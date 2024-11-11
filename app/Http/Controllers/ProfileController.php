<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User; 
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);
    
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
    
        return back()->with('status', 'password-updated');
    }
    

    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            /** @var User $user */
            $user = Auth::user();

            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('users-avatar', 'public');
            $user->profile_image = $path;
            $user->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload image.');
    }
    
}
