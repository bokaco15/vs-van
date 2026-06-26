\# TASK — VSVAN Update (Rent a Vehicle)



Imam Laravel projekat pod nazivom `vsvan\_update`. Unutar njega se nalazi folder `vsvan/`

koji sadrži moj \*\*postojeći statički frontend\*\* (HTML, CSS, vanilla JS + jQuery) za sajt

za rentiranje kombija.



\*\*VAŽNO:\*\* Folder `vsvan/` mi služi SAMO kao referenca za izgled i ponašanje. NIJE trajni

deo projekta. Koristi ga kao izvor — prekopiraj CSS, JS i slike i pretvori HTML u Blade —

a kada Laravel verzija proradi identično, `vsvan/` ćemo obrisati. Ne tretiraj ga kao deo

finalne arhitekture.



Sajt ima kalendarski prikaz zauzetosti svakog kombija po danima i mesecima, i WhatsApp

rezervaciju klikom na slobodan dan. Trenutno frontend čita podatke iz statičkog

`reservations.json` fajla i statičkih slika. Želim da kompletan backend prebaciš na Laravel

i da frontend integrišeš u Blade strukturu, \*\*zadržavajući postojeću stilizaciju (CSS) i

animacije (script.js) netaknute\*\*.



\## Autentifikacija i admin panel



\- Koristi \*\*Laravel Breeze\*\* za autentifikaciju (login admina, zaštićene rute).

\- Admin panel gradi \*\*ručno\*\* — sopstveni kontroleri, Blade forme, validacija. Ne koristi

&#x20; Filament/Nova. Želim da razumem i kontrolišem svaki deo.

\- Zaštiti admin rute middleware-om (samo ulogovan admin pristupa).

\- Razdvoji jasno: javni frontend (kombiji, kalendar) i admin deo (`/admin/...`).



\## Admin tabele



\- Za sve tabelarne prikaze u admin panelu (lista vozila, rezervacija, sekcija) koristi

&#x20; \*\*Yajra DataTables\*\* (`yajra/laravel-datatables`).

\- Koristi \*\*server-side processing\*\* za tabele (zbog paginacije, pretrage i sortiranja

&#x20; preko baze, da radi i kad ima puno podataka).

\- Predloži kako da u DataTables koloni dodam akcione dugmiće (Edit / Delete / upravljanje

&#x20; slikama) i kako da prikažem boolean polja (`is\_recommended`, `is\_featured`) kao

&#x20; badge/ikonice.

\- Objasni postavku: instalacija paketa, DataTable klase ili inline u kontroleru, AJAX ruta,

&#x20; i frontend inicijalizacija.



\## Forme, validacija i poruke



\- Sve admin forme šalji preko \*\*AJAX-a\*\* (da bih mogao da prikažem poruke bez reload-a

&#x20; stranice).

\- Klijentska validacija: koristi \*\*jQuery Validation\*\* plugin za proveru polja pre slanja

&#x20; (obavezna polja, tip fajla, dužina teksta itd.).

\- Serverska validacija: zadrži Laravel validaciju (\*\*Form Request\*\* klase) kao sigurnu

&#x20; proveru — klijentska validacija je samo UX, server je autoritet.

\- Poruke korisniku prikazuj preko \*\*Toastr.js\*\*:

&#x20; - Na uspešan odgovor (2xx) → `toastr.success(...)` sa porukom iz Laravel JSON odgovora.

&#x20; - Na grešku (validacija 422, server 5xx) → `toastr.error(...)`; kod 422 ispiši konkretne

&#x20;   validacione greške iz Laravel odgovora.

\- Predloži konzistentan format JSON odgovora iz kontrolera (npr. `{ status, message, errors }`)

&#x20; tako da frontend uvek zna kako da reaguje, i objasni kako da mapiram Laravel validacione

&#x20; greške na jQuery Validation / Toastr prikaz.



\## Arhitektura i skalabilnost



Projekat treba osmisliti kao "rent a vehicle" platformu, ne samo "rent a van":



\- Vozila imaju polje `type` (`van`/`car`) da bih kasnije lako dodao rent-a-car ponudu i

&#x20; filtrirao ih zasebno.

\- Migracije, modeli (`Vehicle`, `VehicleImage`, `Reservation`, `Section`), kontroleri i rute

&#x20; treba da budu čisto organizovani.



\## Vozila (Vehicles)



\- Ručno napravljen CRUD kroz admin panel (Yajra DataTables index, create/edit forme, delete

&#x20; sa potvrdom).

\- Polje `sort\_order` za sortiranje redosleda prikaza. Za drag-and-drop predloži jednostavno

&#x20; rešenje (npr. SortableJS + AJAX ruta koja čuva redosled), ali objasni i prostiju varijantu

&#x20; sa numeričkim poljem.

\- Boolean polja `is\_recommended` ("preporučeno") i `is\_featured` ("izdvojeno") kao čekboksovi

&#x20; u formi, prikazani kao badge u DataTables.

\- \*\*Paginacija\*\* vozila na javnom prikazu (Laravel paginator), uz zadržavanje postojeće

&#x20; stilizacije kartica.

\- Filtriranje po tipu (van vs car).



\## Slike / Galerija



\- Svako vozilo može imati više slika (relacija jedan-na-više, model `VehicleImage`).

\- Koristi \*\*Intervention Image\*\* za obradu uploadovanih slika.

\- Svaka uploadovana slika treba da bude konvertovana i sačuvana u \*\*WebP\*\* formatu

&#x20; (po mogućstvu thumbnail + full rezolucija).

\- Upload više slika odjednom u admin formi, sa mogućnošću brisanja pojedinačnih i određivanja

&#x20; redosleda. Validaciju uploada radi i kroz jQuery Validation (tip/veličina) i kroz Laravel.

\- Na javnom frontendu, klikom na sliku vozila otvara se \*\*galerija (lightbox)\*\* kroz koju

&#x20; listam sve fotografije tog vozila. Predloži lagano rešenje (vanilla JS ili lightweight

&#x20; biblioteka) koje se uklapa u postojeći stil.



\## Rezervacije / Kalendar



\- Migriraj logiku iz `reservations.json` u bazu: tabela `reservations`

&#x20; (`vehicle\_id`, datum/mesec+dan, status).

\- Kalendar povlači zauzete dane preko Laravel API endpoint-a (JSON ruta) umesto statičkog

&#x20; fajla — \*\*zadrži postojeću JS logiku kalendara (`reservation.js`), samo izmeni izvor

&#x20; podataka\*\* sa `fetch("reservations.json")` na Laravel rutu.

\- Bitan detalj o formatu podataka: kalendar očekuje podatke key-ovane po id-u vozila

&#x20; (`van1..van4`) i po malim srpskim imenima meseci (`januar..decembar`) → niz brojeva dana.

&#x20; Endpoint mora da reprodukuje taj oblik (ili svesno prilagodi JS).

\- Zadrži postojeću WhatsApp rezervacionu logiku (klik na slobodan dan → WhatsApp poruka sa

&#x20; prefilovanom srpskom porukom na `https://wa.me/<phone>?text=...`).

\- U admin panelu mogu ručno da označavam/uklanjam zauzete datume za svako vozilo (lista

&#x20; rezervacija takođe kroz Yajra DataTables, akcije preko AJAX-a sa Toastr porukama).



\## Admin panel — editovanje sekcija



\- Tabela `sections` (key-value ili struktuiran model) za tekstualni sadržaj sajta.

\- Kroz admin menjam tekst sekcija (hero tekst, "naša ponuda", FAQ pitanja i odgovori,

&#x20; kontakt info) bez diranja koda. Snimanje preko AJAX forme sa jQuery Validation + Toastr.

\- Poveži `sections` sa odgovarajućim Blade prikazima.



\## Tehnički zahtevi



\- Laravel (najnovija stabilna verzija) + Breeze + Yajra DataTables.

\- Frontend admin biblioteke: jQuery, jQuery Validation, Toastr.js (i SortableJS gde treba).

&#x20; Objasni kako da ih uključim (CDN ili preko Vite/npm).

\- Frontend assets (CSS, JS) ubaci pravilno u Laravel strukturu; razbij HTML u Blade

&#x20; partial-e/komponente gde ima smisla, ali \*\*vizuelni rezultat mora ostati identičan\*\*.

\- Validacija upload-a slika (tip, veličina) i svih formi (klijent + server).

\- CSRF token pravilno postavljen za sve AJAX zahteve.

\- Čist, komentarisan kod sa objašnjenjima \*\*zašto\*\* nešto radiš (pošto želim da učim).



\## Način rada — VAŽNO



Radimo \*\*fazu po fazu\*\*, ne sve odjednom. Posle svake faze stajemo da pregledam i razumem

kod pre nego što kreneš dalje. Ne pravi desetine fajlova u jednom koraku.



Predloženi redosled faza:



1\. \*\*Frontend migracija\*\* — `vsvan/` → Blade + Vite assets, vizuelno 100% identično.

&#x20;  Podaci o kombijima za sada hardkodovani u Blade (kao u originalu).

2\. \*\*Baza\*\* — migracije + modeli + relacije (`Vehicle`, `VehicleImage`, `Reservation`,

&#x20;  `Section`). Testirati sa `php artisan migrate`.

3\. \*\*Seeder\*\* — prebaci podatke iz `reservations.json` u bazu.

4\. \*\*Kalendar endpoint\*\* — zameni statički JSON Laravel rutom, zadrži JS logiku.

5\. \*\*Breeze auth + admin skelet\*\* — login, zaštićene `/admin` rute.

6\. \*\*Admin CRUD za vozila\*\* — Yajra DataTables + AJAX forme + jQuery Validation + Toastr.

7\. \*\*Slike + galerija\*\* — Intervention Image / WebP, multi-upload, lightbox.

8\. \*\*Admin rezervacije + sekcije\*\*.

9\. \*\*Čišćenje\*\* — obrisati `vsvan/` kad sve radi.



\## Prvi korak (uradi sada)



BEZ pisanja koda, prvo mi predloži:



1\. \*\*Strukturu baze\*\* — migracije i relacije među modelima, sa kratkim objašnjenjem svake

&#x20;  odluke (zašto to polje, zašto ta relacija).

2\. \*\*Strukturu fajlova/foldera\*\* — gde idu kontroleri, modeli, Blade fajlovi, rute.



Pa da onda idemo korak po korak kroz implementaciju, da mogu da pratim i razumem svaki deo.

