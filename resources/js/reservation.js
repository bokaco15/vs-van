// Kalendar zauzetosti — izvor podataka je Laravel JSON endpoint (ne više statički JSON).
// Logika prikaza je zadržana iz originala; izmenjeno je samo:
//  1) podaci se učitavaju PO vozilu na klik "Pogledaj termine",
//  2) provera zauzetosti koristi pun ključ "YYYY-MM" (zbog prave godine),
//  3) WhatsApp broj se čita iz window.APP_CONFIG.

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

let selectedVan = null
let activeVan = null

// Keš rezervacija po vozilu: { [vehicleId]: { "YYYY-MM": [dani] } }
let reservationsByVehicle = {}

// Učitaj rezervacije za vozilo sa Laravel endpointa (jednom po vozilu).
function fetchReservations(vehicleId) {
    if (reservationsByVehicle[vehicleId]) {
        return Promise.resolve(reservationsByVehicle[vehicleId])
    }

    let template = (window.APP_CONFIG && window.APP_CONFIG.reservationsUrlTemplate) || "/api/vehicles/:id/reservations"
    let url = template.replace(":id", vehicleId) + "?t=" + new Date().getTime()

    return fetch(url)
        .then((response) => {
            if (response.status !== 200) {
                throw Error("Fetch problem!")
            }
            return response.json()
        })
        .then((data) => {
            reservationsByVehicle[vehicleId] = data
            return data
        })
        .catch((err) => {
            console.log("Greska pri ucitavanju rezervacije: " + err)
            reservationsByVehicle[vehicleId] = {}
            return {}
        })
}

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
    if (firstDay === 0) {
        counterOfEmptyCells = 6
    } else {
        counterOfEmptyCells = firstDay - 1
    }

    for (let i = 0; i < counterOfEmptyCells; i++) {
        let emptyCell = document.createElement("div")
        emptyCell.classList = "emptyCell"
        daysContainer.appendChild(emptyCell)
    }

    // Ključ meseca za izabrano vozilo (npr. "2026-03").
    let monthKey = `${currentYear}-${String(currentMonth + 1).padStart(2, "0")}`
    let vanData = reservationsByVehicle[selectedVan] || {}
    let bookedThisMonth = vanData[monthKey] || []

    for (let day = 1; day <= numberOfDays; day++) {
        let dayCell = document.createElement("div")
        dayCell.classList = "day"
        dayCell.innerHTML = day

        // Provera da li je dan prošao
        if (currentYear === currentDate.getFullYear() && currentMonth === currentDate.getMonth() && day < currentDate.getDate()) {
            dayCell.classList.add("booked")
        }

        // Provera da li je dan rezervisan
        if (bookedThisMonth.includes(day)) {
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

            let phoneNumber = (window.APP_CONFIG && window.APP_CONFIG.whatsappPhone) || "+381652113423"
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

for (let termin of termini) {
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
            for (let van of vans) {
                let img = van.querySelector("img")
                if (van.dataset.van === vanId) {
                    img.style.display = "none"
                    let div_Wrapper = van.querySelector(".van-item-photo")
                    div_Wrapper.appendChild(kalendar)
                    selectedVan = vanId
                    resetCalendar() // Resetuj na trenutni mesec
                    // Učitaj rezervacije za ovo vozilo, pa prikaži kalendar
                    fetchReservations(vanId).then(() => {
                        kalendar.style.display = "block"
                        updateCalendar()
                    })
                    activeVan = vanId
                } else {
                    img.style.display = "block"
                }
            }
        }
    })
}
