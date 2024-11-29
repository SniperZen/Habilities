<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class UpdateMissedAppointments extends Command
{
    protected $signature = 'appointments:update-missed';
    protected $description = 'Update appointments status to missed if they are accepted and past the end_time and appointment_date';

    public function handle()
    {
        $appointments = Appointment::where('status', 'accepted')
            ->whereNotNull('appointment_date')
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->where(function ($query) {
                $query->where('appointment_date', '<=', Carbon::now()->toDateString())
                    ->where('end_time', '<=', Carbon::now()->toTimeString());
            })
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->status = 'missed';
            $appointment->save();
        }

        $this->info('Missed appointments updated successfully!');
    }
}
