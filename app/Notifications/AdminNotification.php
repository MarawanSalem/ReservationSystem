<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;
use App\Notifications\Channels\CustomDatabaseChannel;

class AdminNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body, $sender)
    {
        $this->title = $title;
        $this->body = $body;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [CustomDatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => 'admin_notification',
            'data' => [
                'sender_id' => $this->sender->id,
                'sender_name' => $this->sender->name,
            ],
            'sender_id' => $this->sender->id,
            'receiver_id' => $notifiable->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'type' => 'admin_notification'
        ];
    }
}
