@extends('layouts.public')

@section('title', 'VS Tim — Rent a car & rent a van | Iznajmljivanje vozila')

@section('content')
    {{-- Redizajnirana početna: moderni "transport premium" izgled.
         Sadržaj i dalje dolazi iz baze (sekcije, vozila, ponuda, FAQ). --}}
    @include('public.partials.navbar')
    @include('public.partials.hero')

    {{-- Marquee traka — pokretni natpis kategorija --}}
    <div class="marquee" aria-hidden="true">
        <div class="marquee__track">
            @for ($i = 0; $i < 2; $i++)
                <span>Rent a car</span><span class="dot">●</span>
                <span>Rent a van</span><span class="dot">●</span>
                <span>Automobili</span><span class="dot">●</span>
                <span>Putnički kombi</span><span class="dot">●</span>
                <span>Teretni kombi</span><span class="dot">●</span>
                <span>Selidbe</span><span class="dot">●</span>
                <span>Kasko osigurano</span><span class="dot">●</span>
                <span>24/7 podrška</span><span class="dot">●</span>
            @endfor
        </div>
    </div>

    @include('public.partials.stickers')
    @include('public.partials.offer')
    @include('public.partials.vehicles')
    @include('public.partials.faq')
    @include('public.partials.footer')
@endsection
