<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AppointmentRequested extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'patient_id' => $this->appointment->patient_id,
            'therapist_id' => $this->appointment->therapist_id,
            'patient_name' => $this->appointment->patient->name,
            'therapist_name' => $this->appointment->therapist->name,
            'message' => "Requested an Appointment",
        ];
    }
    
    
}
