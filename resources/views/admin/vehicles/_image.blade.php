{{-- Jedna slika u galeriji (edit vozila). Očekuje $image. --}}
<div class="col-4 js-img" data-id="{{ $image->id }}">
    <div class="position-relative border rounded overflow-hidden">
        <img src="{{ $image->thumb_url }}" class="w-100" style="height:90px;object-fit:cover" alt="">

        <span class="badge text-bg-success position-absolute top-0 start-0 m-1 js-cover-badge {{ $image->is_cover ? '' : 'd-none' }}">
            Naslovna
        </span>

        <div class="position-absolute bottom-0 end-0 m-1 btn-group btn-group-sm">
            <button type="button" class="btn btn-light js-img-cover" title="Postavi kao naslovnu"
                    data-url="{{ route('admin.images.cover', $image) }}">
                <i class="bi bi-star"></i>
            </button>
            <button type="button" class="btn btn-light text-danger js-img-delete" title="Obriši"
                    data-url="{{ route('admin.images.destroy', $image) }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>
