@extends('layouts.admin')

@section('title', 'Rezervacije')

@section('content')
    <div class="row g-4">
        {{-- Forma za dodavanje zauzetog termina --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Dodaj zauzet termin</h2>
                    <form id="reservationForm" action="{{ route('admin.reservations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Vozilo <span class="text-danger">*</span></label>
                            <select name="vehicle_id" id="r_vehicle" class="form-select" required>
                                <option value="">— izaberi —</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Datum <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Označi kao zauzeto</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabela rezervacija --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Zauzeti termini</h2>
                        <select id="filterVehicle" class="form-select w-auto">
                            <option value="">Sva vozila</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table id="reservationsTable" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Vozilo</th>
                                <th>Datum</th>
                                <th>Status</th>
                                <th style="width:60px"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#reservationsTable').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: '{{ route('admin.reservations.data') }}',
                data: function (d) { d.vehicle_id = $('#filterVehicle').val(); },
            },
            columns: [
                { data: 'vehicle', name: 'vehicle', orderable: false },
                { data: 'date', name: 'date' },
                { data: 'status', name: 'status' },
                { data: 'actions', orderable: false, searchable: false },
            ],
            order: [[1, 'asc']],
            language: { search: 'Pretraga:', info: 'Prikaz _START_–_END_ od _TOTAL_', paginate: { next: '›', previous: '‹' }, zeroRecords: 'Nema rezultata', processing: 'Učitavanje...' },
        });

        $('#filterVehicle').on('change', () => table.ajax.reload());

        $('#reservationForm').validate({
            rules: { vehicle_id: { required: true }, date: { required: true } },
            submitHandler: function (form) {
                Admin.submitForm($(form), {
                    onSuccess: function () {
                        form.reset();
                        table.ajax.reload(null, false);
                    },
                });
                return false;
            },
        });
    });
</script>
@endpush
