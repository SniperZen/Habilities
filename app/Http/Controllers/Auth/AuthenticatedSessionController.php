<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\UserLogin;
use App\Models\UserLogout; 



class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        // Check if the account is inactive
        if ($request->user()->account_status === 'inactive') {
            Auth::logout();
        
            // Redirect back with an error message
            return redirect()->back()->with('status', __('Your account is deactivated.'));
        }
    
        // Log the successful login
        UserLogin::create([
            'user_id' => $request->user()->id,
            'login_at' => now(),
        ]);
    
        $request->session()->regenerate();
    
        // Redirect based on usertype
        switch ($request->user()->usertype) {
            case 'admin':
                return redirect('admin/dash');
            case 'therapist':
                return redirect('therapist/dash');
            case 'user':
                return redirect('patient/dash');
            default:
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Check if the user is authenticated
        if (Auth::guard('web')->check()) {
            // Log the user logout event
            UserLogout::create([
                'user_id' => Auth::id(),
                'logged_out_at' => now(),
            ]);
        }
    
        // Log the user out
        Auth::guard('web')->logout();
    
        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
