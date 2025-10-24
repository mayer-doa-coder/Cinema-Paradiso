<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserMovie;
use App\Models\UserWatchlist;
use App\Models\UserMovieReview;
use App\Models\UserFavoriteMovie;
use App\Models\UserFollower;
use App\Models\UserActivity;

class UserController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function profile()
    {
        return view('profile.userprofile');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Update name if first_name and last_name are provided
        if ($request->filled('first_name') || $request->filled('last_name')) {
            $user->name = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        } elseif ($request->filled('username')) {
            $user->name = $request->username;
        }

        // Update username if provided
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // Update other fields
        $user->email = $validated['email'];
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->bio = $request->bio;
        
        $user->save();

        // Track profile update activity
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'profile_update',
            'activity_data' => [
                'updated_at' => now()->toDateTimeString(),
            ],
            'points' => UserActivity::getActivityPoints('profile_update'),
        ]);

        $user->updateLastActive();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'old_password.required' => 'Please enter your current password.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'The provided password does not match your current password.',
            ])->withInput();
        }

        // Update the password in database
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Logout the user after password change
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home with a flag to show login popup
        return redirect()->route('home')->with('password_changed', true)->with('message', 'Password changed successfully! Please login with your new password.');
    }

    /**
     * Update user's favorite movies.
     */
    public function updateFavorites(Request $request)
    {
        $request->validate([
            'favorite_movies' => 'nullable|array|max:5',
            'favorite_movies.*' => 'required|integer',
            'movie_titles' => 'nullable|array|max:5',
            'movie_titles.*' => 'required|string',
            'movie_posters' => 'nullable|array|max:5',
        ]);

        $user = Auth::user();
        
        // Delete all existing favorite movies for the user
        UserFavoriteMovie::where('user_id', $user->id)->delete();
        
        // Add new favorite movies
        if ($request->has('favorite_movies') && is_array($request->favorite_movies)) {
            foreach ($request->favorite_movies as $index => $movieId) {
                $movieTitle = $request->movie_titles[$index] ?? '';
                $moviePoster = $request->movie_posters[$index] ?? null;
                
                // Create favorite movie entry
                UserFavoriteMovie::create([
                    'user_id' => $user->id,
                    'movie_id' => $movieId,
                    'movie_title' => $movieTitle,
                    'movie_poster' => $moviePoster,
                ]);

                // Also add to UserMovie collection if not already there
                UserMovie::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'movie_id' => $movieId,
                    ],
                    [
                        'movie_title' => $movieTitle,
                        'movie_poster' => $moviePoster,
                        'rating' => 0, // Default rating, user can update later
                        'release_year' => null,
                    ]
                );
            }
            
            // Update total movies watched count
            $user->total_movies_watched = UserMovie::where('user_id', $user->id)->count();
            $user->save();
        }

        return redirect()->route('user.profile')->with('success', 'Favorite movies updated successfully!');
    }

    /**
     * Display the user's watchlist.
     */
    public function watchlist()
    {
        $watchlist = UserWatchlist::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('profile.userwatchlist', compact('watchlist'));
    }

    /**
     * Display the user's reviews.
     */
    public function reviews()
    {
        $reviews = UserMovieReview::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('profile.userreviews', compact('reviews'));
    }

    /**
     * Display user's rated movies.
     */
    public function movies()
    {
        $movies = UserMovie::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('profile.usermovies', compact('movies'));
    }

    /**
     * Display the user's custom lists.
     */
    public function list()
    {
        // TODO: Implement lists functionality
        return view('profile.userlist');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'avatar.required' => 'Please select an image to upload.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Only jpeg, png, jpg, and gif images are allowed.',
            'avatar.max' => 'The image size must not exceed 2MB.',
        ]);

        $user = Auth::user();
        
        // Prevent rapid successive uploads (within 3 seconds)
        if ($user->updated_at && $user->updated_at->diffInSeconds(now()) < 3) {
            return back()->with('error', 'Please wait a moment before uploading again.');
        }

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
        // Update user's avatar path
        $user->avatar = $avatarPath;
        $user->save();

        return back()->with('success', 'Avatar updated successfully!');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        // Delete avatar file if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Clear avatar path from database
        $user->avatar = null;
        $user->save();

        return back()->with('success', 'Avatar deleted successfully!');
    }

    /**
     * Show the following page
     */
    public function following()
    {
        $user = Auth::user();
        
        // Get users that the current user is following
        $following = User::whereIn('id', function($query) use ($user) {
            $query->select('following_id')
                  ->from('user_followers')
                  ->where('follower_id', $user->id);
        })->get();
        
        return view('profile.following', compact('user', 'following'));
    }

    /**
     * Show the followers page
     */
    public function followers()
    {
        $user = Auth::user();
        
        // Get users who are following the current user
        $followers = User::whereIn('id', function($query) use ($user) {
            $query->select('follower_id')
                  ->from('user_followers')
                  ->where('following_id', $user->id);
        })->get();
        
        // Get list of users that current user is following (for follow-back check)
        $followingIds = UserFollower::where('follower_id', $user->id)
                                    ->pluck('following_id')
                                    ->toArray();
        
        return view('profile.followers', compact('user', 'followers', 'followingIds'));
    }

    /**
     * Follow a user
     */
    public function follow(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Prevent following yourself
        if ($currentUser->id == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself'], 400);
        }
        
        // Check if user exists
        $userToFollow = User::find($userId);
        if (!$userToFollow) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        
        // Check if already following
        $existingFollow = UserFollower::where('follower_id', $currentUser->id)
                                      ->where('following_id', $userId)
                                      ->first();
        
        if ($existingFollow) {
            return response()->json(['success' => false, 'message' => 'Already following this user'], 400);
        }
        
        // Create the follow relationship
        UserFollower::create([
            'follower_id' => $currentUser->id,
            'following_id' => $userId
        ]);

        // Track activity
        UserActivity::create([
            'user_id' => $currentUser->id,
            'activity_type' => 'follow',
            'activity_data' => [
                'followed_user_id' => $userId,
                'followed_user_name' => $userToFollow->name,
            ],
            'points' => UserActivity::getActivityPoints('follow'),
        ]);

        // Update popularity scores
        $currentUser->updatePopularityScore();
        $userToFollow->updatePopularityScore();
        
        return response()->json([
            'success' => true, 
            'message' => 'Successfully followed ' . $userToFollow->name,
            'followers_count' => $userToFollow->followers()->count()
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Find and delete the follow relationship
        $deleted = UserFollower::where('follower_id', $currentUser->id)
                               ->where('following_id', $userId)
                               ->delete();
        
        if ($deleted) {
            $userToUnfollow = User::find($userId);
            return response()->json([
                'success' => true, 
                'message' => 'Successfully unfollowed ' . ($userToUnfollow ? $userToUnfollow->name : 'user'),
                'followers_count' => $userToUnfollow ? $userToUnfollow->followers()->count() : 0
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Not following this user'], 400);
    }
}
