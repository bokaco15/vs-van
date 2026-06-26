<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        return view('admin.reservations.index', [
            'vehicles' => Vehicle::orderBy('sort_order')->get(),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $query = Reservation::with('vehicle')->select('reservations.*');

        // Opcioni filter po vozilu.
        if ($vehicleId = $request->query('vehicle_id')) {
            $query->where('vehicle_id', $vehicleId);
        }

        return DataTables::eloquent($query)
            ->addColumn('vehicle', fn (Reservation $r) => $r->vehicle?->name ?? '—')
            ->editColumn('date', fn (Reservation $r) => $r->date->format('d.m.Y.'))
            ->addColumn('actions', function (Reservation $r) {
                return '<button type="button" class="btn btn-sm btn-outline-danger js-delete" '
                    .'data-url="'.route('admin.reservations.destroy', $r).'"><i class="bi bi-trash"></i></button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'date' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:255'],
        ], [], ['vehicle_id' => 'vozilo', 'date' => 'datum']);

        // Spreči duplikat (isto vozilo + datum).
        $reservation = Reservation::updateOrCreate(
            ['vehicle_id' => $data['vehicle_id'], 'date' => $data['date']],
            ['status' => $data['status'] ?? 'booked', 'note' => $data['note'] ?? null]
        );

        return $this->ok(
            $reservation->wasRecentlyCreated ? 'Termin je dodat kao zauzet.' : 'Termin je ažuriran.',
            null,
            201
        );
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return $this->ok('Termin je oslobođen.');
    }
}
