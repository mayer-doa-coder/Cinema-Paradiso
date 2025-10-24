<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'bio' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bio' => $request->bio,
            ]);

            // Login the user after registration
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'bio' => $user->bio,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login' => 'required|string', // Can be email or username
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
            $credentials = [
                $loginField => $request->login,
                'password' => $request->password
            ];

            // Handle remember me functionality
            $remember = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user();
                
                // Track login activity
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'login',
                    'activity_data' => [
                        'login_time' => now()->toDateTimeString(),
                        'ip_address' => $request->ip(),
                    ],
                    'points' => UserActivity::getActivityPoints('login'),
                ]);

                // Update last active timestamp
                $user->updateLastActive();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'bio' => $user->bio,
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'Logout successful');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Logout failed: ' . $e->getMessage());
        }
    }

    /**
     * Get currently authenticated user
     */
    public function user(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'bio' => $user->bio,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle forgot password - generate and send temporary password
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid registered email address',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No user found with this email address'
                ], 404);
            }

            // Generate a random temporary password
            $tempPassword = $this->generateTempPassword();

            // Update user's password
            $user->password = Hash::make($tempPassword);
            $user->save();

            // Send email with temporary password
            try {
                \Mail::to($user->email)->send(new \App\Mail\ForgotPasswordMail($user, $tempPassword));
            } catch (\Exception $mailException) {
                // Log the mail error but still return success with password
                \Log::error('Failed to send forgot password email: ' . $mailException->getMessage());
                
                // For development, return the password in response
                return response()->json([
                    'success' => true,
                    'message' => 'Temporary password generated. Email service unavailable.',
                    'temp_password' => $tempPassword, // Remove in production
                    'warning' => 'Please configure mail service'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'A temporary password has been sent to your email address'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process forgot password request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a random temporary password
     */
    private function generateTempPassword($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
        $password = '';
        $charactersLength = strlen($characters);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $password;
    }
}