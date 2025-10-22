<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\UserMovie;
use App\Models\UserWatchlist;
use App\Models\UserMovieReview;
use App\Models\UserFavoriteMovie;

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
                UserFavoriteMovie::create([
                    'user_id' => $user->id,
                    'movie_id' => $movieId,
                    'movie_title' => $request->movie_titles[$index] ?? '',
                    'movie_poster' => $request->movie_posters[$index] ?? null,
                ]);
            }
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
     * Add a movie or TV show to the user's watchlist.
     */
    public function addToWatchlist(Request $request)
    {
        $validated = $request->validate([
            'media_id' => 'required|integer',
            'media_type' => 'required|in:movie,tv',
            'title' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Check if already in watchlist using movie_id field (works for both movies and TV)
        $exists = UserWatchlist::where('user_id', $user->id)
            ->where('movie_id', $validated['media_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This item is already in your watchlist.'
            ], 400);
        }

        // Add to watchlist (using movie_id field for both movies and TV shows)
        UserWatchlist::create([
            'user_id' => $user->id,
            'movie_id' => $validated['media_id'],
            'movie_title' => $validated['title'] ?? 'Untitled',
            'media_type' => $validated['media_type'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to watchlist successfully!'
        ]);
    }

    /**
     * Remove a movie or TV show from the user's watchlist.
     */
    public function removeFromWatchlist($id)
    {
        $user = Auth::user();

        $watchlistItem = UserWatchlist::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$watchlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in your watchlist.'
            ], 404);
        }

        $watchlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from watchlist successfully!'
        ]);
    }
}
