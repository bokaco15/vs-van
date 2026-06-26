{{-- Deljena polja forme za vozilo. Očekuje $vehicle i $features. --}}
@php
    $selectedFeatures = $vehicle->features->keyBy('id');
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Naziv <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ $vehicle->name }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tip <span class="text-danger">*</span></label>
        <select name="type" class="form-select" required>
            <option value="van" @selected($vehicle->type === 'van')>Kombi (van)</option>
            <option value="car" @selected($vehicle->type === 'car')>Auto (car)</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Podnaslov</label>
        <input type="text" name="subtitle" class="form-control" value="{{ $vehicle->subtitle }}" placeholder="npr. 2017. 2.0TDI">
    </div>
    <div class="col-md-6">
        <label class="form-label">Redosled (sort)</label>
        <input type="number" name="sort_order" class="form-control" value="{{ $vehicle->sort_order }}" min="0">
    </div>

    <div class="col-12">
        <label class="form-label">Opis</label>
        <textarea name="description" class="form-control" rows="3">{{ $vehicle->description }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label d-block">Specifikacije</label>
        <div class="row g-2">
            @foreach ($features as $feature)
                @php $sel = $selectedFeatures->get($feature->id); @endphp
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox"
                                   name="features[]" value="{{ $feature->id }}"
                                   @checked($sel)>
                        </div>
                        <span class="input-group-text flex-grow-1 justify-content-start">{{ $feature->name }}</span>
                        <input type="text" class="form-control" name="feature_values[{{ $feature->id }}]"
                               value="{{ $sel->pivot->value ?? '' }}" placeholder="vrednost (opc.)">
                    </div>
                </div>
            @endforeach
        </div>
        @if ($features->isEmpty())
            <div class="text-muted small">Nema specifikacija — dodajte ih u sekciji „Specifikacije”.</div>
        @endif
    </div>

    <div class="col-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="is_recommended" @checked($vehicle->is_recommended)>
            <label class="form-check-label" for="is_recommended">Preporučeno</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked($vehicle->is_featured)>
            <label class="form-check-label" for="is_featured">Izdvojeno</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($vehicle->is_active)>
            <label class="form-check-label" for="is_active">Aktivno (prikazano na sajtu)</label>
        </div>
    </div>
</div>
