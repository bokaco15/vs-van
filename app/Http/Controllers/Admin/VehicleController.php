<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Models\Feature;
use App\Models\Vehicle;
use App\Services\ImageService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        return view('admin.vehicles.index');
    }

    /** Server-side podaci za DataTables. */
    public function data(): JsonResponse
    {
        // orderBy('sort_order') da tabela poštuje redosled iz drag-and-drop-a
        // (DataTables sa order:[] ne šalje svoj order, pa ovaj ostaje na snazi).
        $query = Vehicle::with('coverImage')->select('vehicles.*')->orderBy('sort_order');

        return DataTables::eloquent($query)
            ->setRowAttr(['data-id' => fn (Vehicle $v) => $v->id])
            ->addColumn('drag', fn () => '<i class="bi bi-grip-vertical js-drag text-muted" style="cursor:grab"></i>')
            ->addColumn('cover', function (Vehicle $v) {
                $url = $v->coverImage?->thumb_url;

                return $url
                    ? '<img src="'.e($url).'" class="table-thumb" alt="">'
                    : '<span class="text-muted small">—</span>';
            })
            ->editColumn('type', fn (Vehicle $v) => $v->type === 'van' ? 'Kombi' : 'Auto')
            ->addColumn('badges', function (Vehicle $v) {
                $b = '';
                if ($v->is_recommended) {
                    $b .= '<span class="badge text-bg-success me-1">Preporučeno</span>';
                }
                if ($v->is_featured) {
                    $b .= '<span class="badge text-bg-info me-1">Izdvojeno</span>';
                }
                $b .= $v->is_active
                    ? '<span class="badge text-bg-secondary">Aktivno</span>'
                    : '<span class="badge text-bg-light text-dark border">Sakriveno</span>';

                return $b;
            })
            ->addColumn('actions', function (Vehicle $v) {
                $edit = route('admin.vehicles.edit', $v);

                return '<div class="btn-group btn-group-sm">'
                    .'<a href="'.$edit.'" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>'
                    .'<button type="button" class="btn btn-outline-danger js-delete" data-url="'.route('admin.vehicles.destroy', $v).'"><i class="bi bi-trash"></i></button>'
                    .'</div>';
            })
            ->rawColumns(['drag', 'cover', 'badges', 'actions'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.vehicles.create', [
            'vehicle' => new Vehicle(['type' => 'van', 'is_active' => true]),
            'features' => Feature::orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($this->vehicleData($request));
        $this->syncFeatures($vehicle, $request);

        return $this->ok('Vozilo je kreirano. Sada možete dodati slike.', [
            'redirect' => route('admin.vehicles.edit', $vehicle),
        ], 201);
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicle->load(['images', 'features']);

        return view('admin.vehicles.edit', [
            'vehicle' => $vehicle,
            'features' => Feature::orderBy('sort_order')->get(),
        ]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->update($this->vehicleData($request, $vehicle));
        $this->syncFeatures($vehicle, $request);

        return $this->ok('Izmene su sačuvane.');
    }

    public function destroy(Vehicle $vehicle, ImageService $images): JsonResponse
    {
        // Obriši fajlove slika (osim statičkih seed assета), pa vozilo (kaskadno briše redove).
        foreach ($vehicle->images as $image) {
            $images->delete($image->path, $image->thumb_path);
        }
        $vehicle->delete();

        return $this->ok('Vozilo je obrisano.');
    }

    /** Sačuvaj redosled vozila (drag-and-drop). */
    public function sort(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $position => $id) {
            Vehicle::whereKey($id)->update(['sort_order' => $position]);
        }

        return $this->ok('Redosled je sačuvan.');
    }

    /** Priprema podataka za upis (uklj. jedinstven slug). */
    private function vehicleData(StoreVehicleRequest $request, ?Vehicle $vehicle = null): array
    {
        $data = $request->only([
            'type', 'name', 'subtitle', 'description',
            'sort_order', 'is_recommended', 'is_featured', 'is_active',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? (Vehicle::max('sort_order') + 1);
        $data['slug'] = $this->uniqueSlug($request->input('name'), $vehicle?->id);

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Vehicle::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }

    /** Sinhronizuj specifikacije sa opcionim vrednostima po pivotu. */
    private function syncFeatures(Vehicle $vehicle, StoreVehicleRequest $request): void
    {
        $values = $request->input('feature_values', []);
        $sync = [];
        foreach ($request->input('features', []) as $featureId) {
            $sync[$featureId] = ['value' => $values[$featureId] ?? null];
        }
        $vehicle->features()->sync($sync);
    }
}
