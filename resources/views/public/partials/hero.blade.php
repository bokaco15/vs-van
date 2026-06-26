{{-- Hero sekcija --}}
<div class="section hero-section">
    <div class="container hero-container">
        <div class="first-hero-column">
            <div class="hero-heading">
                <h1 class="lato">{{ $s['hero_title_before'] ?? 'Vaša' }}
                    <span class="lato udobnost">{{ $s['hero_title_highlight'] ?? 'udobnost' }}</span>{{ $s['hero_title_after'] ?? ', naš prioritet' }}</h1>
            </div>
            <div class="hero-paragraph">
                <p>{{ $s['hero_subtitle'] ?? '' }}</p>
            </div>
            <div class="hero-btns">
                <button class="iznajmi"><a href="tel:{{ $s['contact_phone'] ?? '' }}"><img src="{{ asset('img/Call - Calling.svg') }}" alt="Iznajmi odmah vozilo"> Iznajmi odmah</a></button>
                <button class="ponuda"><a href="#nasa-ponuda">Pogledaj ponudu</a></button>
            </div>
        </div>

        <div class="second-hero-column">
            <img src="{{ asset('img/hero-section-img.png') }}" alt="Hero-Van">
        </div>

        <img src="{{ asset('img/circle.png') }}" alt="" id="circle">
        <img src="{{ asset('img/circle2.png') }}" alt="" id="circle2">
    </div>
</div>
