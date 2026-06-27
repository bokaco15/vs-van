/* =========================================================================
   VS Tim — interakcije + GSAP animacije za "Vice City / GTA VI" početnu.
   - Kinematografski intro (hero timeline), scroll-reveal i parallax preko GSAP
     + ScrollTrigger (učitani kao globalni skriptovi u layout-u).
   - Ako GSAP nije dostupan ili je uključen "reduced motion", sve se odmah
     prikaže (graceful fallback) — sadržaj nikad ne ostaje skriven.
   - Kalendar/WhatsApp logika je i dalje u reservation.js (ne diramo).
   ========================================================================= */

(function () {
    "use strict";

    const gsap = window.gsap;
    const ScrollTrigger = window.ScrollTrigger;
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const useGsap = !!gsap && !reduceMotion;

    /* ----- Fallback: otkrij sav sadržaj kad nema animacija ----- */
    function revealEverything() {
        document.documentElement.classList.add("is-revealed");
        document.body.classList.remove("gsap-armed");
        document.querySelectorAll("[data-hero]").forEach((el) => (el.style.opacity = "1"));
        document.querySelectorAll(".reveal, .van-item").forEach((el) => {
            el.style.opacity = "1";
            el.style.transform = "none";
        });
    }

    /* -----------------------  Mobilni meni  ----------------------- */
    const toggle = document.querySelector(".nav-toggle");
    const menu = document.querySelector(".mobile-menu");
    const menuClose = document.querySelector(".mobile-menu__close");
    const openMenu = () => { if (menu) { menu.classList.add("is-open"); document.body.style.overflow = "hidden"; } };
    const closeMenu = () => { if (menu) { menu.classList.remove("is-open"); document.body.style.overflow = ""; } };
    if (toggle) toggle.addEventListener("click", openMenu);
    if (menuClose) menuClose.addEventListener("click", closeMenu);
    if (menu) menu.querySelectorAll("a").forEach((a) => a.addEventListener("click", closeMenu));

    /* -----------------------  Sticky header  ----------------------- */
    const header = document.querySelector(".site-header");
    if (header) {
        const onScroll = () => header.classList.toggle("is-stuck", window.scrollY > 40);
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();
    }

    /* -----------------------  FAQ akordeon  ----------------------- */
    document.querySelectorAll(".faq .question").forEach((q) => {
        q.addEventListener("click", () => {
            const answer = q.nextElementSibling;
            const isOpen = q.classList.contains("is-open");
            document.querySelectorAll(".faq .question").forEach((o) => {
                o.classList.remove("is-open");
                if (o.nextElementSibling) o.nextElementSibling.style.maxHeight = null;
            });
            if (!isOpen && answer) {
                q.classList.add("is-open");
                answer.style.maxHeight = answer.scrollHeight + "px";
            }
        });
    });

    /* -----------------------  Ponuda akordeon  ----------------------- */
    document.querySelectorAll(".acc__head").forEach((head) => {
        head.addEventListener("click", () => {
            const item = head.closest(".acc__item");
            const body = item ? item.querySelector(".acc__body") : null;
            const isOpen = item && item.classList.contains("is-open");
            document.querySelectorAll(".acc__item").forEach((o) => {
                o.classList.remove("is-open");
                const b = o.querySelector(".acc__body");
                if (b) b.style.maxHeight = null;
            });
            if (!isOpen && item && body) {
                item.classList.add("is-open");
                body.style.maxHeight = body.scrollHeight + "px";
            }
        });
    });

    /* ----------------  Flota: filter + pretraga + paginacija  ---------------- */
    // Sve je client-side: promena kategorije ili pretraga NE osvežava stranicu,
    // samo se ponovo prikaže odgovarajući set kartica i preračuna paginacija
    // (po `data-per-page`, podrazumevano 9). Klik na stranicu vraća na sekciju.
    const fleet = document.querySelector("[data-fleet]");
    if (fleet) {
        const grid = fleet.querySelector("[data-fleet-grid]");
        const cards = grid ? Array.from(grid.querySelectorAll(".van-item")) : [];
        const filter = fleet.querySelector(".filter");
        const buttons = filter ? Array.from(filter.querySelectorAll(".filter__btn")) : [];
        const indicator = filter ? filter.querySelector(".filter__indicator") : null;
        const firstBtn = buttons[0];
        const searchInput = fleet.querySelector("[data-fleet-search]");
        const emptyEl = fleet.querySelector("[data-fleet-empty]");
        const pager = fleet.querySelector("[data-fleet-pagination]");
        const perPage = parseInt(fleet.dataset.perPage, 10) || 9;

        let currentType = "all";
        let currentQuery = "";
        let currentPage = 1;

        const activeBtn = () => buttons.find((b) => b.classList.contains("is-active")) || firstBtn;

        const moveIndicator = (btn) => {
            if (!indicator || !firstBtn || !btn) return;
            indicator.style.width = btn.offsetWidth + "px";
            indicator.style.transform = "translateX(" + (btn.offsetLeft - firstBtn.offsetLeft) + "px)";
        };

        // Kartice koje zadovoljavaju i tip i pretragu
        const getMatches = () => cards.filter((c) => {
            const typeOk = currentType === "all" || c.dataset.type === currentType;
            const nameOk = !currentQuery || (c.dataset.name || "").indexOf(currentQuery) !== -1;
            return typeOk && nameOk;
        });

        // Dinamička paginacija (sa skraćivanjem: 1 … 4 5 6 … 12)
        const buildPager = (totalPages) => {
            if (!pager) return;
            pager.innerHTML = "";
            if (totalPages <= 1) return;

            const addBtn = (label, page, opts) => {
                opts = opts || {};
                const passive = opts.disabled || opts.active || opts.dots;
                const el = document.createElement(passive ? "span" : "button");
                el.className = "pg-btn"
                    + (opts.disabled ? " pg-btn--disabled" : "")
                    + (opts.active ? " pg-btn--active" : "")
                    + (opts.dots ? " pg-btn--dots" : "");
                el.innerHTML = label;
                if (!passive) {
                    el.type = "button";
                    el.addEventListener("click", () => goTo(page));
                }
                pager.appendChild(el);
            };

            addBtn("&#8592;", currentPage - 1, { disabled: currentPage === 1 });

            const seq = [];
            for (let p = 1; p <= totalPages; p++) {
                if (p === 1 || p === totalPages || Math.abs(p - currentPage) <= 1) seq.push(p);
                else if (seq[seq.length - 1] !== "…") seq.push("…");
            }
            seq.forEach((p) => {
                if (p === "…") addBtn("…", null, { dots: true });
                else addBtn(String(p), p, { active: p === currentPage });
            });

            addBtn("&#8594;", currentPage + 1, { disabled: currentPage === totalPages });
        };

        const render = (animate) => {
            const matches = getMatches();
            const totalPages = Math.max(1, Math.ceil(matches.length / perPage));
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * perPage;
            const pageItems = matches.slice(start, start + perPage);
            const pageSet = new Set(pageItems);

            cards.forEach((c) => c.classList.toggle("is-hidden", !pageSet.has(c)));
            if (emptyEl) emptyEl.classList.toggle("is-visible", matches.length === 0);

            buildPager(totalPages);

            if (animate && useGsap && pageItems.length) {
                gsap.fromTo(pageItems, { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.06, ease: "power3.out", overwrite: true });
            }
        };

        const goTo = (page) => {
            currentPage = page;
            render(true);
            fleet.scrollIntoView({ behavior: "smooth", block: "start" });
        };

        // Filter (Sva / Kombiji / Auta) — bez refresha
        buttons.forEach((btn) => {
            btn.addEventListener("click", () => {
                if (btn.classList.contains("is-active")) return;
                buttons.forEach((b) => b.classList.remove("is-active"));
                btn.classList.add("is-active");
                moveIndicator(btn);
                currentType = btn.dataset.filter;
                currentPage = 1;
                render(true);
            });
        });

        // Live pretraga po nazivu (debounce), resetuje na prvu stranicu
        if (searchInput) {
            let t = null;
            searchInput.addEventListener("input", () => {
                clearTimeout(t);
                t = setTimeout(() => {
                    currentQuery = searchInput.value.trim().toLowerCase();
                    currentPage = 1;
                    render(true);
                }, 160);
            });
        }

        // Indikator: snap bez animacije na učitavanju, pa prati aktivno/resize
        if (indicator && firstBtn) {
            indicator.style.transition = "none";
            requestAnimationFrame(() => {
                moveIndicator(activeBtn());
                requestAnimationFrame(() => { indicator.style.transition = ""; });
            });
        }
        window.addEventListener("load", () => moveIndicator(activeBtn()));
        window.addEventListener("resize", () => moveIndicator(activeBtn()));

        render(false); // inicijalni prikaz prve strane (kartice su već vidljive)
    }

    /* =====================  GSAP ANIMACIJE  ===================== */
    if (!useGsap) {
        revealEverything();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    // Inicijalno sakrij animirane elemente preko GSAP-a (inline) pa skini
    // .gsap-armed klasu — tako nema "blica" pre nego što timeline krene.
    // Napomena: kartice (.van-item) NE skrivamo preko GSAP-a — njihovu vidljivost
    // sada kontroliše fleet renderer (paginacija), a animira ih on na promenu.
    gsap.set("[data-hero]", { opacity: 0 });
    document.body.classList.remove("gsap-armed");

    // Suptilan "zoom-out" pozadine na učitavanju (kinematografski ulaz)
    gsap.fromTo(".hero__bg", { scale: 1.18 }, { scale: 1.08, duration: 1.6, ease: "power2.out" });

    /* ---- Hero intro timeline ---- */
    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });
    tl.fromTo('[data-hero="eyebrow"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 })
      .fromTo(".hero__title .line", { opacity: 0, yPercent: 120 }, { opacity: 1, yPercent: 0, duration: 0.95, stagger: 0.1 }, "-=0.2")
      .fromTo('[data-hero="lead"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.55")
      .fromTo('[data-hero="actions"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.4")
      .fromTo('[data-hero="stats"]', { opacity: 0, y: 26 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.4");

    /* ---- Parallax pozadine dok se skroluje hero ---- */
    const hero = document.querySelector(".hero");
    if (hero) {
        gsap.to(".hero__bg", { yPercent: 18, ease: "none", scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: true } });
    }

    /* ---- Scroll-reveal sekcija ---- */
    gsap.utils.toArray(".reveal").forEach((el) => {
        const delay = parseFloat(el.dataset.delay || 0) * 0.08;
        gsap.fromTo(el, { opacity: 0, y: 34 }, {
            opacity: 1, y: 0, duration: 0.85, ease: "power3.out", delay,
            scrollTrigger: { trigger: el, start: "top 86%" },
        });
    });

    /* ---- Naslovi sekcija: blagi "rise" ---- */
    gsap.utils.toArray(".section-title, .fleet__title, .offer__title, .faq-section__title, .footer__cta h2").forEach((el) => {
        gsap.fromTo(el, { opacity: 0, y: 40 }, {
            opacity: 1, y: 0, duration: 0.9, ease: "power4.out",
            scrollTrigger: { trigger: el, start: "top 88%" },
        });
    });

    // Posle učitavanja fontova/slika preračunaj pozicije trigera.
    window.addEventListener("load", () => ScrollTrigger.refresh());
})();
