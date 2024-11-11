<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $accountType = $request->query('account_type', 'self');
        return view('auth.register', compact('accountType'));
    }

    /**
     * Handle the registration of a new user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate($this->validationRules());

        // Create a new user instance
        $user = User::create([
            'email' => $request->email,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'home_address' => $request->home_address,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
        ]);

        // Fire the registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to verification notice
        return redirect()->route('verification.notice');
    }

    /**
     * Get the validation rules for user registration.
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $birthDate = Carbon::parse($value);
                        if ($birthDate->isFuture()) {
                            $fail('Birth date cannot be in the future.');
                        }
                        if ($birthDate->age > 120) {
                            $fail('Please enter a valid birth date.');
                        }
                    }
                },
            ],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'contact_number' => ['required', 'string', 'max:20'],
            'home_address' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8', // Minimum length
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[~`!@#$%^&*()\-_\+={}[\]|\\;:"<>,.\/?]/', // At least one special character
            ],
            'account_type' => ['required', 'string', 'in:self,child'],
        ];
    }
}
