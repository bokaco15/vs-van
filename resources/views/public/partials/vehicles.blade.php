{{-- Flota — naslov, segmentni filter (Sva / Kombiji / Auta) i grid kartica.
     Filtriranje je client-side (home.js) preko data-type na kartici. --}}
@php
    $countAll = $vehicles->count();
    $countVan = $vehicles->where('type', 'van')->count();
    $countCar = $vehicles->where('type', 'car')->count();
@endphp
<section class="fleet" id="flota">
    <div class="container">
        <div class="fleet__head">
            <div class="reveal">
                <span class="eyebrow">Naša ponuda</span>
                <h2 class="fleet__title">Izaberi svoje<br><span class="amber">vozilo</span></h2>
            </div>

            <div class="filter reveal" data-delay="1" role="tablist" aria-label="Filter vozila">
                <span class="filter__indicator" aria-hidden="true"></span>
                <button class="filter__btn is-active" data-filter="all" type="button">
                    Sva vozila <span class="count">{{ $countAll }}</span>
                </button>
                <button class="filter__btn" data-filter="van" type="button">
                    Kombiji <span class="count">{{ $countVan }}</span>
                </button>
                <button class="filter__btn" data-filter="car" type="button">
                    Auta <span class="count">{{ $countCar }}</span>
                </button>
            </div>
        </div>

        <div class="fleet__grid">
            @foreach ($vehicles as $vehicle)
                @include('public.partials.vehicle-card', ['vehicle' => $vehicle])
            @endforeach

            {{-- Prazno stanje (npr. kad se izabere kategorija bez vozila) --}}
            <div class="fleet__empty">
                <h3>Uskoro</h3>
                <p>Trenutno nemamo vozila u ovoj kategoriji. Rent-a-car ponuda stiže — javi nam se za detalje.</p>
            </div>
        </div>
    </div>
</section>

{{-- Deljeni kalendar (jedan za sve kartice) — reservation.js ga premešta --}}
@include('public.partials.calendar')

{{-- Galerija slika vozila (GLightbox) --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.GLightbox) {
                GLightbox({ selector: '.glightbox' });
            }
        });
    </script>
@endpush
