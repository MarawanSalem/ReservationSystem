<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function show(Service $service)
    {
        // Check if the date parameter is set to load available time slots
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
        $serviceDuration = $service->duration; // Duration in minutes

        // Generate slots every hour
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

        return view('services.show', compact('service', 'availableSlots', 'date'));
    }
}
