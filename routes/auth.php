<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
| Auth rute (Breeze backend, svedeno na admin login).
| Registracija, reset lozinke i verifikacija e-pošte su namerno uklonjeni —
| jedini nalog je admin koji se seeduje (vidi AdminUserSeeder).
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
