<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;

class CustomDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return NotificationModel::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'type' => $data['type'],
            'data' => $data['data'],
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
        ]);
    }
}
