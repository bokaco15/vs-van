{{-- Footer — veliki CTA + kontakt podaci iz sekcija --}}
@php
    $email = $s['contact_email'] ?? 'vstimtransport@gmail.com';
    $phone = $s['contact_phone'] ?? '+381652113423';
    $mailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to='.$email
        .'&su=Iznajmljivanje%20Vozila&body=Pozdrav,%20želim%20da%20iznajmim%20vozilo.';
@endphp
<footer class="footer" id="footer-section">
    <div class="container">
        <div class="footer__cta reveal">
            <h2>Spreman<br>za <span class="gradient-text">put?</span></h2>
            <div class="footer__actions">
                <a href="tel:{{ $phone }}" class="btn btn--primary">
                    @include('public.partials.icon', ['name' => 'phone', 'size' => 18]) Pozovi nas
                </a>
                <a href="{{ $mailUrl }}" target="_blank" rel="noopener" class="btn btn--ghost">
                    Pošalji upit @include('public.partials.icon', ['name' => 'arrow-right', 'size' => 18])
                </a>
            </div>
        </div>

        <div class="footer__cols">
            <div class="footer__brand">
                <img src="{{ asset('img/vs_logo.png') }}" alt="VS Tim logo">
                <p>Iznajmljivanje automobila i kombija — putničkih i teretnih. Pouzdano, kasko osigurano i dostupno 24/7.</p>
            </div>

            <div class="footer__col">
                <h4>Radno vreme</h4>
                <p>{{ $s['contact_hours_days'] ?? 'Ponedeljak — Subota' }}</p>
                <p>{{ $s['contact_hours_time'] ?? '08:00 — 20:00' }}</p>
                <h4 style="margin-top:22px">Adresa</h4>
                <a href="{{ $s['contact_address_url'] ?? '#' }}" target="_blank" rel="noopener">@include('public.partials.icon', ['name' => 'pin', 'size' => 17]){{ $s['contact_address'] ?? 'Beograd' }}</a>
            </div>

            <div class="footer__col">
                <h4>Kontakt</h4>
                <a href="tel:{{ $phone }}">@include('public.partials.icon', ['name' => 'phone', 'size' => 17]){{ $phone }}</a>
                <a href="{{ $mailUrl }}" target="_blank" rel="noopener">@include('public.partials.icon', ['name' => 'mail', 'size' => 17]){{ $email }}</a>
                <a href="{{ $s['contact_instagram_url'] ?? '#' }}" target="_blank" rel="noopener">@include('public.partials.icon', ['name' => 'instagram', 'size' => 17]){{ $s['contact_instagram'] ?? '@vstim' }}</a>
            </div>
        </div>

        <div class="footer__bottom">
            <span>VS TIM · © {{ date('Y') }} Sva prava zadržana</span>
            <span>Iznajmljivanje vozila · Beograd</span>
        </div>
    </div>
</footer>
