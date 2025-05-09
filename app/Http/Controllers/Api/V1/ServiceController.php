<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Apply filters
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('service_provider', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $services = $query->paginate(10);

        return response()->json([
            'data' => $services->items(),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
            ]
        ]);
    }

    public function show(Service $service)
    {
        $service->load('addedBy');

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'service_provider' => $service->service_provider,
            'location' => $service->location,
            'price' => $service->price,
            'duration' => $service->duration,
            'category' => $service->category,
            'rating' => $service->rating,
            'image' => $service->image ? asset('storage/' . $service->image) : null,
            'added_by' => $service->addedBy->name,
            'created_at' => $service->created_at->format('Y-m-d H:i:s'),
            'available_slots' => $this->getAvailableSlots($service)
        ]);
    }

    private function getAvailableSlots(Service $service)
    {
        $date = request('date', now()->format('Y-m-d'));

        // Get booked time slots for the selected date
        $bookedSlots = $service->reservations()
            ->whereDate('date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['session_from', 'session_to'])
            ->map(function($reservation) {
                return [
                    'start' => $reservation->session_from->format('H:i'),
                    'end' => $reservation->session_to->format('H:i')
                ];
            })
            ->toArray();

        // Generate available time slots
        $availableSlots = [];
        $start = 9; // 9 AM
        $end = 17;  // 5 PM
        $serviceDuration = $service->duration;

        for ($hour = $start; $hour <= $end - ($serviceDuration / 60); $hour++) {
            $slotStart = sprintf('%02d:00', $hour);
            $slotEndTime = strtotime("+{$serviceDuration} minutes", strtotime($slotStart));
            $slotEnd = date('H:i', $slotEndTime);

            // Check if slot overlaps with any booked slots
            $isAvailable = true;
            foreach ($bookedSlots as $bookedSlot) {
                if (!(strtotime($slotEnd) <= strtotime($bookedSlot['start']) ||
                      strtotime($slotStart) >= strtotime($bookedSlot['end']))) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = [
                    'start' => $slotStart,
                    'end' => $slotEnd
                ];
            }
        }

        return $availableSlots;
    }
}
