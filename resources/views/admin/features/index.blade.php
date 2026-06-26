@extends('layouts.admin')

@section('title', 'Specifikacije')

@section('page-actions')
    <button type="button" class="btn btn-primary js-add" data-bs-toggle="modal" data-bs-target="#featureModal">
        <i class="bi bi-plus-lg"></i> Nova specifikacija
    </button>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="featuresTable" class="table table-hover align-middle w-100" data-base="{{ url('/admin/features') }}">
                <thead>
                    <tr>
                        <th style="width:70px">Ikonica</th>
                        <th>Naziv</th>
                        <th style="width:100px">Sort</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal forma --}}
    <div class="modal fade" id="featureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="featureForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="f_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Specifikacija</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Naziv <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="f_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Redosled</label>
                            <input type="number" name="sort_order" id="f_sort_order" class="form-control" min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ikonica (PNG/SVG)</label>
                            <input type="file" name="icon" class="form-control" accept=".png,.jpg,.jpeg,.webp,.svg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Otkaži</button>
                        <button type="submit" class="btn btn-primary">Sačuvaj</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const base = $('#featuresTable').data('base');
        const table = $('#featuresTable').DataTable({
            processing: true, serverSide: true,
            ajax: '{{ route('admin.features.data') }}',
            columns: [
                { data: 'icon', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'sort_order', name: 'sort_order' },
                { data: 'actions', orderable: false, searchable: false },
            ],
            language: { search: 'Pretraga:', info: 'Prikaz _START_–_END_ od _TOTAL_', paginate: { next: '›', previous: '‹' }, zeroRecords: 'Nema rezultata', processing: 'Učitavanje...' },
        });

        // Reset modala za "novo"
        $('.js-add').on('click', function () {
            $('#featureForm')[0].reset();
            $('#f_id').val('');
        });

        // Popuni modal za "izmenu"
        $('#featuresTable').on('click', '.js-edit', function () {
            const d = $(this).data();
            $('#f_id').val(d.id);
            $('#f_name').val(d.name);
            $('#f_sort_order').val(d.sort_order);
            new bootstrap.Modal('#featureModal').show();
        });

        // Submit (store ili update)
        $('#featureForm').validate({
            rules: { name: { required: true } },
            submitHandler: function (form) {
                const id = $('#f_id').val();
                Admin.submitForm($(form), {
                    url: id ? `${base}/${id}` : '{{ route('admin.features.store') }}',
                    method: id ? 'PUT' : 'POST',
                    onSuccess: function () {
                        bootstrap.Modal.getInstance('#featureModal').hide();
                        table.ajax.reload(null, false);
                    },
                });
                return false;
            },
        });
    });
</script>
@endpush
