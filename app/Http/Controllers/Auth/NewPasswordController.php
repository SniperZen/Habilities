<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming request data
        $request->validate($this->validationRules());

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $this->updatePassword($user, $request->password);
            }
        );

        // Redirect based on the status of the password reset
        return $this->handleResetResponse($status, $request);
    }

    /**
     * Get the validation rules for password reset.
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8) // Minimum length
                    ->mixedCase() // At least one uppercase and one lowercase letter
                    ->numbers() // At least one number
                    ->symbols() // At least one symbol
                    ->uncompromised(), // Check against known compromised passwords
            ],
        ];
    }

    /**
     * Update the user's password and fire the PasswordReset event.
     *
     * @param $user
     * @param string $password
     * @return void
     */
    protected function updatePassword($user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    }

    /**
     * Handle the response after attempting to reset the password.
     *
     * @param string $status
     * @param Request $request
     * @return RedirectResponse
     */
    protected function handleResetResponse(string $status, Request $request): RedirectResponse
    {
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($status)]);
    }
}
