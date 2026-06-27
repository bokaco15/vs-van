// Kalendar zauzetosti — BOOKING stil: bira se OPSEG datuma (dolazak → povratak).
// Izvor podataka je Laravel JSON endpoint (busy dani po vozilu).
// Zadržan DOM kontrakt originala: .kalendar, .dani, .mesec h1, .prev/.next,
// .termini, .van-item[data-van]. Dodato: izbor opsega, hover-preview, validacija
// (opseg ne sme da pređe zauzet/prošli dan) i "Rezerviši" dugme -> WhatsApp.

let monthsName = ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"]

let prevBtn = document.querySelector(".prev")
let nextBtn = document.querySelector(".next")
let displayMonthYear = document.querySelector(".mesec h1")
let daysContainer = document.querySelector(".dani")
let kalendar = document.querySelector(".kalendar")
let infoEl = document.querySelector(".kalendar-info")
let reserveBtn = document.querySelector(".kalendar-reserve")

let currentDate = new Date()
let currentMonth = currentDate.getMonth()
let currentYear = currentDate.getFullYear()

let min_year = parseInt(currentYear)
let min_month = parseInt(currentMonth)
let max_year = parseInt(currentYear + 1)
let max_month = parseInt(currentMonth - 1)

let termini = document.querySelectorAll(".termini")
let selectedVan = null
let activeVan = null

// Izabran opseg (timestamp-ovi ponoći) — start i opcioni end
let rangeStart = null
let rangeEnd = null

// Keš rezervacija po vozilu: { [vehicleId]: { "YYYY-MM": [dani] } }
let reservationsByVehicle = {}

/* -------------------- pomoćne -------------------- */
function pad(n) { return String(n).padStart(2, "0") }
function dayTs(y, m, d) { let dt = new Date(y, m, d); dt.setHours(0, 0, 0, 0); return dt.getTime() }
function todayTs() { let t = new Date(); t.setHours(0, 0, 0, 0); return t.getTime() }
function fmtTs(ts) { let d = new Date(ts); return `${pad(d.getDate())}.${pad(d.getMonth() + 1)}.${d.getFullYear()}.` }
function nightsBetween(a, b) { return Math.round((Math.max(a, b) - Math.min(a, b)) / 86400000) }

// Da li je dan zauzet (prošli datum ILI rezervisan u bazi)
function isBooked(y, m, d) {
    if (dayTs(y, m, d) < todayTs()) return true
    let key = `${y}-${pad(m + 1)}`
    let arr = (reservationsByVehicle[selectedVan] || {})[key] || []
    return arr.includes(d)
}

// Da li opseg (inkluzivno) sadrži neki zauzet dan
function rangeHasBooked(aTs, bTs) {
    let start = Math.min(aTs, bTs), end = Math.max(aTs, bTs)
    for (let dt = new Date(start); dt.getTime() <= end; dt.setDate(dt.getDate() + 1)) {
        if (isBooked(dt.getFullYear(), dt.getMonth(), dt.getDate())) return true
    }
    return false
}

/* -------------------- učitavanje rezervacija -------------------- */
function fetchReservations(vehicleId) {
    if (reservationsByVehicle[vehicleId]) {
        return Promise.resolve(reservationsByVehicle[vehicleId])
    }
    let template = (window.APP_CONFIG && window.APP_CONFIG.reservationsUrlTemplate) || "/api/vehicles/:id/reservations"
    let url = template.replace(":id", vehicleId) + "?t=" + new Date().getTime()

    return fetch(url)
        .then((response) => {
            if (response.status !== 200) throw Error("Fetch problem!")
            return response.json()
        })
        .then((data) => { reservationsByVehicle[vehicleId] = data; return data })
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

function resetSelection() {
    rangeStart = null
    rangeEnd = null
    updateFooter()
}

/* -------------------- izbor datuma -------------------- */
function selectDay(ts) {
    if (rangeStart === null || rangeEnd !== null) {
        // Počni novi izbor
        rangeStart = ts
        rangeEnd = null
    } else if (ts === rangeStart) {
        // Klik na isti dan -> poništi
        rangeStart = null
        rangeEnd = null
    } else {
        let a = Math.min(ts, rangeStart)
        let b = Math.max(ts, rangeStart)
        if (rangeHasBooked(a, b)) {
            // Opseg prelazi zauzet termin -> započni novi izbor od ovog dana
            rangeStart = ts
            rangeEnd = null
            flashWarn("U izabranom opsegu ima zauzet termin — probaj kraći period.")
        } else {
            rangeStart = a
            rangeEnd = b
        }
    }
    updateCalendar()
    updateFooter()
}

let warnTimer = null
function flashWarn(msg) {
    if (!infoEl) return
    infoEl.textContent = msg
    infoEl.classList.add("is-warn")
    clearTimeout(warnTimer)
    warnTimer = setTimeout(() => { infoEl.classList.remove("is-warn"); updateFooter() }, 2600)
}

function updateFooter() {
    if (!infoEl || !reserveBtn) return
    if (infoEl.classList.contains("is-warn")) return // ne gazi upozorenje
    if (rangeStart === null) {
        infoEl.textContent = "Izaberi datum dolaska i povratka"
        reserveBtn.disabled = true
        reserveBtn.textContent = "Rezerviši"
    } else if (rangeEnd === null) {
        infoEl.innerHTML = `Dolazak: <strong>${fmtTs(rangeStart)}</strong> · izaberi povratak`
        reserveBtn.disabled = false
        reserveBtn.textContent = "Rezerviši ovaj dan"
    } else {
        let n = nightsBetween(rangeStart, rangeEnd)
        infoEl.innerHTML = `<strong>${fmtTs(rangeStart)}</strong> → <strong>${fmtTs(rangeEnd)}</strong> · ${n} ${n === 1 ? "noć" : "noći"}`
        reserveBtn.disabled = false
        reserveBtn.textContent = "Rezerviši termin"
    }
}

/* -------------------- hover preview opsega -------------------- */
function paintPreview(hoverTs) {
    if (rangeStart === null || rangeEnd !== null) return
    let a = Math.min(hoverTs, rangeStart), b = Math.max(hoverTs, rangeStart)
    daysContainer.querySelectorAll(".day").forEach((cell) => {
        let ts = parseInt(cell.dataset.ts)
        cell.classList.toggle("preview", ts > a && ts < b)
    })
}
function clearPreview() {
    daysContainer.querySelectorAll(".day.preview").forEach((c) => c.classList.remove("preview"))
}

/* -------------------- render meseca -------------------- */
function updateCalendar() {
    displayMonthYear.innerHTML = `${monthsName[currentMonth]} ${currentYear}`
    daysContainer.innerHTML = " "

    let firstDay = new Date(currentYear, currentMonth, 1).getDay()
    let numberOfDays = 32 - new Date(currentYear, currentMonth, 32).getDate()
    let counterOfEmptyCells = firstDay === 0 ? 6 : firstDay - 1

    for (let i = 0; i < counterOfEmptyCells; i++) {
        let emptyCell = document.createElement("div")
        emptyCell.className = "emptyCell"
        daysContainer.appendChild(emptyCell)
    }

    for (let day = 1; day <= numberOfDays; day++) {
        let ts = dayTs(currentYear, currentMonth, day)
        let dayCell = document.createElement("div")
        dayCell.className = "day"
        dayCell.innerHTML = day
        dayCell.dataset.ts = ts

        if (isBooked(currentYear, currentMonth, day)) {
            dayCell.classList.add("booked")
        } else {
            // Označi izbor
            if (rangeStart !== null && ts === rangeStart) dayCell.classList.add("sel-start", "selected")
            if (rangeEnd !== null && ts === rangeEnd) dayCell.classList.add("sel-end", "selected")
            if (rangeStart !== null && rangeEnd !== null && ts > rangeStart && ts < rangeEnd) dayCell.classList.add("in-range")

            dayCell.addEventListener("click", () => selectDay(ts))
            dayCell.addEventListener("mouseenter", () => paintPreview(ts))
        }

        daysContainer.appendChild(dayCell)
    }
}

if (daysContainer) daysContainer.addEventListener("mouseleave", clearPreview)

/* -------------------- "Rezerviši" -> WhatsApp -------------------- */
if (reserveBtn) {
    reserveBtn.addEventListener("click", () => {
        if (rangeStart === null) return

        let vanName = ""
        let vanItem = kalendar.closest(".van-item")
        if (vanItem) {
            let h = vanItem.querySelector("h4.van-name")
            if (h) vanName = h.innerText
        }

        let phoneNumber = (window.APP_CONFIG && window.APP_CONFIG.whatsappPhone) || "+381652113423"
        let message
        if (rangeEnd === null) {
            message = `Pozdrav, želim da rezervišem ${vanName} za datum ${fmtTs(rangeStart)}`
        } else {
            let n = nightsBetween(rangeStart, rangeEnd)
            message = `Pozdrav, želim da rezervišem ${vanName} od ${fmtTs(rangeStart)} do ${fmtTs(rangeEnd)} (${n} ${n === 1 ? "noć" : "noći"})`
        }
        let whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`
        window.open(whatsappUrl, "_blank")
    })
}

/* -------------------- navigacija meseci -------------------- */
prevBtn.addEventListener("click", () => {
    if (currentYear > min_year || (currentYear === min_year && currentMonth > min_month)) {
        currentMonth--
        if (currentMonth < 0) { currentMonth = 11; currentYear-- }
        updateCalendar()
    }
})
nextBtn.addEventListener("click", () => {
    if (currentYear < max_year || (currentYear === max_year && currentMonth < max_month)) {
        currentMonth++
        if (currentMonth > 11) { currentMonth = 0; currentYear++ }
        updateCalendar()
    }
})

/* -------------------- "Pogledaj termine" po vozilu -------------------- */
for (let termin of termini) {
    termin.addEventListener("click", (e) => {
        let eParent = e.target.closest(".van-item")
        let vanId = eParent.dataset.van
        let vans = document.querySelectorAll(".van-item")

        if (activeVan === vanId) {
            // Zatvori kalendar ako je isto vozilo kliknuto opet
            kalendar.style.display = "none"
            let img = eParent.querySelector("img")
            if (img) img.style.display = "block"
            activeVan = null
        } else {
            for (let van of vans) {
                let img = van.querySelector("img")
                if (van.dataset.van === vanId) {
                    if (img) img.style.display = "none"
                    let wrapper = van.querySelector(".van-item-photo")
                    wrapper.appendChild(kalendar)
                    selectedVan = vanId
                    resetCalendar()
                    resetSelection() // svež izbor po vozilu
                    fetchReservations(vanId).then(() => {
                        kalendar.style.display = "block"
                        updateCalendar()
                        updateFooter()
                    })
                    activeVan = vanId
                } else {
                    if (img) img.style.display = "block"
                }
            }
        }
    })
}
