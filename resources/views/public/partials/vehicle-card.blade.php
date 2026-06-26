{{-- Kartica jednog vozila. $vehicle dolazi iz petlje u vehicles.blade.php --}}
@php
    $cover = $vehicle->coverImage;
    $coverUrl = $cover->url ?? asset('img/teretni-van.png');
    $gallery = 'vehicle-'.$vehicle->id;
@endphp
<div class="van-item" data-van="{{ $vehicle->id }}">
    <div class="van-item-photo">
        {{-- Naslovna slika otvara galeriju (GLightbox). reservation.js i dalje
             pronalazi <img> unutar linka i prebacuje prikaz kalendara. --}}
        <a href="{{ $coverUrl }}" class="glightbox" data-gallery="{{ $gallery }}" style="display:block">
            <img src="{{ $coverUrl }}" alt="{{ $vehicle->name }}">
        </a>
        {{-- Ostale slike (skrivene) — ulaze u istu galeriju --}}
        @foreach ($vehicle->images as $img)
            @continue($cover && $img->id === $cover->id)
            <a href="{{ $img->url }}" class="glightbox" data-gallery="{{ $gallery }}" style="display:none"></a>
        @endforeach
    </div>

    <div class="van-item-description">
        <div class="wrapper-description">
            <div class="wd-heading">
                <h4 class="van-name">{{ $vehicle->name }}</h4>
                <p>{{ $vehicle->subtitle }}</p>
            </div>
            <div class="wd-stickers">
                @foreach ($vehicle->features as $feature)
                    <div class="wd-sticker">
                        <img src="{{ $feature->icon_url ?? asset('img/manual-sticker.png') }}" alt="{{ $feature->name }}">
                        <p>{{ $feature->pivot->value ?: $feature->name }}</p>
                    </div>
                @endforeach
            </div>

            <div class="wd-paragraph">
                <p>{{ $vehicle->description }}</p>
            </div>

            <div class="van-btns">
                <button class="iznajmi-kombi"><a href="#"><img src="{{ asset('img/Call - Calling.svg') }}" alt=""> Iznajmi odmah</a></button>
                <button class="termini"><img src="{{ asset('img/Calender.svg') }}" alt="">Pogledaj termine</button>
            </div>
        </div>
    </div>
</div>
