<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_student_can_register_and_receives_student_role(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'নতুন শিক্ষার্থী',
            'email' => 'newstudent@example.com',
            'phone' => '+8801812345678',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'roles', 'permissions', 'profile'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newstudent@example.com',
        ]);

        $user = User::where('email', 'newstudent@example.com')->first();
        $this->assertTrue($user->hasRole('Student'));
    }

    public function test_seeded_admin_can_login_successfully(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@emisha.academy',
            'password' => 'Emisha@01805464291#Naznin#@',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user.email', 'admin@emisha.academy')
            ->assertJsonPath('data.user.roles.0', 'SuperAdmin');
    }

    public function test_seeded_manager_and_moderators_can_login_successfully(): void
    {
        // Manager
        $resManager = $this->postJson('/api/v1/auth/login', [
            'email' => 'manager@emisha.academy',
            'password' => 'Emisha@01805464291#@Treker#@',
        ]);
        $resManager->assertStatus(200)->assertJsonPath('data.user.roles.0', 'Manager');

        // Moderator 1
        $resM1 = $this->postJson('/api/v1/auth/login', [
            'email' => 'm1@emisha.academy',
            'password' => 'Emisha@583214#@Treker#@',
        ]);
        $resM1->assertStatus(200)->assertJsonPath('data.user.roles.0', 'Moderator');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@emisha.academy',
            'password' => 'incorrect-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::where('email', 'admin@emisha.academy')->first();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.email', 'admin@emisha.academy');
    }
}
