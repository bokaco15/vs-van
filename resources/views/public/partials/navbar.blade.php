{{-- Navbar (desktop) --}}
<div class="nav">
    <div class="nav-container">
        <a href="{{ url('/') }}">
            <div class="logo">
                <img src="{{ asset('img/vs_logo.png') }}" alt="logo">
            </div>
        </a>
        <div class="links">
            <p><a href="#pocenta">Pocetna</a></p>
            <p><a href="#nasa-ponuda">Nasa ponuda</a></p>
            <p><a href="#faq-section">Faq</a></p>
            <p><a href="#footer-section">Kontakt</a></p>
        </div>
    </div>
</div>

{{-- Responsive Navbar --}}
<div class="responsive-nav">
    <div class="container navbar-container">
        <div class="logo responsive-logo">
            <img src="{{ asset('img/vs_logo.png') }}" alt="logo">
        </div>
        <div class="open-nav">
            <img src="{{ asset('img/nav-icon.svg') }}" alt="open menu">
        </div>
    </div>
    <div class="nav-items-wrapper">
        <div class="nav-items">
            <a href="#">Pocetna</a>
            <a href="#">Nasa ponuda</a>
            <a href="#">Faq</a>
            <a href="#">Kontakt</a>
            <div class="nav-info-wrapper nav-info-wrapper-menu">
                @include('public.partials.nav-info-items')
            </div>
            <div class="close-nav">
                <img src="{{ asset('img/nav-close.svg') }}" alt="close menu">
            </div>
        </div>
    </div>
</div>

{{-- Nav Info traka --}}
<div class="nav-info">
    <div class="container">
        <div class="nav-info-wrapper">
            @include('public.partials.nav-info-items')
        </div>
    </div>
</div>
