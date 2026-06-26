@extends('layouts.admin')

@section('title', 'Izmena vozila')

@section('content')
    <div class="row g-4">
        {{-- Forma vozila --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Podaci o vozilu</h2>
                    <form id="vehicleForm" action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.vehicles._form')

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Sačuvaj izmene</button>
                            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Nazad</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Galerija slika --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Slike</h2>

                    <form id="imageForm" action="{{ route('admin.vehicles.images.store', $vehicle) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="images[]" id="imagesInput" class="form-control" accept="image/*" multiple required>
                            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-upload"></i> Otpremi</button>
                        </div>
                        <div class="form-text">JPG/PNG/WebP, do 5MB. Konvertuje se u WebP. Prevucite slike da promenite redosled.</div>
                    </form>

                    <div id="imageGrid" class="row g-2">
                        @foreach ($vehicle->images as $image)
                            @include('admin.vehicles._image', ['image' => $image])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        // 1) Izmena podataka vozila
        $('#vehicleForm').validate({
            rules: { name: { required: true }, type: { required: true } },
            submitHandler: function (form) {
                Admin.submitForm($(form));
                return false;
            },
        });

        // 2) Upload slika
        $('#imageForm').on('submit', function (e) {
            e.preventDefault();
            Admin.submitForm($(this), {
                onSuccess: function () { window.location.reload(); },
            });
        });

        // 3) Brisanje slike (delegirano)
        $('#imageGrid').on('click', '.js-img-delete', function () {
            if (!confirm('Obrisati sliku?')) return;
            const $col = $(this).closest('.js-img');
            $.ajax({ url: $(this).data('url'), method: 'POST', data: { _method: 'DELETE' } })
                .done((res) => { Admin.success(res.message); $col.remove(); })
                .fail((xhr) => Admin.handleAjaxError(xhr));
        });

        // 4) Postavi naslovnu
        $('#imageGrid').on('click', '.js-img-cover', function () {
            $.ajax({ url: $(this).data('url'), method: 'POST', data: { _method: 'PUT' } })
                .done((res) => {
                    Admin.success(res.message);
                    $('#imageGrid .js-cover-badge').addClass('d-none');
                    $(this).closest('.js-img').find('.js-cover-badge').removeClass('d-none');
                })
                .fail((xhr) => Admin.handleAjaxError(xhr));
        });

        // 5) Redosled slika (drag-and-drop)
        const grid = document.getElementById('imageGrid');
        if (window.Sortable && grid) {
            Sortable.create(grid, {
                animation: 150,
                onEnd: function () {
                    const ids = Array.from(grid.querySelectorAll('.js-img')).map((el) => el.getAttribute('data-id'));
                    $.ajax({ url: '{{ route('admin.vehicles.images.sort', $vehicle) }}', method: 'POST', data: { ids } })
                        .done((res) => Admin.success(res.message))
                        .fail((xhr) => Admin.handleAjaxError(xhr));
                },
            });
        }
    });
</script>
@endpush
