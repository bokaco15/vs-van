{{-- Kartica jednog vozila. $vehicle dolazi iz petlje u vehicles.blade.php.
     Klase .van-item / data-van / .van-item-photo / h4.van-name / .termini /
     .iznajmi-kombi su zadržane jer ih reservation.js koristi za kalendar. --}}
@php
    $cover = $vehicle->coverImage;
    $coverUrl = $cover->url ?? asset('img/photos/cargo.jpg');
    $gallery = 'vehicle-'.$vehicle->id;
    $typeLabel = $vehicle->type === 'car' ? 'Automobil' : 'Kombi';
    $phone = $s['contact_phone'] ?? '+381652113423';
@endphp
<article class="van-item" data-van="{{ $vehicle->id }}" data-type="{{ $vehicle->type }}">
    <div class="van-item-photo">
        @if ($vehicle->is_recommended)
            <span class="van-type-tag van-type-tag--reco">★ Preporuka</span>
        @else
            <span class="van-type-tag">{{ $typeLabel }}</span>
        @endif

        {{-- Naslovna slika otvara galeriju (GLightbox); reservation.js pronalazi
             ovaj <img> i sakriva ga kad prikazuje kalendar. --}}
        <a href="{{ $coverUrl }}" class="glightbox" data-gallery="{{ $gallery }}">
            <img src="{{ $coverUrl }}" alt="{{ $vehicle->name }}">
        </a>
        @foreach ($vehicle->images as $img)
            @continue($cover && $img->id === $cover->id)
            <a href="{{ $img->url }}" class="glightbox" data-gallery="{{ $gallery }}" style="display:none"></a>
        @endforeach
    </div>

    <div class="van-item-description">
        <div class="wd-heading">
            <h4 class="van-name">{{ $vehicle->name }}</h4>
            <p>{{ $vehicle->subtitle }}</p>
        </div>

        @if ($vehicle->features->isNotEmpty())
            <div class="wd-stickers">
                @foreach ($vehicle->features as $feature)
                    <span class="wd-sticker">
                        <img src="{{ $feature->icon_url ?? asset('img/manual-sticker.png') }}" alt="">
                        {{ $feature->pivot->value ?: $feature->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="wd-paragraph">
            <p>{{ $vehicle->description }}</p>
        </div>

        <div class="van-btns">
            <button class="iznajmi-kombi" type="button">
                <a href="tel:{{ $phone }}">@include('public.partials.icon', ['name' => 'phone', 'size' => 17]) Iznajmi odmah</a>
            </button>
            <button class="termini" type="button">
                @include('public.partials.icon', ['name' => 'calendar', 'size' => 17]) Termini
            </button>
        </div>
    </div>
</article>
