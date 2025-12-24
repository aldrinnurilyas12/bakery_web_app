
var btnClose = document.getElementById('closeBtn');
var showClose = document.querySelector('.close-dialog');

var btnShow = document.getElementById('btnShow');
var closedTransactionCard = document.querySelector('.transaction-card');


var openDialogOther = document.getElementById('openDialog');
var showDialog = document.querySelector('.open-dialog-clear-cart');
var closedDialog = document.getElementById('closedDialogBtn')

var btnOpenSegment = document.getElementById('openCustomerSegment');
var showItemCustomer = document.querySelector('.customer-segment')

btnClose.addEventListener('click', function () {
    showClose.style.display = 'flex';
    closedTransactionCard.style.display = 'none';
});

btnShow.addEventListener('click', function () {
    closedTransactionCard.style.display = 'block';

    showClose.style.display = 'none';
});

closedDialog.addEventListener('click', function (event) {
    event.preventDefault();

    showDialog.style.display = 'none';
})

btnOpenSegment.addEventListener('click', function (event) {
    event.preventDefault();

    if (showItemCustomer.style.display === 'block') {
        showItemCustomer.style.display = 'none';
    } else {
        showItemCustomer.style.display = 'block';
    }

});

openDialogOther.addEventListener('click', function (event) {
    event.preventDefault();
    if (showDialog.style.display === 'block') {
        showDialog.style.display = 'none';
    } else {
        showDialog.style.display = 'block';
    }



})

// script for calculation:


// Fungsi untuk format uang ke format "Rp."
function formatCurrency(amount) {
    return "Rp. " + amount.toLocaleString('id-ID');
}

// Mendapatkan nilai grandTotal dari input total
const grandTotalInput = parseFloat(document.querySelector('input[name="total_amount"]').value) || 0;
let grandTotal = grandTotalInput; // Grand total yang dihitung berdasarkan harga dan quantity produk

// Menghitung kembalian berdasarkan pembayaran
document.getElementById("amount").addEventListener("input", function () {
    let amountPaid = parseFloat(this.value.replace(/\D/g, '')); // Hapus semua non-digit

    if (isNaN(amountPaid)) {
        amountPaid = 0;
    }

    // Menampilkan jumlah yang dibayar
    document.getElementById("display-paychange").textContent = formatCurrency(amountPaid);

    // Menghitung kembalian
    let change = amountPaid - grandTotal;

    // Menampilkan kembalian
    document.getElementById("display-change").textContent = change >= 0 ? formatCurrency(change) : "Rp. 0";

    // Menampilkan kembalian di input field kembalian
    document.getElementById("paychange").value = change >= 0 ? change : 0;
});

// Ambil harga per item untuk setiap produk
const pricePerItems = []; // Array untuk harga per produk, dapat diisi dengan harga per produk dari server
document.querySelectorAll('.item-price').forEach((priceElement, index) => {
    pricePerItems[index] = parseFloat(priceElement.textContent.replace(/\D/g, '')) ||
        0; // Ambil harga per produk dari elemen
});





// SCRIPT FOR ACTIVE NAVBAR :
$('#nav-scroll .nav-link').on('click', function () {
    $('#nav-scroll .nav-link').removeClass('active');
    $(this).addClass('active');
});


