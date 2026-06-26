{{-- Gornja traka: toggler za mobilni sidebar + ime admina + odjava --}}
<header class="admin-topbar bg-white border-bottom px-4 py-2 d-flex justify-content-between align-items-center">
    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted small">
            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
        </span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Odjava
            </button>
        </form>
    </div>
</header>
