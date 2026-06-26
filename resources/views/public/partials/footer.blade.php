{{-- Footer — kontakt podaci iz sekcija --}}
@php
    $email = $s['contact_email'] ?? 'vstimtransport@gmail.com';
    $mailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to='.$email
        .'&su=Iznajmljivanje%20Kombija&body=Pozdrav,%20želim%20da%20iznajmim%20kombi.';
@endphp
<div class="footer" id="footer-section">
    <div class="container footer-container">
        <div class="footer-wrapper-columns">
            <div class="footer-wrapper-col">
                <div class="col1">
                    <p class="fw-itemsHeading">Radno vreme</p>
                    <p class="fw-item">{{ $s['contact_hours_days'] ?? '' }} </p>
                    <p class="fw-item">{{ $s['contact_hours_time'] ?? '' }}</p>
                </div>
                <div class="col2">
                    <p class="fw-itemsHeading">Adresa</p>
                    <p class="fw-item"><a href="{{ $s['contact_address_url'] ?? '#' }}" target="_blank">{{ $s['contact_address'] ?? '' }}</a></p>
                </div>
            </div>
            <div class="footer-wrapper-col">
                <div class="col1 col0">
                    <p class="fw-itemsHeading">Kontaktirajte nas</p>
                    <p class="fw-item a-flex"><img src="{{ asset('img/Email.svg') }}" alt="Email"><a href="{{ $mailUrl }}" target="_blank">{{ $email }}</a></p>
                    <p class="fw-item a-flex"><img src="{{ asset('img/Instagram.svg') }}" alt="Instagram"><a href="{{ $s['contact_instagram_url'] ?? '#' }}" target="_blank">{{ $s['contact_instagram'] ?? '' }}</a></p>
                    <p class="fw-item a-flex"><img src="{{ asset('img/f-call-calling.svg') }}" alt="Call"><a href="tel:{{ $s['contact_phone'] ?? '' }}" target="_blank">{{ $s['contact_phone'] ?? '' }}</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="uLine"></div>
    <p class="fw-item copyright">VS TIM | © {{ date('Y') }} All rights reserved</p>
</div>
