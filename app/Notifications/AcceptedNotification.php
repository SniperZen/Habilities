<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AcceptedNotification extends Notification
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

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->appointment->patient_id,
            'therapist_id' => $this->appointment->therapist_id,
            'message' => 'Appointment Accepted.',
            'appointment_date' => $this->appointment->appointment_date,
        ];
    }
}
