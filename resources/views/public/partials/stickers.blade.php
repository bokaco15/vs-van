{{-- Trust / kategorije ponude (kombiji + automobili) — moderne SVG ikonice --}}
<section class="trust">
    <div class="container">
        <div class="trust__grid">
            <div class="trust__card reveal">
                <div class="trust__icon">@include('public.partials.icon', ['name' => 'truck', 'size' => 30])</div>
                <h3>Kombiji</h3>
                <p>Putnički do 9 sedišta i teretni za selidbe i transport.</p>
            </div>
            <div class="trust__card reveal" data-delay="1">
                <div class="trust__icon">@include('public.partials.icon', ['name' => 'car', 'size' => 30])</div>
                <h3>Automobili</h3>
                <p>Rent-a-car ponuda — gradska i porodična vozila za svaki put.</p>
            </div>
            <div class="trust__card reveal" data-delay="2">
                <div class="trust__icon">@include('public.partials.icon', ['name' => 'shield', 'size' => 30])</div>
                <h3>Kasko osigurano</h3>
                <p>Sva vozila pod punim osiguranjem — voziš bez briga.</p>
            </div>
            <div class="trust__card reveal" data-delay="3">
                <div class="trust__icon">@include('public.partials.icon', ['name' => 'clock', 'size' => 30])</div>
                <h3>24/7 podrška</h3>
                <p>Tu smo na putu kad zatreba — u svako doba, bez čekanja.</p>
            </div>
        </div>
    </div>
</section>
