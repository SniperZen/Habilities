<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Appointment;

class AppointmentCanceledNotificationPatient extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
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
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->appointment->patient_id,
            'message' => "Cancelled Our appointment",
            'appointment_date' => $this->appointment->appointment_date,
            'start_time' => $this->appointment->start_time
        ];
    }
    
}
