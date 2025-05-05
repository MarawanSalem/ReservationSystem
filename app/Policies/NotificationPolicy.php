<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function view(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id;
    }

    public function update(User $user, DatabaseNotification $notification)
    {
        return $user->id === $notification->notifiable_id;
    }
}
