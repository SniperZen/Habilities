<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateAppointmentStatus
{
    public function handle(Request $request, Closure $next)
    {
        $yesterday = Carbon::now()->subDay()->startOfDay();

        DB::table('appointments')
            ->where('status', 'accepted')
            ->where('appointment_date', '<', $yesterday)
            ->update(['status' => 'missed']);

        return $next($request);
    }
}


