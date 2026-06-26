<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferItem;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class OfferItemController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        return view('admin.offer-items.index');
    }

    public function data(): JsonResponse
    {
        return DataTables::eloquent(OfferItem::query()->orderBy('sort_order'))
            ->setRowAttr(['data-id' => fn (OfferItem $o) => $o->id])
            ->addColumn('drag', fn () => '<i class="bi bi-grip-vertical js-drag text-muted" style="cursor:grab"></i>')
            ->addColumn('icon', fn (OfferItem $o) => $o->icon_url
                ? '<img src="'.e($o->icon_url).'" class="table-thumb" style="object-fit:contain" alt="">'
                : '<span class="text-muted small">—</span>')
            ->editColumn('is_active', fn (OfferItem $o) => $o->is_active
                ? '<span class="badge text-bg-success">Da</span>'
                : '<span class="badge text-bg-secondary">Ne</span>')
            ->addColumn('actions', function (OfferItem $o) {
                return '<div class="btn-group btn-group-sm">'
                    .'<button type="button" class="btn btn-outline-primary js-edit" '
                    .'data-id="'.$o->id.'" data-heading="'.e($o->heading).'" data-description="'.e($o->description).'" '
                    .'data-sort_order="'.$o->sort_order.'" data-is_active="'.(int) $o->is_active.'"><i class="bi bi-pencil"></i></button>'
                    .'<button type="button" class="btn btn-outline-danger js-delete" data-url="'.route('admin.offer-items.destroy', $o).'"><i class="bi bi-trash"></i></button>'
                    .'</div>';
            })
            ->rawColumns(['drag', 'icon', 'is_active', 'actions'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateData($request);
        $data['icon_path'] = $request->hasFile('icon') ? $request->file('icon')->store('offer', 'public') : null;

        OfferItem::create($data);

        return $this->ok('Stavka ponude je dodata.', null, 201);
    }

    public function update(Request $request, OfferItem $offerItem): JsonResponse
    {
        $data = $this->validateData($request);
        if ($request->hasFile('icon')) {
            $this->deleteIcon($offerItem->icon_path);
            $data['icon_path'] = $request->file('icon')->store('offer', 'public');
        }

        $offerItem->update($data);

        return $this->ok('Izmene su sačuvane.');
    }

    public function destroy(OfferItem $offerItem): JsonResponse
    {
        $this->deleteIcon($offerItem->icon_path);
        $offerItem->delete();

        return $this->ok('Stavka je obrisana.');
    }

    public function sort(Request $request): JsonResponse
    {
        foreach ($request->input('ids', []) as $position => $id) {
            OfferItem::whereKey($id)->update(['sort_order' => $position]);
        }

        return $this->ok('Redosled je sačuvan.');
    }

    private function validateData(Request $request): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        return $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'icon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:1024'],
        ], [], ['heading' => 'naslov', 'description' => 'opis']);
    }

    private function deleteIcon(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'img/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
