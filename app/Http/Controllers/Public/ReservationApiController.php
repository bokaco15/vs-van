<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class ReservationApiController extends Controller
{
    /**
     * Zauzeti dani za jedno vozilo, grupisani po mesecu.
     *
     * Format (pogodan za kalendar u reservation.js):
     *   { "2026-03": [1,2,3], "2026-07": [3,6,7], ... }
     */
    public function index(Vehicle $vehicle): JsonResponse
    {
        $map = $vehicle->reservations()
            ->orderBy('date')
            ->get()
            ->groupBy(fn ($r) => $r->date->format('Y-m'))
            ->map(fn ($group) => $group
                ->map(fn ($r) => (int) $r->date->format('j'))
                ->values()
            );

        return response()->json($map);
    }
}
