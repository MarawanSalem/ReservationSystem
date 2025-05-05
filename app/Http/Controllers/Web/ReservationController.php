<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Auth::user()->reservations()->with('service')->latest()->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $reservation = Auth::user()->reservations()->create([
            'service_id' => $service->id,
            'date' => $request->date,
            'time' => $request->time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation created successfully!');
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
