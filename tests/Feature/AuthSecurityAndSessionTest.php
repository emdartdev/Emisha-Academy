<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthSecurityAndSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Worker', 'guard_name' => 'web']);
    }

    public function test_public_registration_strictly_assigns_student_role_ignoring_payload_injection(): void
    {
        $payload = [
            'name' => 'Test Student',
            'email' => 'student.qa@emisha.academy',
            'phone' => '01711223344',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role' => 'Admin', // Attempt privilege escalation
            'is_admin' => true,
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $user = User::where('email', 'student.qa@emisha.academy')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Student'));
        $this->assertFalse($user->hasRole('Admin'));
    }

    public function test_student_login_enforces_maximum_two_active_devices_limit(): void
    {
        $user = User::create([
            'name' => 'Limit Student',
            'email' => 'student.limit@emisha.academy',
            'password' => Hash::make('Password@123'),
            'status' => 'active',
        ]);
        $user->assignRole('Student');

        // Device 1 login
        $res1 = $this->postJson('/api/v1/auth/login', [
            'email' => 'student.limit@emisha.academy',
            'password' => 'Password@123',
            'device_name' => 'Chrome on Windows',
        ]);
        $res1->assertStatus(200);

        // Device 2 login
        $res2 = $this->postJson('/api/v1/auth/login', [
            'email' => 'student.limit@emisha.academy',
            'password' => 'Password@123',
            'device_name' => 'Safari on iPhone',
        ]);
        $res2->assertStatus(200);

        $this->assertEquals(2, $user->tokens()->count());

        // Device 3 login attempt - Must be blocked with 422
        $res3 = $this->postJson('/api/v1/auth/login', [
            'email' => 'student.limit@emisha.academy',
            'password' => 'Password@123',
            'device_name' => 'Firefox on Android',
        ]);

        $res3->assertStatus(422)
            ->assertJsonPath('session_limit_reached', true)
            ->assertJsonStructure(['active_sessions']);

        $this->assertEquals(2, $user->tokens()->count());

        // Device 3 login with explicit revocation of Device 1
        $firstToken = $user->tokens()->first();
        $res4 = $this->postJson('/api/v1/auth/login', [
            'email' => 'student.limit@emisha.academy',
            'password' => 'Password@123',
            'device_name' => 'Firefox on Android',
            'revoke_token_id' => $firstToken->id,
        ]);

        $res4->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertEquals(2, $user->tokens()->count());
    }

    public function test_authenticated_user_can_view_and_revoke_active_sessions(): void
    {
        $user = User::create([
            'name' => 'Sessions User',
            'email' => 'sessions.user@emisha.academy',
            'password' => Hash::make('Password@123'),
            'status' => 'active',
        ]);
        $user->assignRole('Student');

        $token1 = $user->createToken('Device 1');
        $token2 = $user->createToken('Device 2');

        // Fetch sessions
        $response = $this->withToken($token1->plainTextToken)
            ->getJson('/api/v1/auth/sessions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.total_active', 2);

        // Revoke Device 2
        $revokeRes = $this->withToken($token1->plainTextToken)
            ->deleteJson('/api/v1/auth/sessions/' . $token2->accessToken->id);

        $revokeRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertEquals(1, $user->tokens()->count());
    }

    public function test_password_reset_flow_lifecycle(): void
    {
        $user = User::create([
            'name' => 'Reset User',
            'email' => 'reset.user@emisha.academy',
            'password' => Hash::make('OldPassword@123'),
            'status' => 'active',
        ]);
        $user->assignRole('Student');

        // 1. Request Reset
        $reqRes = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'reset.user@emisha.academy',
        ]);

        $reqRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $token = $reqRes->json('data.reset_token');
        $this->assertNotEmpty($token);

        // 2. Complete Reset
        $resetRes = $this->postJson('/api/v1/auth/reset-password', [
            'email' => 'reset.user@emisha.academy',
            'token' => $token,
            'password' => 'NewSecurePassword@2026',
            'password_confirmation' => 'NewSecurePassword@2026',
        ]);

        $resetRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 3. Verify old password fails
        $failLogin = $this->postJson('/api/v1/auth/login', [
            'email' => 'reset.user@emisha.academy',
            'password' => 'OldPassword@123',
        ]);
        $failLogin->assertStatus(422);

        // 4. Verify new password succeeds
        $successLogin = $this->postJson('/api/v1/auth/login', [
            'email' => 'reset.user@emisha.academy',
            'password' => 'NewSecurePassword@2026',
        ]);
        $successLogin->assertStatus(200);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive.user@emisha.academy',
            'password' => Hash::make('Password@123'),
            'status' => 'inactive',
        ]);
        $user->assignRole('Student');

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'inactive.user@emisha.academy',
            'password' => 'Password@123',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('status', 'error');
    }

    public function test_logout_invalidates_current_token_and_blocks_further_api_access(): void
    {
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout.user@emisha.academy',
            'password' => Hash::make('Password@123'),
            'status' => 'active',
        ]);
        $user->assignRole('Student');

        $tokenInstance = $user->createToken('Test Device');
        $token = $tokenInstance->plainTextToken;
        $tokenId = $tokenInstance->accessToken->id;

        // Verify token in DB
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $tokenId]);

        // Logout
        $logoutRes = $this->withToken($token)->postJson('/api/v1/auth/logout');
        $logoutRes->assertStatus(200);

        // Verify token is deleted from DB
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);

        // Unauthenticated request (reset test runner auth state)
        $this->app['auth']->forgetGuards();
        $unauthRes = $this->getJson('/api/v1/auth/me');
        $unauthRes->assertStatus(401);
    }
}
