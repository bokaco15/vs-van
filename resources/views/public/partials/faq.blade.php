{{-- FAQ sekcija (fourth-section) — stavke iz baze --}}
<div class="section fourth-section" id="faq-section">
    <img src="{{ asset('img/Vector.png') }}" alt="" id="vector">

    <div class="container">
        <div class="faq-heading-wrapper">
            <h1 class="faq-heading">Često postavljena pitanja</h1>
        </div>

        @foreach ($faqs as $faq)
            <div class="faq">
                <div class="question">
                    <h3>{{ $faq->question }}</h3>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="answer">
                    <p>{{ $faq->answer }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
