<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Feedback;
use App\Models\User;

class TherapistFeedbackNotification extends Notification
{
    use Queueable;

    protected $feedback;
    protected $therapist;


    public function __construct(User $therapist, Feedback $feedback)
    {
        $this->therapist = $therapist;
        $this->feedback = $feedback;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your therapist has provided new feedback.')
                    ->line('Title: ' . $this->feedback->title)
                    ->action('View Feedback', url('/patient/feedback/' . $this->feedback->id))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'feedback_id' => $this->feedback->id,  // Changed this line
            'therapist_id' => $this->feedback->id,  // Changed this if therapist_id is the same as id
            'message' => 'Your therapist has provided feedback.',
            'title' => $this->feedback->title,
            // other relevant data
        ];
    }
    
    
    
    
}
