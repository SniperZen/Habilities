<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Console\Scheduling\Schedule;

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
        \App\Http\Middleware\EnsureUserIsAuthenticated::class,

    ];
    protected $commands = [
        \App\Console\Commands\UpdateMissedAppointments::class,
    ];
    protected function schedule(Schedule $schedule)
{
    $schedule->command('appointments:update-missed')
              ->daily();
}

}
