{{-- "Naša ponuda" (second-section) — akordeon stavke iz baze (offer_items) --}}
<div class="section second-section">
    <div class="container">
        <div class="columns">
            <div class="first-column">
                <div class="first-col-img">
                    <img src="{{ asset('img/hero-section-img-2.png') }}" alt="Putnicki kombi">
                </div>
            </div>
            <div class="second-column">
                <div class="second-col-wrapper">
                    @foreach ($offerItems as $item)
                        <div class="item">
                            <div class="item-wrapper">
                                <div class="item-wrapper1">
                                    <div class="item-logo">
                                        <img src="{{ $item->icon_url ?? asset('img/item-perfect.svg') }}" alt="{{ $item->heading }}">
                                    </div>
                                    <div class="flex-wrapper-row">
                                        <div class="flex-wrapper">
                                            <p class="ss-item-heading">{{ $item->heading }}</p>
                                            <svg class="x" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 7H13M7 1L7 13" stroke="#455A64" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="item-wrapper2">
                                            <p class="item-description">{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
