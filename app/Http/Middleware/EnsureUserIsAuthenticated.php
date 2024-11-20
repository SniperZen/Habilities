<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;  // Add this line

class EnsureUserIsAuthenticated
{
    public function handle($request, Closure $next)
    {
        // If user is not authenticated and trying to access protected routes
        if (!Auth::check() && $request->is('dashboard', 'admin/*', 'therapist/*', 'patient/*')) {
            return redirect('/login');
        }

        // If authenticated user tries to access login page
        if (Auth::check()) {
            if ($request->is('login')) {
                return $this->redirectBasedOnUserType();
            }

            // Redirect if user tries to access wrong dashboard
            $usertype = Auth::user()->usertype;
            $currentPath = $request->path();

            $allowedPaths = [
                'admin' => ['admin/*'],
                'therapist' => ['therapist/*'],
                'user' => ['patient/*']
            ];

            // Check if user is accessing allowed paths
            if (isset($allowedPaths[$usertype])) {
                $allowed = false;
                foreach ($allowedPaths[$usertype] as $path) {
                    if (Str::is($path, $currentPath)) {  // Changed from str_is to Str::is
                        $allowed = true;
                        break;
                    }
                }
                
                if (!$allowed) {
                    return $this->redirectBasedOnUserType();
                }
            }
            
        }

        return $next($request);
    }

    private function redirectBasedOnUserType()
    {
        $usertype = Auth::user()->usertype;
        
        $redirectPaths = [
            'admin' => '/admin/dash',
            'therapist' => '/therapist/dash',
            'user' => '/patient/dash'
        ];

        return redirect($redirectPaths[$usertype] ?? '/dashboard');
    }
}
