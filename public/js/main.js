/* ========================================================
   Main JavaScript Helper - Aplikasi Parkir MVC
   ======================================================== */

document.addEventListener('DOMContentLoaded', function () {
    // Auto-focus on plate number or ticket input fields
    const autoFocusElem = document.querySelector('.auto-focus');
    if (autoFocusElem) {
        autoFocusElem.focus();
    }

    // Dynamic cash payment change calculator
    const inputTarif = document.getElementById('inputTarif');
    const inputBayar = document.getElementById('inputBayar');
    const displayKembalian = document.getElementById('displayKembalian');
    const btnProsesKeluar = document.getElementById('btnProsesKeluar');

    if (inputTarif && inputBayar && displayKembalian) {
        function calculateChange() {
            const tarif = parseInt(inputTarif.value) || 0;
            const bayar = parseInt(inputBayar.value) || 0;
            const kembalian = bayar - tarif;

            if (bayar > 0 && kembalian >= 0) {
                displayKembalian.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
                displayKembalian.className = 'fw-bold text-success fs-5';
                if (btnProsesKeluar) btnProsesKeluar.disabled = false;
            } else if (bayar > 0 && kembalian < 0) {
                displayKembalian.textContent = 'Kurang Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(kembalian));
                displayKembalian.className = 'fw-bold text-danger fs-5';
                if (btnProsesKeluar) btnProsesKeluar.disabled = true;
            } else {
                displayKembalian.textContent = 'Rp 0';
                displayKembalian.className = 'fw-bold text-muted fs-5';
                if (btnProsesKeluar) btnProsesKeluar.disabled = (tarif > 0);
            }
        }

        inputBayar.addEventListener('input', calculateChange);
    }
});

// Quick Print Helper
function printElement() {
    window.print();
}
