{{-- FAQ — akordeon, stavke iz baze --}}
<section class="faq-section" id="faq-section">
    <div class="container">
        <div class="faq-section__head reveal">
            <span class="eyebrow">Pitanja i odgovori</span>
            <h2 class="faq-section__title">Često postavljena pitanja</h2>
        </div>

        <div class="faq-list reveal" data-delay="1">
            @foreach ($faqs as $faq)
                <div class="faq">
                    <div class="question">
                        <h3>{{ $faq->question }}</h3>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </div>
                    <div class="answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
