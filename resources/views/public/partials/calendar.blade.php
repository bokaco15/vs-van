{{-- Deljeni kalendar (booking-stil: biranje opsega datuma dolazak→povratak).
     reservation.js ga premešta u izabrano vozilo i popunjava .dani. --}}
<div class="kalendar">
    <div class="mesec">
        <button class="prev" type="button" aria-label="Prethodni mesec">&#10094;</button>
        <h1></h1>
        <button class="next" type="button" aria-label="Sledeći mesec">&#10095;</button>
    </div>
    <div class="dani-u-sedmici">
        <div>Pon</div>
        <div>Uto</div>
        <div>Sre</div>
        <div>Čet</div>
        <div>Pet</div>
        <div>Sub</div>
        <div>Ned</div>
    </div>
    <div class="dani"></div>

    <div class="kalendar-legend">
        <span><i class="lg lg-free"></i> Slobodno</span>
        <span><i class="lg lg-busy"></i> Zauzeto</span>
        <span><i class="lg lg-sel"></i> Izabrano</span>
    </div>

    <div class="kalendar-footer">
        <div class="kalendar-info">Izaberi datum dolaska i povratka</div>
        <button class="kalendar-reserve" type="button" disabled>Rezerviši</button>
    </div>
</div>
