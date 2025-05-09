<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $reservations = Auth::user()->reservations()->with('service')->latest()->paginate(10);
        return view('reservations.index', compact('reservations'));
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
            return back()->withErrors(['session_from' => 'This time slot is no longer available.'])->withInput();
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
                    // Continue execution even if notification fails
                }
            }

            DB::commit();
            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Reservation created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create reservation. Please try again.'])->withInput();
        }
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $reservation->update([
            'date' => $request->date,
            'time' => $request->time,
            'notes' => $request->notes,
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation updated successfully!');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation cancelled successfully!');
    }
}
