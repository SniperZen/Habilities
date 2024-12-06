<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EditProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Check user type and return the appropriate view
        if ($user->usertype === 'therapist') {
            return view('therapist.edit', ['user' => $user]);
        } elseif ($user->usertype === 'patient') {
            return view('edit-profile.edit', ['user' => $user]);
        }

        // Optionally handle unauthorized access
        abort(403, 'Unauthorized action.');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update common fields with null handling
        $user->fill([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'home_address' => $validated['home_address'] ?? null,
        ]);

        // Update guardian fields for child accounts
        if ($user->account_type === 'child') {
            // Get the guardian role from the request
            $guardianRole = $request->input('guardian_role');
            
            // If guardian role is 'other', use the other_guardian_role value
            if ($guardianRole === 'other') {
                $guardianRole = $request->input('other_guardian_role');
            }

            $user->fill([
                'guardian_role' => $guardianRole,
                'guardian_name' => $validated['guardian_name'] ?? null,
            ]);
        }

        // Update therapist-specific fields
        if ($user->usertype === 'therapist') {
            $user->fill([
                'teletherapist_link' => $validated['teletherapist_link'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
            ]);
            
            // Handle availability array
            if (isset($validated['availability']) && is_array($validated['availability'])) {
                $user->availability = !empty($validated['availability']) 
                    ? implode(',', $validated['availability']) 
                    : null;
            } else {
                $user->availability = null;
            }
        }

        // Handle email verification if email changes
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
