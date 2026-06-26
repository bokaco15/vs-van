@extends('layouts.admin')

@section('title', 'FAQ')

@section('page-actions')
    <button type="button" class="btn btn-primary js-add"><i class="bi bi-plus-lg"></i> Novo pitanje</button>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-3"><i class="bi bi-info-circle"></i> Prevucite redove za promenu redosleda.</p>
            <table id="faqTable" class="table table-hover align-middle w-100" data-base="{{ url('/admin/faqs') }}">
                <thead>
                    <tr>
                        <th style="width:30px"></th>
                        <th>Pitanje</th>
                        <th style="width:80px">Aktivno</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="faqModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="faqForm">
                    @csrf
                    <input type="hidden" name="id" id="q_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Pitanje</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pitanje <span class="text-danger">*</span></label>
                            <input type="text" name="question" id="q_question" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Odgovor <span class="text-danger">*</span></label>
                            <textarea name="answer" id="q_answer" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" id="q_is_active" class="form-check-input" checked>
                            <label class="form-check-label" for="q_is_active">Aktivno</label>
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
        const base = $('#faqTable').data('base');
        const table = $('#faqTable').DataTable({
            processing: true, serverSide: true,
            ajax: '{{ route('admin.faqs.data') }}',
            order: [],
            columns: [
                { data: 'drag', orderable: false, searchable: false },
                { data: 'question', name: 'question' },
                { data: 'is_active', orderable: false, searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            drawCallback: function () {
                Admin.initSortableTable(this.api().table().node(), '{{ route('admin.faqs.sort') }}');
            },
            language: { search: 'Pretraga:', info: 'Prikaz _START_–_END_ od _TOTAL_', paginate: { next: '›', previous: '‹' }, zeroRecords: 'Nema rezultata', processing: 'Učitavanje...' },
        });

        $('.js-add').on('click', function () {
            $('#faqForm')[0].reset();
            $('#q_id').val('');
            $('#q_is_active').prop('checked', true);
            new bootstrap.Modal('#faqModal').show();
        });

        $('#faqTable').on('click', '.js-edit', function () {
            const d = $(this).data();
            $('#q_id').val(d.id);
            $('#q_question').val(d.question);
            $('#q_answer').val(d.answer);
            $('#q_is_active').prop('checked', String(d.is_active) === '1');
            new bootstrap.Modal('#faqModal').show();
        });

        $('#faqForm').validate({
            rules: { question: { required: true }, answer: { required: true } },
            submitHandler: function (form) {
                const id = $('#q_id').val();
                Admin.submitForm($(form), {
                    url: id ? `${base}/${id}` : '{{ route('admin.faqs.store') }}',
                    method: id ? 'PUT' : 'POST',
                    onSuccess: function () {
                        bootstrap.Modal.getInstance('#faqModal').hide();
                        table.ajax.reload(null, false);
                    },
                });
                return false;
            },
        });
    });
</script>
@endpush
