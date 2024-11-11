<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Global middleware
    ];

    protected $routeMiddleware = [
        'update.appointments' => \App\Http\Middleware\UpdateAppointmentStatus::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'therapist' => \App\Http\Middleware\Therapist::class,
        'user' => \App\Http\Middleware\User::class,
        'auth.check' => \App\Http\Middleware\EnsureUserIsAuthenticated::class,
    ];

    protected $middlewareGroups = [
        'web' => [
        ],
    ];
    
}
