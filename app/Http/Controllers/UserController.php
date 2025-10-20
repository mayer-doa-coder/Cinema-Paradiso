<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
     * Display the user's watchlist.
     */
    public function watchlist()
    {
        // TODO: Implement watchlist functionality
        return view('profile.userwatchlist');
    }

    /**
     * Display the user's reviews.
     */
    public function reviews()
    {
        // TODO: Implement reviews functionality
        return view('profile.userreviews');
    }

    /**
     * Display the user's rated movies.
     */
    public function movies()
    {
        // TODO: Implement rated movies functionality
        return view('profile.usermovies');
    }

    /**
     * Display the user's custom lists.
     */
    public function list()
    {
        // TODO: Implement lists functionality
        return view('profile.userlist');
    }
}
