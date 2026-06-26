<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        return view('admin.faqs.index');
    }

    public function data(): JsonResponse
    {
        return DataTables::eloquent(Faq::query()->orderBy('sort_order'))
            ->setRowAttr(['data-id' => fn (Faq $f) => $f->id])
            ->addColumn('drag', fn () => '<i class="bi bi-grip-vertical js-drag text-muted" style="cursor:grab"></i>')
            ->editColumn('is_active', fn (Faq $f) => $f->is_active
                ? '<span class="badge text-bg-success">Da</span>'
                : '<span class="badge text-bg-secondary">Ne</span>')
            ->addColumn('actions', function (Faq $f) {
                return '<div class="btn-group btn-group-sm">'
                    .'<button type="button" class="btn btn-outline-primary js-edit" '
                    .'data-id="'.$f->id.'" data-question="'.e($f->question).'" data-answer="'.e($f->answer).'" '
                    .'data-sort_order="'.$f->sort_order.'" data-is_active="'.(int) $f->is_active.'"><i class="bi bi-pencil"></i></button>'
                    .'<button type="button" class="btn btn-outline-danger js-delete" data-url="'.route('admin.faqs.destroy', $f).'"><i class="bi bi-trash"></i></button>'
                    .'</div>';
            })
            ->rawColumns(['drag', 'is_active', 'actions'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        Faq::create($this->validateData($request));

        return $this->ok('Pitanje je dodato.', null, 201);
    }

    public function update(Request $request, Faq $faq): JsonResponse
    {
        $faq->update($this->validateData($request));

        return $this->ok('Izmene su sačuvane.');
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();

        return $this->ok('Pitanje je obrisano.');
    }

    public function sort(Request $request): JsonResponse
    {
        foreach ($request->input('ids', []) as $position => $id) {
            Faq::whereKey($id)->update(['sort_order' => $position]);
        }

        return $this->ok('Redosled je sačuvan.');
    }

    private function validateData(Request $request): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ], [], ['question' => 'pitanje', 'answer' => 'odgovor']);
    }
}
