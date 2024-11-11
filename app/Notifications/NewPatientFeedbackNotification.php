<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PatientFeedback;

class NewPatientFeedbackNotification extends Notification
{
    use Queueable;

    protected $feedback;

    public function __construct(PatientFeedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'feedback_id' => $this->feedback->id,
            'user_id' => $this->feedback->user_id,
            'message' => 'New feedback received',
        ];
    }
}
