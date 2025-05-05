<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        $query = Reservation::with(['user', 'service']);

        // Apply search filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('service', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Apply date range filter
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(10);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return view('admin.reservations.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $reservation->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Reservation status updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        $reservation->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }
}
