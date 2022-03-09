<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationAdded extends Notification
{
    use Queueable;
    private Reservation $newReservation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservationData)
    {
        $this->newReservation = $reservationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type'=>'New Reservation',
            'patient'=>$this->newReservation->patient->user->fname." ".$this->newReservation->patient->user->lname,
            'date'=>$this->newReservation->appointment->date
        ];
    }
}
