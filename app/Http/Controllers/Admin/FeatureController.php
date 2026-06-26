<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        return view('admin.features.index');
    }

    public function data(): JsonResponse
    {
        return DataTables::eloquent(Feature::query())
            ->addColumn('icon', function (Feature $f) {
                return $f->icon_url
                    ? '<img src="'.e($f->icon_url).'" class="table-thumb" style="object-fit:contain" alt="">'
                    : '<span class="text-muted small">—</span>';
            })
            ->addColumn('actions', function (Feature $f) {
                return '<div class="btn-group btn-group-sm">'
                    .'<button type="button" class="btn btn-outline-primary js-edit" '
                    .'data-id="'.$f->id.'" data-name="'.e($f->name).'" data-sort_order="'.$f->sort_order.'"><i class="bi bi-pencil"></i></button>'
                    .'<button type="button" class="btn btn-outline-danger js-delete" data-url="'.route('admin.features.destroy', $f).'"><i class="bi bi-trash"></i></button>'
                    .'</div>';
            })
            ->rawColumns(['icon', 'actions'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateData($request);
        $data['icon_path'] = $this->storeIcon($request);

        Feature::create($data);

        return $this->ok('Specifikacija je dodata.', null, 201);
    }

    public function update(Request $request, Feature $feature): JsonResponse
    {
        $data = $this->validateData($request, $feature->id);
        if ($path = $this->storeIcon($request)) {
            $this->deleteIcon($feature->icon_path);
            $data['icon_path'] = $path;
        }

        $feature->update($data);

        return $this->ok('Izmene su sačuvane.');
    }

    public function destroy(Feature $feature): JsonResponse
    {
        $this->deleteIcon($feature->icon_path);
        $feature->delete();

        return $this->ok('Specifikacija je obrisana.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:features,name'.($ignoreId ? ",{$ignoreId}" : '')],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'icon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:1024'],
        ], [], ['name' => 'naziv']);
    }

    /** Ikonice čuvamo kao original (zbog SVG-a) na public disk. */
    private function storeIcon(Request $request): ?string
    {
        if (! $request->hasFile('icon')) {
            return null;
        }

        return $request->file('icon')->store('features', 'public');
    }

    private function deleteIcon(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'img/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
