{{-- Kontakt stavke (deljeno: gornja traka + responsive meni). Podaci iz sekcija. --}}
@php
    $email = $s['contact_email'] ?? 'vstimtransport@gmail.com';
    $mailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to='.$email
        .'&su=Iznajmljivanje%20Kombija&body=Pozdrav,%20želim%20da%20iznajmim%20kombi.';
@endphp
<div class="nav-info-item">
    <a href="{{ $mailUrl }}" target="_blank">
        <img src="{{ asset('img/nav-info-mail.svg') }}" alt="e-mail contact">
        {{ $email }}
    </a>
</div>
<div class="nav-info-item">
    <a href="{{ $s['contact_instagram_url'] ?? '#' }}" target="_blank">
        <img src="{{ asset('img/nav-info-ig.svg') }}" alt="instagram contact">
        {{ $s['contact_instagram'] ?? '' }}
    </a>
</div>
<div class="nav-info-item">
    <a href="tel:{{ $s['contact_phone'] ?? '' }}" target="_blank">
        <img src="{{ asset('img/nav-info-phone.svg') }}" alt="phone contact">
        <p>{{ $s['contact_phone'] ?? '' }}</p>
    </a>
</div>
