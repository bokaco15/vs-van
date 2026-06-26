@extends('layouts.admin')

@section('title', 'Vozila')

@section('page-actions')
    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Novo vozilo
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-3">
                <i class="bi bi-info-circle"></i> Prevucite redove (ručka levo) da promenite redosled prikaza na sajtu.
            </p>
            <table id="vehiclesTable" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th style="width:30px"></th>
                        <th style="width:70px">Slika</th>
                        <th>Naziv</th>
                        <th>Tip</th>
                        <th>Oznake</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#vehiclesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.vehicles.data') }}',
            order: [],
            columns: [
                { data: 'drag', orderable: false, searchable: false },
                { data: 'cover', name: 'cover', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                { data: 'badges', orderable: false, searchable: false },
                { data: 'actions', orderable: false, searchable: false },
            ],
            drawCallback: function () {
                Admin.initSortableTable(this.api().table().node(), '{{ route('admin.vehicles.sort') }}');
            },
            language: { search: 'Pretraga:', lengthMenu: 'Prikaži _MENU_', info: 'Prikaz _START_–_END_ od _TOTAL_', paginate: { next: '›', previous: '‹' }, zeroRecords: 'Nema rezultata', processing: 'Učitavanje...' },
        });
    });
</script>
@endpush
