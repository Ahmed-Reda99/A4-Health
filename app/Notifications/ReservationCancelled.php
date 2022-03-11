<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationCancelled extends Notification
{
    use Queueable;
    private Reservation $cancelledReservation;
    
    public function __construct(Reservation $reservationData)
    {
        $this->cancelledReservation = $reservationData;
    }

    
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
            'type'=>'Cencelling Reservation',
            'patient'=>$this->cancelledReservation->patient->user->fname." ".$this->cancelledReservation->patient->user->lname,
            'date'=>$this->cancelledReservation->appointment->date
        ];
    }
}
