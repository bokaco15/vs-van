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

    {{-- Google fontovi: Lato (postojeći) + Montserrat i Anton (korišćeni u CSS-u) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:wght@400;500;600;700&family=Anton&display=swap" rel="stylesheet">

    {{-- jQuery (script.js ga koristi) — običan script, izvršava se pre Vite ES modula --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- GLightbox (galerija slika vozila) --}}
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

    {{-- Postojeći dizajn + skripte (kroz Vite) --}}
    @vite(['resources/css/style.css', 'resources/js/script.js', 'resources/js/reservation.js'])

    @stack('styles')
</head>
<body>
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
