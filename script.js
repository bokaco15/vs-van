let btnOpenNav = $(".open-nav")
let btnCloseNav = $(".close-nav")
let navItems = $(".nav-items-wrapper")
let navInfoItems = document.querySelector(".nav-info .container .nav-info-wrapper")
let navContainer = document.querySelector(".nav-container")
let firstHeroColumn = document.querySelector(".section .container .first-hero-column")
let secondHeroColumn = document.querySelector(".second-hero-column")
let stickerSection = document.querySelector(".stickers")
let navItemsLinks = document.querySelectorAll(".nav-items a")

navContainer.style.opacity = "0"
navInfoItems.style.opacity = "0"
firstHeroColumn.style.opacity = "0"
secondHeroColumn.style.opacity = "0"
stickerSection.style.opacity = "0"


// Inicijalni opacitet može biti kontrolisan u CSS-u umesto JavaScript-a

function openNavbar() {
    navItems.fadeIn(300)
    $("body").css("overflow", "hidden")
}

function closeNavbar() {
    navItems.fadeOut(300)
    $("body").css("overflow", "auto")
}

for(let navItemLink of navItemsLinks) {
    navItemLink.addEventListener("click", (e) => {
        document.body.style = "overflow: auto"
        //e.target.parentNode.parentNode.style = "display: none"
        document.querySelector(".nav-items-wrapper").style = "display: none"
        if(e.target.innerHTML === "Pocetna") {
            e.target.href = "#"
        } else if(e.target.innerHTML === "Nasa ponuda") {
            e.target.href = "#nasa-ponuda"
        } else if(e.target.innerHTML === "Faq") {
            e.target.href = "#faq-section"
        } else if(e.target.innerHTML === "Kontakt") {
            e.target.href = "#footer-section"
        }
    })
}

btnOpenNav.on("click", openNavbar)
btnCloseNav.on("click", closeNavbar)

/////////////////////////////////////////////////////////

function navBarAnimation() {
    navContainer.classList.add("fadeInAnimate")
}

function responsiveNavAnimate() {
    responsiveNav.classList.add("fadeInAnimate")
}

function navItemsAnimation() {
    navInfoItems.classList.add("fadeInAnimate")
}

function heroMoveRightAnimation() {
    firstHeroColumn.classList.add("moving-right")
}

function heroMoveLeftAnimation() {
    secondHeroColumn.classList.add("moving-left")
}

function stickerSectionAnimate() {
    stickerSection.classList.add("fadeInAnimate")
    
}

setTimeout(navBarAnimation, 200)
setTimeout(navItemsAnimation, 500)
setTimeout(heroMoveRightAnimation, 900)
setTimeout(heroMoveLeftAnimation, 900)
setTimeout(stickerSectionAnimate, 1200)



///////////////////////////////////////////////
$(".item-wrapper1").on("click", function() {
    $(".item-wrapper2").slideUp(300)

    $(".x").css({
        "transform": "rotate(0deg)",
        "stroke-opacity": "0.6",
        "transition": "300ms"
    })
    $(".ss-item-heading").css("color", "rgba(69, 90, 100, 0.5)")
    $(".item-logo").css("background", "rgba(207, 216, 220, 0.4)")
    $(".item-wrapper1").css("align-items", "center")

    
    let itemDescription = $(this).find(".item-wrapper2")
    
    if (!itemDescription.is(":visible")) {
        itemDescription.slideDown(300)

        let itemwrapper1 = $(this).closest(".item-wrapper1")
        itemwrapper1.css("align-items", "flex-start")

        $(this).find(".x").css({
            "transform": "rotate(45deg)",
            "stroke-opacity": "1",
            "transition": "300ms"
        })

        $(this).find(".ss-item-heading").css({
            "color": "rgba(69, 90, 100)",
            "transition": "300ms"
        })

        $(this).find(".item-logo").css({
            "background": "#ECEFF1",
            "transition": "300ms"
        })
    }
})



//faq
$(".question").on("click", function(){
    let answer = $(this).next($(".answer"))
    let rotate = $(this).find($(".fa-solid"))

    if(answer.is(":visible")){
        answer.slideUp(300)
        rotate.css({
            "rotate": "0deg",
            "transition": "100ms"
        })
        $(this).css({
            "background-color": "rgba(120, 144, 156, 0.6)",  // Resetuje boju pozadine
            "transition": "300ms"
        })
    }else{
        $(".answer").slideUp(300)
        $(".fa-solid").css({
            "rotate": "0deg",
            "transition": "100ms"
        })
        $(".question").css("background-color", "rgba(120, 144, 156, 0.6)")
        $(this).css({
            "background-color": "rgba(120, 144, 156)",
            "transition": "300ms"
        })
        answer.slideDown(300)
        rotate.css("rotate", "180deg")
    }

})




////////////////////////////////////////////////////////////

function secondSectionScrollFadeIn() {
    let secondSection = document.querySelector(".second-section")
    let secondSection_columns = document.querySelector(".columns")
    secondSection_columns.style.opacity = 0
    
    window.addEventListener("scroll", () => {
        let secondSection_position = secondSection.getBoundingClientRect().top
        let windowHeight = window.innerHeight
    
        if (windowHeight > secondSection_position + 50) {
            setTimeout(() => {
                secondSection_columns.classList.add("fadeInAnimate")
                secondSection_columns.style.position = "relative"
                secondSection_columns.style.zIndex = "1"
            }, 200)
        }
    })
}

secondSectionScrollFadeIn()


////////////////////////////////////////////////////////////////


function vanItemsAndHeadingScroll() {
    const vanItems = document.querySelectorAll(".van-item");
    const offerHeading = document.querySelector(".offer-heading");

    offerHeading.style.opacity = "0";

    window.addEventListener("scroll", () => {
        for (let vanItem of vanItems) {
            const rect = vanItem.getBoundingClientRect();
            const vanItemHeight = rect.height;

            const vanItemVisibleHeight = (rect.top >= 0 && rect.top < window.innerHeight) || 
                                         (rect.bottom > 0 && rect.bottom <= window.innerHeight);

            if (vanItemVisibleHeight) {
                const visiblePortion = (window.innerHeight - rect.top) / vanItemHeight;

                if (visiblePortion > 0.3) {
                    vanItem.classList.add("show");
                }
            }
        }

        const headingRect = offerHeading.getBoundingClientRect();
        const headingHeight = headingRect.height;
        const headingVisiblePortion = (window.innerHeight - headingRect.top) / headingHeight;

        if (headingVisiblePortion >= 0.3) {
            offerHeading.style.opacity = "1";
            offerHeading.style.transition = "opacity 0.5s ease";
        }
    });

    window.dispatchEvent(new Event('scroll'));
}

vanItemsAndHeadingScroll();



////////////////////////////////////////////////////////////////////


function faqScroll() {
    const faqs = document.querySelectorAll(".faq");
    const faqHeading = document.querySelector(".faq-heading");

    faqHeading.style.opacity = "0";

    function handleScroll() {
        for (let faq of faqs) {
            const rect = faq.getBoundingClientRect();
            const faqHeight = rect.height;

            const faqVisibleHeight = (rect.top >= 0 && rect.top < window.innerHeight);
            const visiblePortion = (window.innerHeight - rect.top) / faqHeight;

            if (faqVisibleHeight && visiblePortion > 0.1) {
                faq.classList.add("show");
            }
        }

        const headingRect = faqHeading.getBoundingClientRect();
        const headingHeight = headingRect.height;
        const headingVisiblePortion = (window.innerHeight - headingRect.top) / headingHeight;

        if (headingVisiblePortion >= 0.2) {
            faqHeading.style.opacity = "1";
            faqHeading.style.transition = "opacity 2s ease";
        }
    }

    window.addEventListener("scroll", handleScroll);

    handleScroll();
}


faqScroll();
////////////////////////////////////////////////////////////////


let btnsIznajmi = document.querySelectorAll(".iznajmi-kombi a")

for(let btnIznajmi of btnsIznajmi) {
    btnIznajmi.addEventListener("click", (e) => {
        e.target.href = "tel:+381652113423"
    })
}

