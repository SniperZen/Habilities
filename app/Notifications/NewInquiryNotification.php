<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Inquiry;

class NewInquiryNotification extends Notification
{
    use Queueable;

    protected $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'user_id' => $this->inquiry->user_id,
            'user_name' => $this->inquiry->user->name, // Assuming you have a user relationship
            'message' => 'New inquiry submitted.',
        ];
    }
    
}
