@extends('layouts.admin')

@section('title', 'Sekcije / Tekst')

@section('content')
    <form id="sectionsForm" action="{{ route('admin.sections.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach ($groups as $groupName => $items)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong class="text-capitalize">{{ $groupName ?: 'Ostalo' }}</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($items as $section)
                            <div class="col-md-6">
                                <label class="form-label">{{ $section->label ?? $section->key }}</label>
                                @if (mb_strlen((string) $section->value) > 60)
                                    <textarea name="sections[{{ $section->key }}]" class="form-control" rows="3">{{ $section->value }}</textarea>
                                @else
                                    <input type="text" name="sections[{{ $section->key }}]" class="form-control" value="{{ $section->value }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Sačuvaj tekst</button>
    </form>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#sectionsForm').on('submit', function (e) {
            e.preventDefault();
            Admin.submitForm($(this));
        });
    });
</script>
@endpush
