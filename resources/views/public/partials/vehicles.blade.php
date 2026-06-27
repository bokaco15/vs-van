{{-- Flota — naslov, pretraga, segmentni filter (Sva / Kombiji / Auta) i grid.
     Filter, pretraga i paginacija (9 po strani) su client-side u home.js —
     promena kategorije/pretrage NE osvežava stranicu. --}}
<section class="fleet" id="flota"
         data-fleet
         data-per-page="9"
         data-count-all="{{ $countAll }}"
         data-count-van="{{ $countVan }}"
         data-count-car="{{ $countCar }}">
    <div class="container">
        <div class="fleet__head">
            <div class="reveal">
                <span class="eyebrow">Naša ponuda</span>
                <h2 class="fleet__title">Izaberi svoje<br><span class="amber">vozilo</span></h2>
            </div>

            <div class="fleet__controls reveal" data-delay="1">
                <div class="fleet__search">
                    @include('public.partials.icon', ['name' => 'search', 'size' => 18])
                    <input type="search"
                           class="fleet__search-input"
                           data-fleet-search
                           placeholder="Pretraži po nazivu…"
                           aria-label="Pretraži vozila po nazivu"
                           autocomplete="off">
                </div>

                <div class="filter" role="tablist" aria-label="Filter vozila">
                    <span class="filter__indicator" aria-hidden="true"></span>
                    <button class="filter__btn is-active" data-filter="all" type="button">
                        Sva <span class="count">{{ $countAll }}</span>
                    </button>
                    <button class="filter__btn" data-filter="van" type="button">
                        Kombiji <span class="count">{{ $countVan }}</span>
                    </button>
                    <button class="filter__btn" data-filter="car" type="button">
                        Auta <span class="count">{{ $countCar }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="fleet__grid" data-fleet-grid>
            @foreach ($vehicles as $vehicle)
                @include('public.partials.vehicle-card', ['vehicle' => $vehicle])
            @endforeach
        </div>

        {{-- Prazno stanje (kad nema rezultata za izabran filter/pretragu) --}}
        <div class="fleet__empty" data-fleet-empty>
            <h3>Nema rezultata</h3>
            <p>Ne nalazimo vozilo za izabrane kriterijume. Probaj drugu kategoriju ili pretragu.</p>
        </div>

        {{-- Paginaciju popunjava home.js (po 9 vozila) --}}
        <nav class="fleet-pagination" data-fleet-pagination aria-label="Stranice flote"></nav>
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
