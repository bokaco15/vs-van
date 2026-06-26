<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReservationsJsonSeeder extends Seeder
{
    /** Srpski naziv meseca -> broj meseca. */
    private array $months = [
        'januar' => 1, 'februar' => 2, 'mart' => 3, 'april' => 4,
        'maj' => 5, 'jun' => 6, 'jul' => 7, 'avgust' => 8,
        'septembar' => 9, 'oktobar' => 10, 'novembar' => 11, 'decembar' => 12,
    ];

    public function run(): void
    {
        $path = base_path('vsvan/reservations.json');
        if (! is_file($path)) {
            $this->command?->warn('reservations.json nije pronađen — preskačem.');

            return;
        }

        $data = json_decode(file_get_contents($path), true) ?: [];

        // van1..van4 -> vozila po sort_order (redosled iz VehicleSeeder).
        $vehicles = Vehicle::orderBy('sort_order')->get()->values();
        $year = now()->year;
        $count = 0;

        foreach (array_values($data) as $index => $monthsData) {
            $vehicle = $vehicles[$index] ?? null;
            if (! $vehicle) {
                continue;
            }

            foreach ($monthsData as $monthName => $days) {
                $month = $this->months[strtolower($monthName)] ?? null;
                if (! $month) {
                    continue;
                }

                foreach ($days as $day) {
                    // Preskoči nevalidne datume (npr. 31. u mesecu sa 30 dana).
                    if (! checkdate($month, (int) $day, $year)) {
                        continue;
                    }

                    Reservation::updateOrCreate([
                        'vehicle_id' => $vehicle->id,
                        'date' => Carbon::create($year, $month, (int) $day)->toDateString(),
                    ], ['status' => 'booked']);

                    $count++;
                }
            }
        }

        $this->command?->info("Uvezeno rezervacija: {$count} (godina {$year}).");
    }
}
