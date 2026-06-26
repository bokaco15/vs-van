{{-- "Naša ponuda" vozila (third-section) --}}
<div class="section third-section" id="nasa-ponuda">
    <div class="container third-section-container">
        <div class="offer">
            <h2 class="offer-heading">Naša ponuda</h2>
        </div>

        @foreach ($vehicles as $vehicle)
            @include('public.partials.vehicle-card', ['vehicle' => $vehicle])
        @endforeach
    </div>
</div>

{{-- Deljeni kalendar (jedan za sve kartice) --}}
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
