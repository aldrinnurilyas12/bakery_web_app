document.getElementById("redeemForm").addEventListener("submit", function () {
    const btn = document.getElementById("redeemBtn");
    btn.classList.add("loading");
    btn.disabled = true;
});