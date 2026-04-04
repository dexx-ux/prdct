<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the change password form.
     */
    public function password(): View
    {
        return view('admin.profile.password');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return Redirect::route('login')->withErrors(['auth' => 'You must be logged in to update your profile.']);
        }
        
        // Validate only if name and email are being updated
        if ($request->has('name') || $request->has('email')) {
            $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);
            
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            
            if ($request->has('email')) {
                $user->email = $request->email;
                
                // Reset email verification if email changed
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }
            }
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }
        
        $user->save();
        
        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }
    
    /**
     * Remove the user's profile photo.
     */
    public function removePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->profile_photo_path) {
            // Delete the file from storage
            Storage::disk('public')->delete($user->profile_photo_path);
            
            // Remove the path from database
            $user->profile_photo_path = null;
            $user->save();
        }
        
        return Redirect::route('admin.profile.edit')->with('photo-removed', true);
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
        
        return Redirect::route('admin.profile.password')->with('password_status', 'Password updated successfully!');
    }
}