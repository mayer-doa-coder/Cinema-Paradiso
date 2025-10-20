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
            'username' => 'nullable|string|max:255|unique:users,name,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
        ]);

        // Update name if first_name and last_name are provided
        if ($request->filled('first_name') || $request->filled('last_name')) {
            $user->name = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        } elseif ($request->filled('username')) {
            $user->name = $request->username;
        }

        $user->email = $validated['email'];
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
        ]);

        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['The provided password does not match your current password.'],
            ]);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Display the user's favorite movies.
     */
    public function favorites()
    {
        // TODO: Implement favorites functionality
        return view('profile.userfavorites');
    }

    /**
     * Display the user's rated movies.
     */
    public function rated()
    {
        // TODO: Implement rated movies functionality
        return view('profile.userrated');
    }
}
