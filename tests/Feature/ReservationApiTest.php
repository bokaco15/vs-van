<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_booked_days_grouped_by_month(): void
    {
        $vehicle = Vehicle::create([
            'type' => 'van', 'name' => 'Test', 'slug' => 'test', 'is_active' => true,
        ]);
        Reservation::create(['vehicle_id' => $vehicle->id, 'date' => '2026-03-05']);
        Reservation::create(['vehicle_id' => $vehicle->id, 'date' => '2026-03-10']);
        Reservation::create(['vehicle_id' => $vehicle->id, 'date' => '2026-07-01']);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}/reservations");

        $response->assertOk()->assertJson([
            '2026-03' => [5, 10],
            '2026-07' => [1],
        ]);
    }
}
