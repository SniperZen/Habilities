<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)
                                    ->mixedCase()
                                    ->numbers()
                                    ->symbols()
                                    ->uncompromised(), 'confirmed'],
        ]);
    
        if (Hash::check($validated['current_password'], $request->user()->password)) {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
    
            return back()->with('status', 'password-updated');
        }
    
        return back()->withErrors([
            'current_password' => 'The provided password does not match your current password.',
        ])->withInput();
    }
    
}
