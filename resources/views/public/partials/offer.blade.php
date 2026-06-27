{{-- "Zašto VS Tim" — akordeon stavke iz baze (offer_items) --}}
<section class="offer">
    <div class="container offer__grid">
        <div class="offer__media reveal">
            <img src="{{ asset('img/photos/offer.jpg') }}" alt="Renault Trafic VS Tim na otvorenom putu">
        </div>

        <div class="offer__content reveal" data-delay="1">
            <div class="offer__head">
                <span class="eyebrow">Zašto baš mi</span>
                <h2 class="offer__title">Iznajmljivanje<br>bez kompromisa</h2>
            </div>

            <div class="acc">
                @foreach ($offerItems as $item)
                    <div class="acc__item {{ $loop->first ? 'is-open' : '' }}">
                        <button class="acc__head" type="button">
                            <span class="acc__icon">
                                <img src="{{ $item->icon_url ?? asset('img/item-perfect.svg') }}" alt="">
                            </span>
                            <span class="acc__title">{{ $item->heading }}</span>
                            <span class="acc__plus" aria-hidden="true"></span>
                        </button>
                        <div class="acc__body" @if($loop->first) style="max-height: 240px" @endif>
                            <p>{{ $item->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
