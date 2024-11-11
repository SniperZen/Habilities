<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentCanceledPatient extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Appointment Cancellation Notice')
                    ->view('emails.appointments.canceled-patient') // Changed from markdown to view
                    ->with([
                        'appointment' => $this->appointment,
                        'patientName' => $this->appointment->patient->name,
                        'appointmentDate' => $this->appointment->appointment_date,
                        'appointmentTime' => $this->appointment->start_time,
                        'cancellationReason' => $this->appointment->patient_reason,
                        'cancellationNote' => $this->appointment->patient_note
                    ]);
    }
}
