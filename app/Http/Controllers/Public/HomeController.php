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
        // Opcioni filter po tipu (van/car) — priprema za rent-a-car.
        $type = $request->query('type');

        $vehicles = Vehicle::active()
            ->ofType($type)
            ->with(['coverImage', 'images', 'features'])
            ->get();

        // Mapa sekcija (key => value) za tekstualni sadržaj.
        $s = Section::query()->pluck('value', 'key');

        return view('public.home', [
            'vehicles' => $vehicles,
            'offerItems' => OfferItem::active()->get(),
            'faqs' => Faq::active()->get(),
            's' => $s,
            // Prosleđeno layout-u za window.APP_CONFIG (WhatsApp).
            'whatsappPhone' => $s['contact_phone'] ?? '+381652113423',
        ]);
    }
}
