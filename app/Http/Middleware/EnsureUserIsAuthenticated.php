<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() && $request->is('dashboard')) {
            return redirect('/login');
        }

        if (Auth::check() && $request->is('login')) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}

