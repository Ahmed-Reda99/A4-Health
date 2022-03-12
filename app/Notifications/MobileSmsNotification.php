<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class MobileSmsNotification extends Notification
{
    use Queueable;
    private $verificationCode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($generatedCode)
    {
        $this->verificationCode = $generatedCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['nexmo'];
    }

    
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)->content('Your Verification Code: '.$this->verificationCode)->from('A4-Health');;
    }

}
