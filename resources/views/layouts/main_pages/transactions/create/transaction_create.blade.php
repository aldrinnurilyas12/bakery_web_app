<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Buat Transaksi</title>
    <link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/transaction_create.css') }}">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container">
                    <h4><strong>Sale</strong></h4>
                    <hr>

                    @if ($category_data)
                        <div class="main-container-content">
                            <div class="container-content">
                                <div class="tab-content" id="tab-content">
                                    <div class="filter-content">
                                        <div class="category-title">
                                            <h6>Kategori Produk</h6>
                                        </div>

                                        <div class="category-scroll">
                                            <ul class="nav nav-tabs" id="nav-scroll" role="tablist" style="gap: 10px;">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="tab-all-tab" data-bs-toggle="tab"
                                                        href="#tab-all" role="tab" aria-controls="tab-all"
                                                        aria-selected="true">Semua</a>
                                                </li>
                                                @foreach ($category_data as $ctg)
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link" id="tab-{{ $ctg->category_name }}-tab"
                                                            data-bs-toggle="tab" href="#tab-{{ $ctg->category_name }}"
                                                            role="tab" aria-controls="tab-{{ $ctg->category_name }}"
                                                            aria-selected="false">
                                                            {{ $ctg->category_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    </div>

                                    {{-- @php
                                        $voucherCustomer = DB::table('customer_vouchers as cv')
                                            ->where('voucher', $voucher_code)
                                            ->count();
                                        $voucher_quota = DB::table('voucher')
                                            ->where('voucher_code', $voucher_code)
                                            ->value('quota');

                                        $checkingQuotaVoucher = $voucherCustomer >= $voucher_quota;
                                        dd($checkingQuotaVoucher);
                                    @endphp --}}

                                    <hr>
                                    <div class="tab-pane active" id="tab-all" role="tabpanel"
                                        aria-labelledby="tab-all">
                                        <div class="card-body">
                                            <div class="tab-pane fade show active" id="tab-all" role="tabpanel"
                                                aria-labelledby="tab-all-tab">
                                                <div class="card-body">
                                                    <div class="content-product-show">
                                                        <div class="products-card"
                                                            style="display: flex; flex-wrap: wrap; gap: 20px;">

                                                            @foreach ($all_products as $product)
                                                                @php
                                                                    $products_images = DB::table('product_images')
                                                                        ->where('product_code', $product->product_code)
                                                                        ->first();
                                                                @endphp
                                                                <div style="position: left;" class="card"
                                                                    style="width: 200px;">
                                                                    @if ($product->product_code == $products_images->product_code)
                                                                        <img class="card-img"
                                                                            src="{{ asset('storage/' . $products_images->images) }}"
                                                                            alt="">
                                                                    @else
                                                                    @endif
                                                                    <p class="product-name">
                                                                        <strong>{{ $product->product }}</strong>
                                                                    </p>
                                                                    @if ($product->category)
                                                                        <small
                                                                            class="category">{{ $product->category }}</small>
                                                                    @else
                                                                    @endif

                                                                    @if ($product->price_after_discount)
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                            </p>
                                                                            <small
                                                                                class="discount">{{ '-' . $product->discount . '%' }}</small>
                                                                        </div>
                                                                    @else
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price) }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div class="stok">
                                                                        <p>Stok:
                                                                            {{ $product->stock_available }}
                                                                        </p>
                                                                        {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                    </div>
                                                                    <div class="btn-add-cart">
                                                                        <form action="{{ route('cart_add') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_code"
                                                                                value="{{ $product->product_code }}">
                                                                            <input type="hidden" name="product_name"
                                                                                value="{{ $product->product }}">
                                                                            @if ($product->discount)
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price_after_discount }}">
                                                                            @else
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price }}">
                                                                            @endif
                                                                            @if ($product->stock_available)
                                                                                <button class="btn-add-to-cart"
                                                                                    type="submit">Tambah</button>
                                                                            @else
                                                                                <button style="width:100%;"
                                                                                    class="btn btn-secondary"
                                                                                    type="button">Kosong</button>
                                                                            @endif
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>

                                    {{-- TAB PER CATEGORY --}}

                                    @if ($all_products->isNotEmpty())
                                        @foreach ($category_data as $ctg)
                                            @php
                                                $filtered_products = $all_products->where(
                                                    'category',
                                                    $ctg->category_name,
                                                );
                                            @endphp
                                            <div class="tab-pane fade" id="tab-{{ $ctg->category_name }}"
                                                role="tabpanel" aria-labelledby="tab-{{ $ctg->category_name }}-tab">
                                                <div class="card-body">
                                                    <div class="products-card"
                                                        style="display: flex; flex-wrap: wrap; gap: 20px;">
                                                        @if ($filtered_products->isNotEmpty())
                                                            @foreach ($filtered_products as $product)
                                                                @php
                                                                    $products_images = DB::table('product_images')
                                                                        ->where('product_code', $product->product_code)
                                                                        ->first();
                                                                @endphp
                                                                <div style="position: left;" class="card"
                                                                    style="width: 200px;">
                                                                    @if ($product->product_code == $products_images->product_code)
                                                                        <img class="card-img"
                                                                            src="{{ asset('storage/' . $products_images->images) }}"
                                                                            alt="">
                                                                    @else
                                                                    @endif
                                                                    <p><strong>{{ $product->product }}</strong>
                                                                    </p>
                                                                    <div class="price">
                                                                        <p>{{ 'Rp.' . number_format($product->price) }}
                                                                        </p>
                                                                    </div>

                                                                    @if ($product->category)
                                                                        <small
                                                                            class="category">{{ $product->category }}</small>
                                                                    @else
                                                                    @endif

                                                                    @if ($product->price_after_discount)
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                            </p>
                                                                            <small
                                                                                class="discount">{{ '-' . $product->discount . '%' }}</small>
                                                                        </div>
                                                                    @else
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price) }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div class="stok">
                                                                        <p>Stok:
                                                                            {{ $product->stock_available }}
                                                                        </p>
                                                                        {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                    </div>
                                                                    <div class="btn-add-cart">
                                                                        <form action="{{ route('cart_add') }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_code"
                                                                                value="{{ $product->product_code }}">
                                                                            <input type="hidden" name="product_name"
                                                                                value="{{ $product->product }}">
                                                                            @if ($product->discount)
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price_after_discount }}">
                                                                            @else
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price }}">
                                                                            @endif
                                                                            @if ($product->stock_available)
                                                                                <button class="btn-add-to-cart"
                                                                                    type="submit">Tambah</button>
                                                                            @else
                                                                                <button style="width:100%;"
                                                                                    class="btn btn-secondary"
                                                                                    type="button">Kosong</button>
                                                                            @endif
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p>Data tidak ada</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Data tidak ada</p>
                                    @endif

                                    <div class="pagination">
                                        {{ $all_products->links() }}
                                    </div>


                                </div>
                            </div>

                            {{-- transaction-card --}}
                            <div class="transaction-card">
                                <div class="title-action-close">
                                    <h6><strong>Keranjang Belanja</strong></h6>
                                    <div style="display: flex; gap:30px;" class="btn-action">
                                        {{-- <a style="color:black;" id="openDialog" href="#">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </a> --}}
                                        <a style="color:black;" href="#" data-toggle="modal"
                                            data-target="#showDeleteCart"><i class="fa fa-ellipsis-h"
                                                aria-hidden="true"></i></a>

                                        <a style="color:black;" id="closeBtn" href="#">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>

                                <hr>

                                <div wire:ignore class="modal fade" id="showDeleteCart" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Bersihkan Keranjang
                                                    Belanja
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Apakah anda yakin ingin membersihkan keranjang
                                                belanja ?</div>
                                            <div class="modal-footer">
                                                <form action="{{ route('clear_cart') }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn-clear-cart">Bersihkan
                                                        Keranjang</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Daftar barang di keranjang -->
                                <form action="{{ route('transaction.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="cart-items">
                                        @if ($cart_value)
                                            @foreach ($cart_value as $cart)
                                                <div class="cart-item">
                                                    <div style="display:flex; justify-content: space-between;"
                                                        class="container-content-product">

                                                        <!-- Product Details -->
                                                        <div class="sub-container-product">
                                                            <p class="item-name">{{ $cart['product_name'] }}</p>
                                                            <input name="product[]" type="hidden"
                                                                value="{{ $cart['product_code'] }}">

                                                            <!-- Product Price and Quantity -->
                                                            <div class="flex-content"
                                                                style="display: flex; justify-content: space-between;">
                                                                <p class="item-price">
                                                                    {{ 'Rp.' . number_format($cart['price']) }}
                                                                </p>
                                                            </div>


                                                        </div>


                                                        <div style="display: flex; gap:10px;"
                                                            class="btn-delete-product">

                                                            <button style="background: none;border:none;"
                                                                type="button" class="text-danger"
                                                                onclick="event.preventDefault();
                                                                document.getElementById('delete-{{ $cart['product_code'] }}').submit();">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <!-- Quantity Control -->
                                                            <div class="quantity-container">
                                                                <button type="button" class="decrease">-</button>
                                                                <input name="quantity_per_product[]" value="1"
                                                                    min="1" type="number"
                                                                    class="item-quantity">
                                                                <button type="button" class="increase">+</button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Quantity and Delete Section -->


                                                    <hr class="hr-cart">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center-cart">
                                                <h6>Kosong</h6>
                                            </div>

                                        @endif
                                    </div>
                                    @if ($cart_value)
                                        <hr>
                                        <div class="payment-method">
                                            <label for=""><strong>Metode Bayar</strong></label>
                                            <div class="open-pay-method">
                                                <select class="form-control" name="payment_type" id=""
                                                    required>
                                                    <option value="">=== Pilih Metode Bayar ===</option>
                                                    @foreach ($payment_type as $pay)
                                                        <option value="{{ $pay->id }}">
                                                            {{ $pay->payment_category }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <hr>

                                        <div id="form-voucher">
                                            <label for=""><strong>Masukan Kode Voucher</strong></label>
                                            <small>*Hapus kode voucher jika ingin hapus E-Voucher</small>
                                            <br>
                                            <input style="margin:0;" type="text" class="form-control"
                                                name="promo_code" placeholder="Masukan kode disini..."
                                                value="{{ old('promo_code') }}" id="promo_code_input">
                                            <br>

                                            <div class="btn-submit">
                                                <button id="btn-submit-check-result"
                                                    style="background-color: #212529;" class="btn btn-dark"
                                                    type="button">Pakai
                                                </button>

                                                <button id="btn-remove-voucher" class="btn btn-danger"
                                                    type="button">Hapus Voucher
                                                </button>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="category-scroll">
                                            <div class="customer-information">

                                                <!-- TAB CONTENT -->
                                                <div class="tab-content mt-3">
                                                    <small>*Harap isi jika pelanggan bagian dari Membership</small>
                                                    <div class="card-body">

                                                        <div class="customer-input">
                                                            <label><strong>Nama Pelanggan, Kode Pelanggan atau No.HP
                                                                    (Member Only)</strong></label>
                                                            <input id="search-customer" class="form-control"
                                                                type="text" autocomplete="off"
                                                                placeholder="Masukan Nama pelanggan, kode pelanggan atau no hp">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="showCustomer" class="show-customer">

                                                </div>
                                            </div>
                                        </div>


                                        <hr>
                                        <div class="payment-amount">
                                            <div style="margin-bottom: 10px;" class="amount">
                                                <label for=""><strong>Bayar</strong></label>
                                                <small>*Hanya berlaku untuk jenis pembayaran Cash/Tunai</small>
                                                <input name="total_amount" id="amount" class="form-control"
                                                    type="number" autocomplete="off">
                                            </div>

                                            <div class="payment-changes">
                                                <label for=""><strong>Kembalian</strong></label>
                                                <input id="paychange" name="payment_changes" class="form-control"
                                                    type="number" readonly>
                                            </div>
                                        </div>

                                        <hr>
                                        <p><strong>Informasi Pembayaran</strong></p>

                                        <!-- Subtotal dan Total -->
                                        <div class="totals">

                                            <hr>
                                            <div class="content-total">
                                                <span class="title-total">Bayar: </span>
                                                <span id="display-paychange"
                                                    class="paychange">{{ 'Rp.' }}</span>
                                            </div>
                                            <div class="content-total">
                                                <span class="title-total">Kembalian: </span>
                                                <span id="display-change"
                                                    class="paychange">{{ 'Rp.' }}</span>
                                            </div>
                                            <hr>
                                            <div class="content-total">
                                                <span class="title-total">Total items : </span>
                                                <span id="total-quantity">0</span>
                                                <input value="0" type="text" name="quantity"
                                                    id="total-quantity-result" hidden>

                                            </div>

                                            <div class="content-total">
                                                <span class="title-total">E-Voucher :</span>

                                                <div id="show-nominal" class="form-group">
                                                    <div class="info-first"></div>
                                                </div>
                                            </div>

                                            <div class="content-total">
                                                <span class="title-total">Potongan :</span>

                                                <div id="show-voucher-code" class="form-group">
                                                    <div class="info-second"></div>
                                                </div>
                                            </div>


                                            <div class="content-total">
                                                <span class="title-total">Grand Total: </span>
                                                <span class="subtotal" id="grandtotal">
                                                </span>
                                                <input value="0" type="text" name="grand_total"
                                                    id="total-input" hidden>
                                            </div>
                                        </div>

                                        <br>

                                        @if ($cart_value)
                                            <button style="width: 100%;" type="submit" class="btn btn-primary">Buat
                                                Pesanan</button>
                                        @else
                                            <button style="width: 100%;" type="button"
                                                class="btn btn-secondary">Buat
                                                Pesanan</button>
                                        @endif
                                </form>
                            @else
                    @endif


                    @foreach ($cart_value as $cart)
                        <form id="delete-{{ $cart['product_code'] }}"
                            action="{{ route('delete_item_cart', $cart['product_code']) }}" method="POST"
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>

                {{-- close dialog transaction card --}}
                <div class="close-dialog">
                    <h6><strong>Keranjang Belanja</strong></h6>
                    <a style="color: black;" id="btnShow" href="#">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </a>
                </div>


        </div>
        @endif
    </div>
    </main>
</body>
<script src="{{ asset('assets/front_end/js/main/transaction.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        function formatCurrency(amount) {
            return "Rp. " + amount.toLocaleString('id-ID');
        }

        const quantityInputs = document.querySelectorAll('.item-quantity');
        const increaseButtons = document.querySelectorAll('.increase');
        const decreaseButtons = document.querySelectorAll('.decrease');
        const priceElements = document.querySelectorAll('.item-price');

        const totalQuantitySpan = document.getElementById('total-quantity');
        const qtyResult = document.getElementById('total-quantity-result');

        const grandTotalSpan = document.getElementById('grandtotal');
        const totalResult = document.getElementById('total-input');

        const amountInput = document.getElementById('amount');
        const displayPayChange = document.getElementById('display-paychange');
        const displayChange = document.getElementById('display-change');
        const payChangeInput = document.getElementById('paychange');

        const voucherCodeResult = document.getElementById('info-first');

        function getPrice(index) {
            return parseFloat(
                priceElements[index].textContent.replace(/\D/g, '')
            ) || 0;
        }

        function updateTotalQuantity() {
            let totalQty = 0;
            quantityInputs.forEach(input => {
                totalQty += parseInt(input.value) || 0;
            });
            totalQuantitySpan.textContent = totalQty;
            qtyResult.value = totalQty;
        }

        function updateGrandTotal() {
            let total = 0;

            quantityInputs.forEach((input, index) => {
                const qty = parseInt(input.value) || 0;
                const price = getPrice(index);
                total += qty * price;
            });

            grandTotalSpan.textContent = formatCurrency(total);
            totalResult.value = total;

            updateChange();
        }

        function updateChange() {
            const paid = parseFloat(amountInput.value.replace(/\D/g, '')) || 0;
            const total = parseFloat(totalResult.value) || 0;
            const change = paid - total;

            displayPayChange.textContent = formatCurrency(paid);
            displayChange.textContent = change >= 0 ? formatCurrency(change) : "Rp. 0";
            payChangeInput.value = change >= 0 ? change : 0;
        }

        increaseButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                quantityInputs[index].value = (parseInt(quantityInputs[index].value) || 0) + 1;
                updateTotalQuantity();
                updateGrandTotal();
            });
        });

        decreaseButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                const current = parseInt(quantityInputs[index].value) || 0;
                if (current > 1) {
                    quantityInputs[index].value = current - 1;
                    updateTotalQuantity();
                    updateGrandTotal();
                }
            });
        });

        quantityInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (parseInt(input.value) < 1 || isNaN(input.value)) {
                    input.value = 1;
                }
                updateTotalQuantity();
                updateGrandTotal();
            });
        });

        amountInput.addEventListener('input', updateChange);

        // INIT
        updateTotalQuantity();
        updateGrandTotal();
    });

    // JS FOR SHOW VOUCHER :
    var btnCheckResult = document.getElementById('btn-submit-check');
    var showResult = document.getElementById('show-nominal');
    var inputPromoCode = document.getElementById('promo_code_input');
    const infoFirst = document.getElementById('info-first');




    // Meng-handle form submit
    $(document).ready(function() {
        var savedPromo = localStorage.getItem('promo_code');
        if (savedPromo) {
            $('#promo_code_input').val(savedPromo);
            $('#show-nominal .info-first').text(savedPromo);
        }
        // Meng-handle form submit
        $('#btn-submit-check-result').on('click', function(e) {
            e.preventDefault(); // Mencegah halaman reload

            // Ambil kode promo dari input form
            var promo_code = $('#promo_code_input').val();
            localStorage.setItem('promo_code', promo_code);

            var formData = $(this).serialize();
            $.ajax({
                url: '/show_promo_code', // URL untuk mengirim data
                type: 'GET',
                data: {
                    promo_code: promo_code
                },
                success: function(response) {
                    console.log('Response:', response);

                    if (response.status === 'success' && response.data) {
                        // Tampilkan div show-nominal
                        $('#show-nominal').show();

                        // Kosongkan konten lama
                        $('#show-nominal .info-first').empty();
                        $('#show-nominal .info-second').empty();

                        // Tampilkan diskon jika ada
                        if (response.data.discount) {
                            $('#show-nominal .info-first').append(
                                '<span class="badge-success">' + response
                                .data.voucher_code + '</span>'
                            );

                            $('#show-voucher-code .info-second').append(
                                '<span class="text-danger">-' + response
                                .data.discount + '%</span>'
                            );
                        }

                        // Tampilkan nominal jika ada
                        if (response.data.nominal) {
                            $('#show-nominal .info-first').append(
                                '<span class="badge-success">' + response
                                .data.voucher_code + '</span>'
                            );

                            $('#show-voucher-code .info-second').append(
                                '<span class="text-danger">' + response
                                .data.nominal + '</span>'
                            );
                        }

                    } else {
                        $('#show-nominal').hide();
                        alert(response.message || 'Kode promo tidak valid');
                    }
                },
                error: function(xhr, status, error) {
                    // Menangani error
                    $('#responseMessage').html('<p>Error: ' + error + '</p>');
                }
            });
        });


        // Tombol Hapus Voucher
        $('#btn-remove-voucher').on('click', function() {
            // Kosongkan input
            $('#promo_code_input').val('');

            // Hapus localStorage
            localStorage.removeItem('promo_code');

            // Kosongkan tampilan voucher
            $('#show-nominal .info-first').empty();
            $('#show-voucher-code .info-second').empty();

            // Sembunyikan section voucher (opsional)
            $('#show-nominal').hide();
        });
    });
    // end

    // ==============
    $(document).ready(function() {
        $('#search-customer').on('keyup', function() {
            let keyword = $(this).val();

            if (keyword.length < 2) {
                $('#showCustomer').html('');
                return;
            }

            $.ajax({
                url: '/search_customer',
                type: 'GET',
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(function(customer) {
                            if (customer.status == 7)
                                html += `
                             <div>
                            <strong>${customer.name} [${customer.customer_code}] &nbsp;  <input name="customer" value="${customer.customer_code}" type="checkbox">
                                Pilih</strong><br>
                            <small>Aktif</small>
                            `;
                            else
                                html += `
                             <div>
                            <strong>${customer.name} [${customer.customer_code}] &nbsp;
                               <span class="text-danger"> Tidak aktif</span></strong><br>
                            `;

                        });
                    } else {
                        html = '<div class="text-muted">Data tidak ditemukan</div>';
                    }

                    $('#showCustomer').html(html);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>


@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 1000,
            showConfirmButton: false
        });
    </script>
@elseif(Session::has('add_cart_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('add_cart_success') }}",
            icon: 'success',
            timer: 1000,
            toast: true,
            position: 'bottom-left',
            showConfirmButton: false
        });
    </script>
@elseif(Session::has('failed_voucher'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_voucher') }}",
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        });
    </script>
@endif

@if (Session::has('success_empty_cart'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('success_empty_cart') }}",
            icon: 'success',
            timer: 1000,
            toast: true,
            position: 'bottom-left',
            showConfirmButton: false
        });
    </script>
@endif

</html>
