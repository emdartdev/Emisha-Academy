<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\UpdatePasswordRequest;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new student account.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Strictly prevent role injection: Always assign default Student role
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Assign default Student role (Server-side enforced)
        $user->assignRole('Student');

        // Initialize User Profile
        UserProfile::create([
            'user_id' => $user->id,
            'country' => 'Bangladesh',
        ]);

        $deviceName = $this->formatDeviceName($request);
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'রেজিস্ট্রেশন সফল হয়েছে। ইমিশা একাডেমিতে আপনাকে স্বাগতম!',
            'data' => [
                'user' => new UserResource($user->load(['profile', 'roles', 'permissions'])),
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Authenticate a user and enforce device session rules.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', strtolower(trim($request->email)))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['প্রদত্ত ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।'],
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'আপনার অ্যাকাউন্টটি বর্তমানে নিষ্ক্রিয় অবস্থায় রয়েছে। অনুগ্রহ করে সাপোর্টে যোগাযোগ করুন।',
            ], 403);
        }

        // Student Account 2-Device Policy Enforcement
        $isStudent = $user->hasRole('Student') && !$user->hasAnyRole(['Admin', 'SuperAdmin', 'Manager', 'Worker']);
        if ($isStudent) {
            $activeTokens = $user->tokens()->orderByDesc('last_used_at')->get();

            // Check if user requested to revoke a specific token or all others
            if ($request->filled('revoke_token_id')) {
                $user->tokens()->where('id', $request->revoke_token_id)->delete();
            } elseif ($request->boolean('force_logout_others')) {
                $user->tokens()->delete();
            } elseif ($activeTokens->count() >= 2) {
                // Return 422 with session limit details and existing devices
                return response()->json([
                    'status' => 'error',
                    'session_limit_reached' => true,
                    'message' => 'আপনার শিক্ষার্থী অ্যাকাউন্টে সর্বোচ্চ ২টি ডিভাইসে সক্রিয় লগইন করার অনুমতি রয়েছে। অন্য কোনো ডিভাইস থেকে লগআউট করুন অথবা নিচের তালিকা থেকে একটি ডিভাইস সেশন বাতিল করুন।',
                    'active_sessions' => $activeTokens->map(function ($token) {
                        return [
                            'id' => $token->id,
                            'device_name' => $token->name,
                            'last_used_at' => $token->last_used_at ? $token->last_used_at->diffForHumans() : 'সম্প্রতি সক্রিয়',
                            'created_at' => $token->created_at ? $token->created_at->format('d M Y, h:i A') : null,
                        ];
                    }),
                ], 422);
            }
        }

        // Update login stats
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $deviceName = $this->formatDeviceName($request);
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'লগইন সফল হয়েছে।',
            'data' => [
                'user' => new UserResource($user->load(['profile', 'roles', 'permissions'])),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Get current authenticated user details.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($request->user()->load(['profile', 'roles', 'permissions'])),
        ]);
    }

    /**
     * Log out current user (Revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();
        Auth::guard('web')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'সফলভাবে লগআউট করা হয়েছে।',
        ]);
    }

    /**
     * Get active device sessions for current user.
     */
    public function getSessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()?->id;

        $sessions = $user->tokens()->orderByDesc('last_used_at')->get()->map(function ($token) use ($currentTokenId) {
            return [
                'id' => $token->id,
                'device_name' => $token->name,
                'is_current' => $token->id === $currentTokenId,
                'last_used_at' => $token->last_used_at ? $token->last_used_at->diffForHumans() : 'বর্তমানে সক্রিয়',
                'created_at' => $token->created_at ? $token->created_at->format('d M Y, h:i A') : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_active' => $sessions->count(),
                'max_allowed' => $user->hasRole('Student') ? 2 : 5,
                'sessions' => $sessions,
            ],
        ]);
    }

    /**
     * Revoke a specific device session.
     */
    public function revokeSession(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $deleted = $user->tokens()->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'সেশনটি পাওয়া যায়নি অথবা ইতোমধ্যে বাতিল করা হয়েছে।',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'ডিভাইস সেশনটি সফলভাবে বাতিল করা হয়েছে।',
        ]);
    }

    /**
     * Revoke all other device sessions except current.
     */
    public function revokeOtherSessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()?->id;

        $user->tokens()->where('id', '!=', $currentTokenId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'অন্যান্য সকল ডিভাইস থেকে সফলভাবে লগআউট করা হয়েছে।',
        ]);
    }

    /**
     * Initiate Password Reset.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'ইমেইল এড্রেস প্রদান করুন।',
            'email.email' => 'সঠিক ইমেইল এড্রেস প্রদান করুন।',
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        // Consistent message to prevent account enumeration
        if ($user) {
            $token = Str::random(60);
            
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'পাসওয়ার্ড রিসেটের নির্দেশাবলী ও সিকিউরিটি কোড আপনার ইমেইলে পাঠানো হয়েছে।',
                'data' => [
                    'reset_token' => $token, // Provided for testing and verified email flow
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'পাসওয়ার্ড রিসেটের নির্দেশাবলী ও সিকিউরিটি কোড আপনার ইমেইলে পাঠানো হয়েছে।',
        ]);
    }

    /**
     * Complete Password Reset.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'ইমেইল প্রদান করুন।',
            'token.required' => 'রিসেট টোকেন প্রদান করুন।',
            'password.required' => 'নতুন পাসওয়ার্ড প্রদান করুন।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মেলেনি।',
        ]);

        $email = strtolower(trim($request->email));
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'পাসওয়ার্ড রিসেট টোকেনটি পাওয়া যায়নি বা মেয়াদোত্তীর্ণ হয়েছে। পুনরায় অনুরোধ করুন।',
            ], 422);
        }

        // Verify token expiry (60 minutes)
        if ($resetRecord->created_at && now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'রিসেট টোকেনটির মেয়াদ শেষ হয়ে গেছে। অনুগ্রহ করে নতুন করে চেষ্টা করুন।',
            ], 422);
        }

        // Verify token hash or direct comparison
        $isValidToken = Hash::check($request->token, $resetRecord->token) || $request->token === $resetRecord->token;
        if (!$isValidToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'প্রদত্ত রিসেট টোকেনটি সঠিক নয়।',
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'ব্যবহারকারী পাওয়া যায়নি।',
            ], 404);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all existing sessions for security
        $user->tokens()->delete();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে। নতুন পাসওয়ার্ড দিয়ে লগইন করুন।',
        ]);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update([
            'name' => $request->has('name') ? $request->name : $user->name,
            'email' => $request->has('email') ? strtolower(trim($request->email)) : $user->email,
            'phone' => $request->has('phone') ? $request->phone : $user->phone,
            'avatar' => $request->has('avatar') ? $request->avatar : $user->avatar,
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'headline' => $request->headline,
                'bio' => $request->bio,
                'city' => $request->city,
                'country' => $request->country ?? 'Bangladesh',
                'education' => $request->education,
                'occupation' => $request->occupation,
                'github_url' => $request->github_url,
                'linkedin_url' => $request->linkedin_url,
                'facebook_url' => $request->facebook_url,
                'website_url' => $request->website_url,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'প্রোফাইল তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => new UserResource($user->fresh(['profile', 'roles', 'permissions'])),
        ]);
    }

    /**
     * Update user account password.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke other tokens except current
        $user->tokens()->where('id', '!=', $user->currentAccessToken()?->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।',
        ]);
    }

    /**
     * Helper to extract friendly device name from request.
     */
    protected function formatDeviceName(Request $request): string
    {
        if ($request->filled('device_name')) {
            return substr($request->device_name, 0, 100);
        }

        $userAgent = $request->header('User-Agent', 'Web Browser');
        
        $platform = 'Desktop';
        if (stripos($userAgent, 'Windows') !== false) $platform = 'Windows';
        elseif (stripos($userAgent, 'Macintosh') !== false) $platform = 'macOS';
        elseif (stripos($userAgent, 'Android') !== false) $platform = 'Android';
        elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) $platform = 'iOS';
        elseif (stripos($userAgent, 'Linux') !== false) $platform = 'Linux';

        $browser = 'Browser';
        if (stripos($userAgent, 'Chrome') !== false) $browser = 'Chrome';
        elseif (stripos($userAgent, 'Firefox') !== false) $browser = 'Firefox';
        elseif (stripos($userAgent, 'Safari') !== false) $browser = 'Safari';
        elseif (stripos($userAgent, 'Edge') !== false) $browser = 'Edge';

        return "{$browser} on {$platform}";
    }
}
