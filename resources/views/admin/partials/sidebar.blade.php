{{--
    Bočni meni admin panela.
    Linkovi koriste url('/admin/...') + request()->is(...) za "active" stanje,
    pa ne zavise od toga da li je svaka ruta već definisana (gradimo ih u fazama).
--}}
<aside class="admin-sidebar text-white d-flex flex-column">
    <div class="admin-brand px-3 py-3 border-bottom border-secondary">
        <a href="{{ url('/admin') }}" class="d-flex align-items-center text-white text-decoration-none">
            <i class="bi bi-truck fs-4 me-2"></i>
            <span class="fw-bold">VS Tim Admin</span>
        </a>
    </div>

    <nav class="nav flex-column px-2 py-3 flex-grow-1">
        <a href="{{ url('/admin') }}"
           class="nav-link text-white {{ request()->is('admin') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Kontrolna tabla
        </a>
        <a href="{{ url('/admin/vehicles') }}"
           class="nav-link text-white {{ request()->is('admin/vehicles*') ? 'active' : '' }}">
            <i class="bi bi-truck me-2"></i> Vozila
        </a>
        <a href="{{ url('/admin/reservations') }}"
           class="nav-link text-white {{ request()->is('admin/reservations*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check me-2"></i> Rezervacije
        </a>
        <a href="{{ url('/admin/features') }}"
           class="nav-link text-white {{ request()->is('admin/features*') ? 'active' : '' }}">
            <i class="bi bi-stars me-2"></i> Specifikacije
        </a>
        <a href="{{ url('/admin/offer-items') }}"
           class="nav-link text-white {{ request()->is('admin/offer-items*') ? 'active' : '' }}">
            <i class="bi bi-grid me-2"></i> Naša ponuda
        </a>
        <a href="{{ url('/admin/faqs') }}"
           class="nav-link text-white {{ request()->is('admin/faqs*') ? 'active' : '' }}">
            <i class="bi bi-question-circle me-2"></i> FAQ
        </a>
        <a href="{{ url('/admin/sections') }}"
           class="nav-link text-white {{ request()->is('admin/sections*') ? 'active' : '' }}">
            <i class="bi bi-pencil-square me-2"></i> Sekcije / Tekst
        </a>
    </nav>

    <div class="px-2 py-3 border-top border-secondary">
        <a href="{{ url('/') }}" target="_blank" class="nav-link text-white-50">
            <i class="bi bi-box-arrow-up-right me-2"></i> Pogledaj sajt
        </a>
    </div>
</aside>
