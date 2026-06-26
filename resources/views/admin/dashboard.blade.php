@extends('layouts.admin')

@section('title', 'Kontrolna tabla')

@section('content')
    <div class="row g-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Vozila</div>
                    <div class="h3 mb-0">{{ $vehiclesCount ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Rezervacije</div>
                    <div class="h3 mb-0">{{ $reservationsCount ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Specifikacije</div>
                    <div class="h3 mb-0">{{ $featuresCount ?? '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">FAQ</div>
                    <div class="h3 mb-0">{{ $faqsCount ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5">Dobrodošli 👋</h2>
            <p class="text-muted mb-0">
                Odaberite sekciju iz menija sa leve strane da biste upravljali sadržajem sajta.
            </p>
        </div>
    </div>
@endsection
