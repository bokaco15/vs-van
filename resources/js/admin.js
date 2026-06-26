/* ==========================================================================
   Zajednička admin logika
   - CSRF token za sve jQuery AJAX zahteve (čita <meta name="csrf-token">)
   - Globalna Toastr podešavanja
   - Helperi: toast poruke + mapiranje Laravel 422 validacionih grešaka
   - Sidebar toggle na mobilnom
   ========================================================================== */

// 1) CSRF za sve AJAX pozive — Laravel zahteva X-CSRF-TOKEN header
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (window.jQuery && csrfToken) {
    jQuery.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrfToken },
    });
}

// 2) Toastr podrazumevana podešavanja
if (window.toastr) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 4000,
    };
}

// 3) Helperi dostupni globalno (window.Admin)
window.Admin = {
    /** Prikaži uspešnu poruku iz JSON odgovora { message } */
    success(message) {
        if (window.toastr) toastr.success(message || 'Uspešno sačuvano.');
    },

    /** Prikaži grešku (server 5xx ili opšta) */
    error(message) {
        if (window.toastr) toastr.error(message || 'Došlo je do greške.');
    },

    /**
     * Obradi neuspeo AJAX odgovor.
     * - 422: ispiši svaku validacionu poruku iz Laravel { errors: { field: [msg] } }
     * - ostalo: prikaži opštu/serversku poruku
     */
    handleAjaxError(xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors || {};
            Object.values(errors).forEach((messages) => {
                (Array.isArray(messages) ? messages : [messages]).forEach((msg) =>
                    this.error(msg)
                );
            });
        } else {
            this.error(xhr.responseJSON?.message || 'Neočekivana greška na serveru.');
        }
    },
};

// 4) Reload svih DataTables na stranici (posle izmene/brisanja)
window.Admin.reloadTables = function () {
    if (window.jQuery && jQuery.fn.dataTable) {
        jQuery('.dataTable').each(function () {
            if (jQuery.fn.dataTable.isDataTable(this)) {
                jQuery(this).DataTable().ajax.reload(null, false);
            }
        });
    }
};

/**
 * Pošalji formu preko AJAX-a (podržava i fajlove preko FormData).
 * @param {JQuery} $form
 * @param {object} opts { url, method, onSuccess }
 */
window.Admin.submitForm = function ($form, opts = {}) {
    const url = opts.url || $form.attr('action');
    const method = (opts.method || $form.attr('method') || 'POST').toUpperCase();
    const formData = new FormData($form[0]);

    // Laravel method spoofing za PUT/PATCH/DELETE preko POST + _method
    let ajaxMethod = method;
    if (['PUT', 'PATCH', 'DELETE'].includes(method)) {
        formData.append('_method', method);
        ajaxMethod = 'POST';
    }

    return jQuery.ajax({
        url: url,
        method: ajaxMethod,
        data: formData,
        processData: false,
        contentType: false,
    })
        .done((res) => {
            window.Admin.success(res.message);
            if (typeof opts.onSuccess === 'function') opts.onSuccess(res);
        })
        .fail((xhr) => window.Admin.handleAjaxError(xhr));
};

/**
 * Uključi drag-and-drop redosled na <tbody> DataTables tabele.
 * Redovi moraju imati data-id; ručka je element sa klasom .js-drag.
 * Poziva se iz DataTables drawCallback (pošto se tbody iscrtava iznova).
 */
window.Admin.initSortableTable = function (tableEl, url) {
    const tbody = tableEl.querySelector('tbody');
    if (!tbody || !window.Sortable) return;
    if (tbody._sortable) tbody._sortable.destroy();

    tbody._sortable = window.Sortable.create(tbody, {
        handle: '.js-drag',
        animation: 150,
        onEnd: function () {
            const ids = Array.from(tbody.querySelectorAll('tr'))
                .map((tr) => tr.getAttribute('data-id'))
                .filter(Boolean);
            jQuery.ajax({ url, method: 'POST', data: { ids } })
                .done((res) => window.Admin.success(res.message))
                .fail((xhr) => window.Admin.handleAjaxError(xhr));
        },
    });
};

// 5) Delegirano brisanje: dugme .js-delete sa data-url (DELETE)
document.addEventListener('DOMContentLoaded', () => {
    jQuery(document).on('click', '.js-delete', function () {
        if (!confirm('Da li ste sigurni?')) return;
        const url = jQuery(this).data('url');
        jQuery.ajax({ url, method: 'POST', data: { _method: 'DELETE' } })
            .done((res) => {
                window.Admin.success(res.message);
                window.Admin.reloadTables();
            })
            .fail((xhr) => window.Admin.handleAjaxError(xhr));
    });

    // Sidebar toggle (mobilni prikaz)
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
});
