<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentCanceledNotification extends Notification
{
    use Queueable;

    protected $appointment;
    protected $cancellation_reason;
    protected $cancellation_note;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->cancellation_reason = $appointment->cancellation_reason;
        $this->cancellation_note = $appointment->cancellation_note;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Appointment Canceled',

            'appointment_id' => $this->appointment->id,
            'appointment_date' => $this->appointment->appointment_date,
            'therapist_name' => $this->appointment->therapist->name,
            'cancellation_reason' => $this->cancellation_reason,
            'cancellation_note' => $this->cancellation_note,
            'type' => 'appointment_canceled' // This helps in frontend to identify notification type
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your appointment with ' . $this->appointment->therapist->name . ' scheduled on ' . 
                \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F j, Y') . ' at ' . 
                \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A') . ' has been canceled.' .
                "\n\nReason: " . ($this->appointment->cancellation_reason ?: 'No reason provided') .
                "\nNote: " . ($this->appointment->cancellation_note ?: 'No additional note provided'),
            'appointment_id' => $this->appointment->id,
            'appointment_date' => $this->appointment->appointment_date,
            'therapist_name' => $this->appointment->therapist->name,
            'cancellation_reason' => $this->cancellation_reason,
            'cancellation_note' => $this->cancellation_note,
            'type' => 'appointment_canceled'
        ]);
    }
}
