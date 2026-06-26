@extends('layouts.admin')

@section('title', 'Naša ponuda')

@section('page-actions')
    <button type="button" class="btn btn-primary js-add"><i class="bi bi-plus-lg"></i> Nova stavka</button>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-3"><i class="bi bi-info-circle"></i> Prevucite redove za promenu redosleda.</p>
            <table id="offerTable" class="table table-hover align-middle w-100" data-base="{{ url('/admin/offer-items') }}">
                <thead>
                    <tr>
                        <th style="width:30px"></th>
                        <th style="width:70px">Ikona</th>
                        <th>Naslov</th>
                        <th style="width:80px">Aktivno</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="offerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="offerForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="o_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Stavka ponude</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Naslov <span class="text-danger">*</span></label>
                            <input type="text" name="heading" id="o_heading" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opis <span class="text-danger">*</span></label>
                            <textarea name="description" id="o_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ikona (SVG/PNG)</label>
                            <input type="file" name="icon" class="form-control" accept=".png,.jpg,.jpeg,.webp,.svg">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" id="o_is_active" class="form-check-input" checked>
                            <label class="form-check-label" for="o_is_active">Aktivno</label>
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
        const base = $('#offerTable').data('base');
        const table = $('#offerTable').DataTable({
            processing: true, serverSide: true,
            ajax: '{{ route('admin.offer-items.data') }}',
            order: [],
            columns: [
                { data: 'drag', orderable: false, searchable: false },
                { data: 'icon', orderable: false, searchable: false },
                { data: 'heading', name: 'heading' },
                { data: 'is_active', orderable: false, searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            drawCallback: function () {
                Admin.initSortableTable(this.api().table().node(), '{{ route('admin.offer-items.sort') }}');
            },
            language: { search: 'Pretraga:', info: 'Prikaz _START_–_END_ od _TOTAL_', paginate: { next: '›', previous: '‹' }, zeroRecords: 'Nema rezultata', processing: 'Učitavanje...' },
        });

        $('.js-add').on('click', function () {
            $('#offerForm')[0].reset();
            $('#o_id').val('');
            $('#o_is_active').prop('checked', true);
            new bootstrap.Modal('#offerModal').show();
        });

        $('#offerTable').on('click', '.js-edit', function () {
            const d = $(this).data();
            $('#o_id').val(d.id);
            $('#o_heading').val(d.heading);
            $('#o_description').val(d.description);
            $('#o_is_active').prop('checked', String(d.is_active) === '1');
            new bootstrap.Modal('#offerModal').show();
        });

        $('#offerForm').validate({
            rules: { heading: { required: true }, description: { required: true } },
            submitHandler: function (form) {
                const id = $('#o_id').val();
                Admin.submitForm($(form), {
                    url: id ? `${base}/${id}` : '{{ route('admin.offer-items.store') }}',
                    method: id ? 'PUT' : 'POST',
                    onSuccess: function () {
                        bootstrap.Modal.getInstance('#offerModal').hide();
                        table.ajax.reload(null, false);
                    },
                });
                return false;
            },
        });
    });
</script>
@endpush
