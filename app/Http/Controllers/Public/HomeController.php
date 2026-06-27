<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\OfferItem;
use App\Models\Section;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Javna početna strana — povlači sav sadržaj iz baze.
     */
    public function index(Request $request): View
    {
        // Sva aktivna vozila — filter (tip), pretraga i paginacija su client-side
        // (home.js) tako da promena kategorije/pretrage ne osvežava stranicu.
        $vehicles = Vehicle::active()
            ->with(['coverImage', 'images', 'features'])
            ->orderBy('sort_order')
            ->get();

        // Mapa sekcija (key => value) za tekstualni sadržaj.
        $s = Section::query()->pluck('value', 'key');

        return view('public.home', [
            'vehicles'   => $vehicles,
            'countAll'   => $vehicles->count(),
            'countVan'   => $vehicles->where('type', 'van')->count(),
            'countCar'   => $vehicles->where('type', 'car')->count(),
            'offerItems' => OfferItem::active()->get(),
            'faqs' => Faq::active()->get(),
            's' => $s,
            // Prosleđeno layout-u za window.APP_CONFIG (WhatsApp).
            'whatsappPhone' => $s['contact_phone'] ?? '+381652113423',
        ]);
    }
}
