document.addEventListener("DOMContentLoaded", function () {

    // Untuk form dengan ID (single form)
    function setupLoading(formId, btnId, loadingText = null) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener("submit", function () {
            const btn = document.getElementById(btnId);
            if (!btn) return;

            btn.classList.add("loading");
            btn.disabled = true;

            if (loadingText) {
                const text = btn.querySelector(".btn-text");
                if (text) text.textContent = loadingText;
            }
        });
    }

    // Untuk form banyak (pakai class / looping)
    function setupLoadingByClass(formClass, btnClass, loadingText = null) {
        const forms = document.querySelectorAll("." + formClass);

        forms.forEach(function (form) {
            form.addEventListener("submit", function () {
                const btn = form.querySelector("." + btnClass);
                if (!btn) return;

                btn.classList.add("loading");
                btn.disabled = true;

                if (loadingText) {
                    const text = btn.querySelector(".btn-text");
                    if (text) text.textContent = loadingText;
                }
            });
        });
    }

    // Panggil
    setupLoading("formGeneral", "btnGeneral");
    setupLoading("formGeneralMaster", "btnMaster", "Menyimpan data...");
    setupLoadingByClass("form-delete", "btn-general-delete", "Menghapus data...");
    setupLoadingByClass("form-general", "btn-general");
    setupLoadingByClass("form-transaction", "btn-general", "Memproses pesanan...");
    setupLoadingByClass("form-redeem", "btn-general", "Sedang memproses...");
});