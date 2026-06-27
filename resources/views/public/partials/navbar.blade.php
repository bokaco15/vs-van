{{-- Header / navigacija — sticky, blur, amber CTA --}}
@php $phone = $s['contact_phone'] ?? '+381652113423'; @endphp
<header class="site-header" id="pocetna">
    <div class="container nav">
        <a href="{{ url('/') }}" class="nav__logo" aria-label="VS Tim početna">
            <img src="{{ asset('img/vs_logo.png') }}" alt="VS Tim logo">
        </a>

        <nav class="nav__links" aria-label="Glavna navigacija">
            <a href="#pocetna">Početna</a>
            <a href="#flota">Naša ponuda</a>
            <a href="#faq-section">Pitanja</a>
            <a href="#footer-section">Kontakt</a>
        </nav>

        <div class="nav__cta">
            <a href="tel:{{ $phone }}" class="nav__phone">
                @include('public.partials.icon', ['name' => 'phone', 'size' => 16]) {{ $phone }}
            </a>
            <a href="#flota" class="btn btn--accent">Iznajmi vozilo</a>
            <button class="nav-toggle" aria-label="Otvori meni" aria-controls="mobile-menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- Mobilni fullscreen meni --}}
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu__close" aria-label="Zatvori meni">&times;</button>
    <a href="#pocetna">Po<span>č</span>etna</a>
    <a href="#flota">Naša <span>ponuda</span></a>
    <a href="#faq-section">Pi<span>tanja</span></a>
    <a href="#footer-section">Kon<span>takt</span></a>
    <div class="mobile-menu__contact">
        <span>{{ $phone }}</span>
        <span>{{ $s['contact_email'] ?? 'vstimtransport@gmail.com' }}</span>
    </div>
</div>
