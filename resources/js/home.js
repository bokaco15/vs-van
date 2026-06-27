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

    /* -----------------------  Filter vozila  ----------------------- */
    const filter = document.querySelector(".filter");
    if (filter) {
        const buttons = Array.from(filter.querySelectorAll(".filter__btn"));
        const indicator = filter.querySelector(".filter__indicator");
        const firstBtn = buttons[0];
        const cards = Array.from(document.querySelectorAll(".van-item[data-type]"));
        const empty = document.querySelector(".fleet__empty");

        const moveIndicator = (btn) => {
            if (!indicator || !firstBtn) return;
            indicator.style.width = btn.offsetWidth + "px";
            indicator.style.transform = "translateX(" + (btn.offsetLeft - firstBtn.offsetLeft) + "px)";
        };

        const applyFilter = (type) => {
            const visible = [];
            cards.forEach((card) => {
                const match = type === "all" || card.dataset.type === type;
                card.classList.toggle("is-hidden", !match);
                if (match) visible.push(card);
            });
            if (empty) empty.classList.toggle("is-visible", visible.length === 0);
            if (useGsap && visible.length) {
                gsap.fromTo(visible, { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.06, ease: "power3.out", overwrite: true });
            }
        };

        buttons.forEach((btn) => {
            btn.addEventListener("click", () => {
                buttons.forEach((b) => b.classList.remove("is-active"));
                btn.classList.add("is-active");
                moveIndicator(btn);
                applyFilter(btn.dataset.filter);
            });
        });

        const active = filter.querySelector(".filter__btn.is-active") || firstBtn;
        if (active) {
            requestAnimationFrame(() => moveIndicator(active));
            window.addEventListener("load", () => moveIndicator(active));
        }
        window.addEventListener("resize", () => {
            const cur = filter.querySelector(".filter__btn.is-active");
            if (cur) moveIndicator(cur);
        });
    }

    /* =====================  GSAP ANIMACIJE  ===================== */
    if (!useGsap) {
        revealEverything();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    // Inicijalno sakrij animirane elemente preko GSAP-a (inline) pa skini
    // .gsap-armed klasu — tako nema "blica" pre nego što timeline krene.
    gsap.set("[data-hero]", { opacity: 0 });
    gsap.set(".van-item", { opacity: 0, y: 40 });
    document.body.classList.remove("gsap-armed");

    // Suptilan "zoom-out" pozadine na učitavanju (kinematografski ulaz)
    gsap.fromTo(".hero__bg", { scale: 1.18 }, { scale: 1.08, duration: 1.6, ease: "power2.out" });

    /* ---- Hero intro timeline ---- */
    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });
    tl.fromTo('[data-hero="eyebrow"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 })
      .fromTo(".hero__title .line", { opacity: 0, yPercent: 120 }, { opacity: 1, yPercent: 0, duration: 0.95, stagger: 0.1 }, "-=0.2")
      .fromTo('[data-hero="lead"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.55")
      .fromTo('[data-hero="actions"]', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.4")
      .fromTo('[data-hero="stats"]', { opacity: 0, y: 26 }, { opacity: 1, y: 0, duration: 0.6 }, "-=0.4")
      .fromTo('[data-hero="scroll"]', { opacity: 0 }, { opacity: 1, duration: 0.6 }, "-=0.3");

    /* ---- Parallax pozadine dok se skroluje hero ---- */
    const hero = document.querySelector(".hero");
    if (hero) {
        gsap.to(".hero__bg", { yPercent: 18, ease: "none", scrollTrigger: { trigger: hero, start: "top top", end: "bottom top", scrub: true } });
        gsap.to('[data-hero="scroll"]', { opacity: 0, ease: "none", scrollTrigger: { trigger: hero, start: "top top", end: "20% top", scrub: true } });
    }

    /* ---- Scroll-reveal sekcija ---- */
    gsap.utils.toArray(".reveal").forEach((el) => {
        const delay = parseFloat(el.dataset.delay || 0) * 0.08;
        gsap.fromTo(el, { opacity: 0, y: 34 }, {
            opacity: 1, y: 0, duration: 0.85, ease: "power3.out", delay,
            scrollTrigger: { trigger: el, start: "top 86%" },
        });
    });

    /* ---- Kartice vozila — staggered reveal po redovima ---- */
    ScrollTrigger.batch(".van-item", {
        start: "top 90%",
        onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, duration: 0.7, stagger: 0.09, ease: "power3.out", overwrite: true }),
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
