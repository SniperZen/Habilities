<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppointmentCancelPatient extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
        // Debug log for constructor
        Log::info('AppointmentCancelPatient Constructor', [
            'appointment_id' => $appointment->id ?? 'null',
            'appointment_data' => $appointment->toArray(),
        ]);
    }

    public function build()
    {
        try {
            // Debug logs for relationships
            Log::info('AppointmentCancelPatient Build Method - Relationship Check', [
                'has_patient' => isset($this->appointment->patient),
                'has_therapist' => isset($this->appointment->therapist),
                'patient_data' => $this->appointment->patient ? $this->appointment->patient->toArray() : 'null',
                'therapist_data' => $this->appointment->therapist ? $this->appointment->therapist->toArray() : 'null',
                'start_time' => $this->appointment->start_time,
                'end_time' => $this->appointment->end_time,
                'mode' => $this->appointment->mode,
                'patient_reason' => $this->appointment->patient_reason,
                'patient_note' => $this->appointment->patient_note,
            ]);

            return $this->subject('Appointment Cancellation Notice')
                        ->view('emails.appointment-cancel-patient')
                        ->with([
                            'appointment' => $this->appointment,
                            'patient' => $this->appointment->patient,
                            'therapist' => $this->appointment->therapist,
                        ]);
        } catch (\Exception $e) {
            Log::error('Error in AppointmentCancelPatient build method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
