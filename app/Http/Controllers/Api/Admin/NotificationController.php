<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:admin_notification,new_reservation',
            'receiver_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $notification = Notification::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'type' => $validated['type'],
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'service_id' => $validated['service_id'] ?? null,
            'reservation_id' => $validated['reservation_id'] ?? null,
            'seen' => false,
        ]);

        return response()->json([
            'message' => 'Notification sent successfully',
            'data' => $notification
        ], 201);
    }
}
