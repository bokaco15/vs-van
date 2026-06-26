<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Kontrolna tabla admin panela — kratak pregled stanja.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'vehiclesCount' => Vehicle::count(),
            'reservationsCount' => Reservation::count(),
            'featuresCount' => Feature::count(),
            'faqsCount' => Faq::count(),
        ]);
    }
}
