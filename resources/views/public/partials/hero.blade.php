{{-- Hero — full-bleed kinematografska pozadina (autostrada/sumrak) + tamni
     overlay za čitljivost. Tekst levo, statistika i scroll-cue dole.
     GSAP (home.js) animira [data-hero] elemente + suptilan parallax pozadine. --}}
@php $phone = $s['contact_phone'] ?? '+381652113423'; @endphp
<section class="hero" id="pocetna">
    <div class="hero__bg" aria-hidden="true" style="background-image: url('{{ asset('img/photos/hero-bg.jpg') }}')"></div>
    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="container hero__inner">
        <span class="eyebrow" data-hero="eyebrow">VS Tim · Rent a car &amp; rent a van</span>

        <h1 class="hero__title">
            <span class="line" data-hero="line">Vozi</span>
            <span class="line gradient-text" data-hero="line">bez granica</span>
        </h1>

        <p class="hero__lead" data-hero="lead">
            {{ $s['hero_subtitle'] ?? 'Kombiji i automobili nove generacije za svaki povod — selidbe, putovanja, posao ili vikend bez plana. Izaberi vozilo, proveri slobodne termine i kreni.' }}
        </p>

        <div class="hero__actions" data-hero="actions">
            <a href="tel:{{ $phone }}" class="btn btn--primary">
                @include('public.partials.icon', ['name' => 'phone', 'size' => 18]) Iznajmi odmah
            </a>
            <a href="#flota" class="btn btn--ghost">
                Pogledaj flotu @include('public.partials.icon', ['name' => 'arrow-right', 'size' => 18])
            </a>
        </div>

        <div class="hero__stats" data-hero="stats">
            <div class="hero__stat">
                <div class="num">100<span>%</span></div>
                <div class="label">Kasko osigurano</div>
            </div>
            <div class="hero__stat">
                <div class="num">24<span>/7</span></div>
                <div class="label">Podrška na putu</div>
            </div>
            <div class="hero__stat">
                <div class="num">0<span>€</span></div>
                <div class="label">Skrivenih troškova</div>
            </div>
        </div>
    </div>

    <a href="#flota" class="hero__scroll" data-hero="scroll" aria-label="Skroluj na ponudu">
        <span>Skroluj</span>
        <span class="hero__scroll-line"></span>
    </a>
</section>
