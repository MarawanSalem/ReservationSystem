<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'service_name' => $this->reservation->service->name,
            'user_name' => $this->reservation->user->name,
            'date' => $this->reservation->date,
            'session_from' => $this->reservation->session_from,
            'session_to' => $this->reservation->session_to,
            'message' => "New reservation for {$this->reservation->service->name} by {$this->reservation->user->name}",
            'type' => 'new_reservation'
        ];
    }
}
