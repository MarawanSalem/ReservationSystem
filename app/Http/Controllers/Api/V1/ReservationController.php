<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->reservations()->with('service');

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $reservations = $query->paginate(10);

        return response()->json([
            'data' => $reservations->items(),
            'meta' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
            ]
        ]);
    }

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'session_from' => 'required|date_format:H:i',
            'session_to' => 'required|date_format:H:i|after:session_from',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for overlapping reservations
        $overlapping = $service->reservations()
            ->whereDate('date', $request->date)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('session_from', '<', $request->session_to)
                      ->where('session_to', '>', $request->session_from);
                });
            })->exists();

        if ($overlapping) {
            return response()->json([
                'message' => 'This time slot is no longer available.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create reservation
            $reservation = Auth::user()->reservations()->create([
                'service_id' => $service->id,
                'date' => $request->date,
                'session_from' => $request->session_from,
                'session_to' => $request->session_to,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Eager load the relationships
            $reservation->load(['service', 'user']);

            // Notify admin users
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new NewReservationNotification($reservation));
                } catch (\Exception $e) {
                    Log::warning("Failed to send notification to admin {$admin->id}: " . $e->getMessage());
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Reservation created successfully',
                'data' => $reservation
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create reservation. Please try again.'
            ], 500);
        }
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $reservation->delete();

        return response()->json([
            'message' => 'Reservation cancelled successfully'
        ]);
    }
}
