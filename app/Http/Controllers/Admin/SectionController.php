<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SectionController extends Controller
{
    use ApiResponse;

    public function index(): View
    {
        // Grupisano radi preglednog prikaza u adminu (hero, kontakt...).
        return view('admin.sections.index', [
            'groups' => Section::orderBy('group')->get()->groupBy('group'),
        ]);
    }

    /** Bulk izmena: { sections: { key: value, ... } } */
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*' => ['nullable', 'string'],
        ]);

        foreach ($data['sections'] as $key => $value) {
            Section::where('key', $key)->update(['value' => $value]);
        }

        // Mass update ne okida model evente — ručno očisti keš sekcija.
        Cache::forget('sections.map');

        return $this->ok('Tekst je sačuvan.');
    }
}
