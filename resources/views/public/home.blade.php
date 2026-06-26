@extends('layouts.public')

@section('title', 'VS Tim - Iznajmi kombi')

@section('content')
    {{-- Sekcije portovane iz vsvan/index.html — vizuelno identične, sadržaj iz baze --}}
    @include('public.partials.navbar')
    @include('public.partials.hero')
    @include('public.partials.stickers')
    @include('public.partials.offer')
    @include('public.partials.vehicles')
    @include('public.partials.faq')
    @include('public.partials.footer')
@endsection
