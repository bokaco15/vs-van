<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\OfferItemController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ReservationApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Javne rute
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// JSON: zauzeti dani vozila (koristi kalendar na javnom sajtu).
Route::get('/api/vehicles/{vehicle}/reservations', [ReservationApiController::class, 'index'])
    ->name('api.vehicles.reservations');

/*
|--------------------------------------------------------------------------
| Admin rute (samo ulogovan admin: auth + admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ---- Vozila ----
        Route::get('vehicles/data', [VehicleController::class, 'data'])->name('vehicles.data');
        Route::post('vehicles/sort', [VehicleController::class, 'sort'])->name('vehicles.sort');
        Route::resource('vehicles', VehicleController::class)->except(['show']);

        // ---- Slike vozila (ugnježdeno) ----
        Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('vehicles.images.store');
        Route::post('vehicles/{vehicle}/images/sort', [VehicleImageController::class, 'sort'])->name('vehicles.images.sort');
        Route::put('images/{image}/cover', [VehicleImageController::class, 'cover'])->name('images.cover');
        Route::delete('images/{image}', [VehicleImageController::class, 'destroy'])->name('images.destroy');

        // ---- Specifikacije ----
        Route::get('features/data', [FeatureController::class, 'data'])->name('features.data');
        Route::resource('features', FeatureController::class)->only(['index', 'store', 'update', 'destroy']);

        // ---- Rezervacije ----
        Route::get('reservations/data', [ReservationController::class, 'data'])->name('reservations.data');
        Route::resource('reservations', ReservationController::class)->only(['index', 'store', 'destroy']);

        // ---- Naša ponuda ----
        Route::get('offer-items/data', [OfferItemController::class, 'data'])->name('offer-items.data');
        Route::post('offer-items/sort', [OfferItemController::class, 'sort'])->name('offer-items.sort');
        Route::resource('offer-items', OfferItemController::class)->only(['index', 'store', 'update', 'destroy']);

        // ---- FAQ ----
        Route::get('faqs/data', [FaqController::class, 'data'])->name('faqs.data');
        Route::post('faqs/sort', [FaqController::class, 'sort'])->name('faqs.sort');
        Route::resource('faqs', FaqController::class)->only(['index', 'store', 'update', 'destroy']);

        // ---- Sekcije / Tekst ----
        Route::get('sections', [SectionController::class, 'index'])->name('sections.index');
        Route::put('sections', [SectionController::class, 'update'])->name('sections.update');
    });

require __DIR__.'/auth.php';
