@extends('layouts.admin')

@section('title', 'Novo vozilo')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="vehicleForm" action="{{ route('admin.vehicles.store') }}" method="POST">
                @csrf
                @include('admin.vehicles._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Sačuvaj i nastavi</button>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Otkaži</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#vehicleForm').validate({
            rules: { name: { required: true }, type: { required: true } },
            messages: { name: 'Naziv je obavezan.' },
            submitHandler: function (form) {
                Admin.submitForm($(form), {
                    onSuccess: function (res) {
                        // Posle kreiranja idi na edit (dodavanje slika).
                        if (res.data && res.data.redirect) {
                            window.location = res.data.redirect;
                        }
                    },
                });
                return false;
            },
        });
    });
</script>
@endpush
