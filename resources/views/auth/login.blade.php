@extends('layouts.guest')

@section('title', 'Admin prijava')

@section('content')
    <h1 class="h4 mb-1 text-center">VS Tim Admin</h1>
    <p class="text-muted text-center small mb-4">Prijavite se da biste pristupili admin panelu</p>

    {{-- Status poruka (npr. nakon odjave) --}}
    @if (session('status'))
        <div class="alert alert-success py-2">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Lozinka</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label">Zapamti me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Prijavi se</button>
    </form>
@endsection
