<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF token za sve AJAX zahteve (čita ga admin.js u $.ajaxSetup) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — VS Tim</title>

    {{-- ===== CSS biblioteke (CDN) ===== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- DataTables + Toastr (Bootstrap 5 tema) --}}
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">

    {{-- Naša dopuna stilizacije + admin.css kroz Vite --}}
    @vite(['resources/css/admin.css'])

    {{-- Stranice mogu dodati svoj CSS --}}
    @stack('styles')
</head>
<body>
<div class="admin-wrapper d-flex">
    {{-- Bočni meni --}}
    @include('admin.partials.sidebar')

    <div class="admin-main flex-grow-1 d-flex flex-column min-vh-100">
        {{-- Gornja traka --}}
        @include('admin.partials.topbar')

        <main class="admin-content p-4 flex-grow-1">
            {{-- Naslov stranice + opcione akcije (dugmad) --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">@yield('page-title', View::yieldContent('title'))</h1>
                <div>@yield('page-actions')</div>
            </div>

            @yield('content')
        </main>
    </div>
</div>

{{-- ===== JS biblioteke (CDN) ===== --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

{{-- Zajednička admin logika (CSRF za AJAX, Toastr helperi) --}}
@vite(['resources/js/admin.js'])

{{-- Stranice mogu dodati svoj JS --}}
@stack('scripts')
</body>
</html>
