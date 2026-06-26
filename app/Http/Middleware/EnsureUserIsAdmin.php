<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Propušta samo ulogovane korisnike koji su admini (users.is_admin = true).
 * Registruje se kao alias 'admin' u bootstrap/app.php i kombinuje sa 'auth'
 * na admin grupi ruta.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403, 'Pristup dozvoljen samo administratorima.');
        }

        return $next($request);
    }
}
