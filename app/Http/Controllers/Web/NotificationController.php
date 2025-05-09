<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        if (!Gate::allows('view', $notification)) {
            abort(403);
        }

        if (!$notification->seen) {
            $notification->update(['seen' => true]);
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAsRead(Notification $notification)
    {
        if (!Gate::allows('update', $notification)) {
            abort(403);
        }

        $notification->update(['seen' => true]);

        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('receiver_id', Auth::id())
            ->where('seen', false)
            ->update(['seen' => true]);

        return back()->with('success', 'All notifications marked as read');
    }
}
