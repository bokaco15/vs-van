{{-- "Zašto VS Tim" — editorial split: moćna slika + numerisani akordeon (offer_items iz baze) --}}
<section class="offer">
    {{-- ambijentalni sunset sjaj u pozadini sekcije --}}
    <span class="offer__glow" aria-hidden="true"></span>

    <div class="container offer__grid">
        {{-- ===== Media kolona: portret kadar sa halo sjajem i staklenom karticom ===== --}}
        <div class="offer__media reveal">
            <span class="offer__halo" aria-hidden="true"></span>

            <div class="offer__frame">
                <img src="{{ asset('img/photos/offer-car.jpg') }}" alt="Automobil za iznajmljivanje u zlatnoj svetlosti zalaska sunca">
                <span class="offer__overlay" aria-hidden="true"></span>

                {{-- plutajuća staklena kartica — obećanje brenda (bez izmišljenih brojki) --}}
                <div class="offer__badge">
                    <span class="offer__badge-ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3l7 3v5c0 4.5-3 7.6-7 9-4-1.4-7-4.5-7-9V6z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </span>
                    <span class="offer__badge-text">
                        <strong>Bez skrivenih troškova</strong>
                        <small>Cena koju vidiš je cena koju plaćaš</small>
                    </span>
                </div>
            </div>
        </div>

        {{-- ===== Sadržaj: naslov + uvod + numerisani akordeon ===== --}}
        <div class="offer__content reveal" data-delay="1">
            <div class="offer__head">
                <span class="eyebrow">Zašto baš mi</span>
                <h2 class="offer__title">Iznajmljivanje<br>bez <span class="gradient-text">kompromisa</span></h2>
                <p class="offer__lead">
                    Bez sitnih slova i skrivenih uslova — od preuzimanja do povratka tačno znaš
                    šta dobijaš. Sve ostalo prepusti nama.
                </p>
            </div>

            <div class="acc">
                @foreach ($offerItems as $item)
                    <div class="acc__item {{ $loop->first ? 'is-open' : '' }}">
                        <button class="acc__head" type="button">
                            <span class="acc__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="acc__title">{{ $item->heading }}</span>
                            <span class="acc__plus" aria-hidden="true"></span>
                        </button>
                        <div class="acc__body" @if($loop->first) style="max-height: 320px" @endif>
                            <p>{{ $item->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
