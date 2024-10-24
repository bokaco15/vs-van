let monthsName = ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"]

let prevBtn = document.querySelector(".prev")
let nextBtn = document.querySelector(".next")
let displayMonthYear = document.querySelector(".mesec h1")
let daysContainer = document.querySelector(".dani")

let currentDate = new Date()
let currentMonth = currentDate.getMonth()
let currentYear = currentDate.getFullYear()

let min_year = parseInt(currentYear)
let min_month = parseInt(currentMonth)

let max_year = parseInt(currentYear + 1)
let max_month = parseInt(currentMonth - 1)

let termini = document.querySelectorAll(".termini")
let kalendar = document.querySelector(".kalendar")

let selectedVan = "van1"
let activeVan = null

let reservations = {}

// Fetch reservations from JSON file
fetch("reservations.json?t=" + new Date().getTime())
    .then((response) => {
        if(response.status !== 200) {
            throw Error("Fetch problem!")
        }
        return response.json()
    })
    .then((data) => {
        reservations = data
        updateCalendar()
    })
    .catch((err) => {
        console.log("Greska pri ucitavanju rezervacije: " + err)
    })


function resetCalendar() {
    currentMonth = currentDate.getMonth()
    currentYear = currentDate.getFullYear()
}

function updateCalendar() {
    displayMonthYear.innerHTML = `${monthsName[currentMonth]} ${currentYear}`
    daysContainer.innerHTML = " "

    let firstDay = new Date(currentYear, currentMonth, 1).getDay()
    let numberOfDays = 32 - new Date(currentYear, currentMonth, 32).getDate()

    let counterOfEmptyCells
    if(firstDay === 0) {
        counterOfEmptyCells = 6
    } else {
        counterOfEmptyCells = firstDay - 1
    }

    for (let i = 0; i < counterOfEmptyCells; i++) {
        let emptyCell = document.createElement("div")
        emptyCell.classList = "emptyCell"
        daysContainer.appendChild(emptyCell)
    }

    for (let day = 1; day <= numberOfDays; day++) {
        let dayCell = document.createElement("div")
        dayCell.classList = "day"
        dayCell.innerHTML = day

        let monthName = monthsName[currentMonth].toLowerCase()
        

        // Provera da li je dan prošao
        if (currentYear === currentDate.getFullYear() && currentMonth === currentDate.getMonth() && day < currentDate.getDate()) {
            dayCell.classList.add("booked")
        }

        // Provera da li je dan rezervisan
        if (reservations[selectedVan] && reservations[selectedVan][monthName] && reservations[selectedVan][monthName].includes(day)) {
            dayCell.classList.add("booked")
        }

        daysContainer.appendChild(dayCell)
    }

    let freeDays = document.querySelectorAll(".day:not(.booked)")
    for (let freeDay of freeDays) {
        freeDay.addEventListener("click", (e) => {
            let vanName = ""
            let vanItem = freeDay.closest(".van-item")
            if (vanItem) {
                let vanHeading = vanItem.querySelector("h4.van-name")
                if (vanHeading) {
                    vanName = vanHeading.innerText
                }
            }

            let phoneNumber = "+381652113423"
            let date = e.target.innerHTML // Dan koji je kliknut
            let month = monthsName[currentMonth] // Trenutni mesec
            let message = `Pozdrav, želim da rezervišem ${vanName} za datum ${date}. ${month}.`
            let encodedMessage = encodeURIComponent(message)
            let whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`

            window.open(whatsappUrl, "_blank")
        })
    }

    let bookedDays = document.querySelectorAll(".booked")
    for (let bookedDay of bookedDays) {
        bookedDay.addEventListener("click", (e) => {
            let date = e.target.innerHTML
            let month = monthsName[currentMonth]
            alert(`${date}. ${month} je zauzet termin. Molimo Vas odaberite slobodan termin`)
        })
    }
}

prevBtn.addEventListener("click", () => {
    if (currentYear > min_year || (currentYear === min_year && currentMonth > min_month)) {
        currentMonth--

        if (currentMonth < 0) {
            currentMonth = 11
            currentYear--
        }

        updateCalendar()
    }
})

nextBtn.addEventListener("click", () => {
    if (currentYear < max_year || (currentYear === max_year && currentMonth < max_month)) {
        currentMonth++

        if (currentMonth > 11) {
            currentMonth = 0
            currentYear++
        }

        updateCalendar()
    }
})

for(let termin of termini) {
    termin.addEventListener("click", (e) => {
        let eParent = e.target.closest(".van-item")
        let vanId = eParent.dataset.van
        let vans = document.querySelectorAll(".van-item")

        if (activeVan === vanId) {
            // Zatvori kalendar ako je isti kombi kliknut opet
            kalendar.style.display = "none"
            let img = eParent.querySelector("img")
            img.style.display = "block"
            activeVan = null
        } else {
            // Ako je novi kombi kliknut
            for(let van of vans) {
                let img = van.querySelector("img")
                if(van.dataset.van === vanId) {
                    img.style.display = "none"
                    let div_Wrapper = van.querySelector(".van-item-photo")
                    div_Wrapper.appendChild(kalendar)
                    kalendar.style.display = "block"
                    selectedVan = vanId
                    resetCalendar() // Resetuj na trenutni mesec
                    updateCalendar()
                    activeVan = vanId
                } else {
                    img.style.display = "block"
                }
            }
        }
    })
}
