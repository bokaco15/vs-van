<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VS Tim - Iznajmi kombi')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">

    {{-- Font Awesome (koristi se za FAQ strelice itd.) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Fontovi (Fontshare): Clash Display (display/naslovi) + Satoshi (telo) --}}
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,500,600,700&f[]=satoshi@400,500,700,900&display=swap" rel="stylesheet">

    {{-- GLightbox (galerija slika vozila) --}}
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

    {{-- GSAP + ScrollTrigger (kinematografske scroll animacije, GTA-stil).
         Učitava se pre Vite ES modula da home.js ima globalni `gsap`. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    {{-- Redizajnirana početna (home.css/home.js) + kalendar (reservation.js) --}}
    @vite(['resources/css/home.css', 'resources/js/home.js', 'resources/js/reservation.js'])

    @stack('styles')
</head>
<body class="gsap-armed">
    {{-- Bez JS-a (ili ako GSAP ne učita) hero elementi moraju biti vidljivi --}}
    <noscript><style>.gsap-armed [data-hero]{opacity:1 !important}.reveal{opacity:1 !important;transform:none !important}</style></noscript>

    {{-- Globalna JS konfiguracija (npr. WhatsApp broj iz sekcija) --}}
    <script>
        window.APP_CONFIG = {
            whatsappPhone: @json($whatsappPhone ?? '+381652113423'),
            reservationsUrlTemplate: @json(url('/api/vehicles/:id/reservations')),
        };
    </script>

    @yield('content')

    @stack('scripts')
</body>
</html>
