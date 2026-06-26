# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project state & intent

A **Laravel 13 app** (PHP 8.3, **MySQL**, Vite 8 — Tailwind was removed) that ports a previously static van-rental site into Blade and adds a hand-written admin panel. Business: VS Tim, van/vehicle rental ("rent a vehicle" platform — `vehicles.type` is `van`/`car` so a rent-a-car offering can be added later).

Two source artifacts:
1. `vsvan/` — the **original static site** (own nested git repo): `index.html`, `style.css`, `script.js`, `reservation.js`, `reservations.json`, `img/`. Kept as the reference; its CSS/JS were copied into `resources/` and must stay **visually identical** on the public site.
2. `TASK.md` (Serbian) — the original spec. Still useful for intent, but the app is now built; this file reflects what exists.

> The user is doing this to **learn** and communicates in **Serbian** — respond in Serbian, prefer hand-written, well-commented code that explains *why*. No admin scaffolding generators (Filament/Nova excluded). Layouts use **Blade inheritance** (`@extends`/`@section`/`@yield`/`@include`), not `<x-...>` components.

## Commands

```bash
composer dev          # server + queue + pail logs + vite concurrently (main dev loop)
php artisan test                                   # full suite (uses in-memory SQLite)
php artisan test --filter=ReservationApiTest       # single test
./vendor/bin/pint     # format PHP (Laravel Pint) — run before finishing
php artisan migrate:fresh --seed                   # rebuild MySQL DB + seed demo data
npm run build         # build Vite assets (public site + admin)
```

- DB is **MySQL** (`vsvan_update`, root/no-password via WAMP). Tests run on in-memory SQLite (`phpunit.xml`).
- Admin login (seeded): `admin@vstim.rs` / `password`. Public registration is removed.
- After pulling: `php artisan migrate:fresh --seed && npm run build && php artisan storage:link`.

## Architecture

**Public side** (`App\Http\Controllers\Public\`):
- `HomeController@index` renders `resources/views/public/home.blade.php`, which `@include`s partials in `public/partials/` (navbar, hero, stickers, offer, vehicles, vehicle-card, calendar, faq, footer). Layout: `layouts/public.blade.php` (loads jQuery + GLightbox CDN, `@vite` of `style.css`/`script.js`/`reservation.js`, injects `window.APP_CONFIG`).
- `ReservationApiController@index` → `GET /api/vehicles/{vehicle}/reservations` returns busy days grouped `{ "YYYY-MM": [days] }`. `resources/js/reservation.js` fetches this per-vehicle on "Pogledaj termine" and keeps the original calendar + WhatsApp behavior.

**Admin side** (`App\Http\Controllers\Admin\`, routes under `/admin`, `auth`+`admin` middleware): hand-written CRUD for vehicles (+ nested images, drag-sort, features), reservations, features, offer-items, faqs, sections. Layout `layouts/admin.blade.php` is **Bootstrap 5 via CDN** plus jQuery, **Yajra DataTables** (server-side `data()` methods), jQuery Validation, **Toastr**, **SortableJS**, Bootstrap Icons; shared JS in `resources/js/admin.js` (`window.Admin` helpers: `submitForm`, `handleAjaxError`, `initSortableTable`, `.js-delete`).

**Key cross-cutting pieces:**
- `App\Support\ApiResponse` (trait) — uniform `{status,message,data}` JSON; admin AJAX maps 2xx→`toastr.success`, 422→per-field errors, 5xx→`toastr.error`. **`bootstrap/app.php`** widens `shouldRenderJsonWhen` to `expectsJson()` so admin AJAX gets 422 JSON, not redirects.
- `App\Services\ImageService` — Intervention Image **v4.1** (note its API: `decode()` + `encode(new WebpEncoder(quality))`, *not* `read()`/`toWebp()`). Stores uploads as **WebP** (full + thumb) on the `public` disk.
- `App\Support\Media::url($path)` — media URL resolver: paths starting `img/` → `asset()` (static/seeded assets in `public/img`), else `Storage::url()` (uploads). Used by `VehicleImage`, `Feature`, `OfferItem` URL accessors.
- Models: `Vehicle` (hasMany images/reservations, belongsToMany features with pivot `value`, `coverImage()`, `scopeActive`/`scopeOfType`), `VehicleImage`, `Feature`, `Reservation` (`date` cast), `Section` (cached key→value via `Section::get()`; mass-update flushes `sections.map` cache manually), `Faq`, `OfferItem`.

**Auth:** Breeze backend kept but trimmed to login/logout only (registration, password reset, email verification, profile removed); login view re-styled in Bootstrap; `EnsureUserIsAdmin` middleware (alias `admin`) checks `users.is_admin`.

## Preserve behavior on the public site

`style.css`/`script.js` are copied verbatim into `resources/` and served via Vite — do not change their behavior. `script.js` uses jQuery (loaded as a regular CDN script before the Vite ES modules). Vehicle cards carry `data-van="{{ $vehicle->id }}"` (the calendar keys off this). Seeded media references `img/...` (existing `public/img` assets); admin uploads go to `storage/app/public/...`.
