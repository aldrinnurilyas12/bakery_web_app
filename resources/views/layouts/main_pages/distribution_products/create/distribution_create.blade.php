<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Buat Distribusi Stock Produk Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Data Distribusi Stok Produk ke Store</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Distribusi stok produk untuk store dilakukan setiap produksi selesai</li>
                            <li>Jumlah distribusi ke setiap store harus tidak melebihi qty available</li>
                            <li>Total distribusi ke seluruh store harus sama atau kurang dari qty available</li>
                            <li>Prioritas distribusi dapat ditentukan berdasarkan performa penjualan masing-masing store
                            </li>
                            <li>Store dengan stok menipis dapat diprioritaskan untuk pengiriman</li>
                            <li>Distribusi harus dicatat dan tervalidasi dalam sistem sebelum dikirim</li>
                            <li>Perubahan jumlah distribusi setelah submit harus melalui proses approval</li>
                            <li>Setiap store wajib melakukan konfirmasi penerimaan barang</li>
                            <li>Selisih stok akibat distribusi harus segera dilaporkan dan ditindaklanjuti</li>
                            <li>Distribusi dapat disesuaikan berdasarkan kondisi khusus (promo, event, atau permintaan
                                tinggi)</li>
                        </ul>
                    </div>
                    @if ($products->isNotEmpty())
                        <form id="formGeneralMaster" action="{{ route('distribution_products.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Varian</th>
                                                <th>Qty Available</th>
                                                <th>Tanggal Expired</th>
                                                @foreach ($stores as $store)
                                                    <th>{{ $store->store_name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>

                                                    <td>
                                                        {{ $product->product }}
                                                        <input type="hidden"
                                                            name="product[{{ $loop->index }}][product_code]"
                                                            value="{{ $product->product_code }}">
                                                    </td>
                                                    <td>
                                                        {{ $product->variant ?: '-' }}
                                                        <input type="hidden"
                                                            name="product[{{ $loop->index }}][variant_code]"
                                                            value="{{ $product->variant_code }}">
                                                    </td>

                                                    <td>
                                                        {{ $product->total_available }}
                                                        <input id="stock_{{ $loop->index }}" type="hidden"
                                                            name="product[{{ $loop->index }}][total_available]"
                                                            value="{{ $product->total_available }}">
                                                    </td>

                                                    <td>
                                                        @if ($product->total_available == 0)
                                                        <input type="number" placeholder="Stok Kosong"
                                                                    class="form-control" readonly>
                                                        @else
                                                        <input class="form-control" type="date"
                                                            name="product[{{ $loop->index }}][expired_date]">
                                                        @endif
                                                    </td>

                                                    @foreach ($stores as $store)
                                                        <td>
                                                            @if ($product->total_available == 0)
                                                                <input type="number" placeholder="Stok Kosong"
                                                                    class="form-control" readonly>
                                                            @else
                                                                <input class="form-control" type="number"
                                                                    name="product[{{ $loop->parent->index }}][store][{{ $store->store_code }}]"
                                                                    min="0"
                                                                    max="{{ $product->total_available }}"
                                                                    id="qty_{{ $loop->parent->index }}_{{ $store->store_code }}"
                                                                    oninput="validateTotal({{ $loop->parent->index }})"
                                                                    value="{{ old('product.' . $loop->parent->index . '.store.' . $store->store_code) }}">

                                                                <small
                                                                    id="error_{{ $loop->parent->index }}_{{ $store->store_code }}"
                                                                    style="color:red; display:none;">
                                                                    Jumlah melebihi stok
                                                                </small>
                                                            @endif
                                                        </td>
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <br>
                                    <div class="form-group">
                                        <label><strong>Tanggal Distribusi</strong></label>
                                        <input type="date" name="distribution_date" class="form-control"
                                            value="{{ old('distribution_date') }}" required>
                                        <x-input-error :messages="$errors->get('distribution_date')" class="text-danger" />
                                    </div>


                                    <button id="btnMaster" type="submit" class="btn-general"><span
                                            class="btn-text">Simpan
                                            Data</span>
                                        <span class="spinner"></span></button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                            class="empty-transaction">

                            <div style="display: flex;" class="empty-content">
                                <div style="display: flex; gap:20px;margin:auto;">
                                    <img width="70" height="70"
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div style="display: block;align-self: center;">
                                        <h3>Belum ada data produksi produk</h3>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

@if (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif

<script>
    document.getElementById("formGeneralMaster").addEventListener("submit", function(e) {

        let invalid = false;

        // ambil semua input qty
        let inputs = document.querySelectorAll('[id^="qty_"]');

        inputs.forEach(function(input) {
            // cek kalau ada border merah
            if (input.style.border.includes("red")) {
                invalid = true;
            }
        });

        if (invalid) {
            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Masih ada input yang melebihi stok!',
                confirmButtonText: 'OK'
            });

            return false;
        }
    });

    function updateSubmitButton() {
        let inputs = document.querySelectorAll('[id^="qty_"]');
        let btn = document.getElementById("btnMaster");

        let hasError = false;

        inputs.forEach(function(input) {
            if (input.style.border.includes("red")) {
                hasError = true;
            }
        });

        if (hasError) {
            btn.setAttribute("type", "button");

            // reset class dulu
            btn.classList.remove("btn-general");
            btn.classList.add("btn-general-secondary");

        } else {
            btn.setAttribute("type", "submit");

            // reset class dulu
            btn.classList.remove("btn-general-secondary");
            btn.classList.add("btn-general");
        }
    }


    function validateTotal(index) {
        let stock = parseInt(document.getElementById("stock_" + index).value) || 0;

        let inputs = document.querySelectorAll('[id^="qty_' + index + '_"]');

        let total = 0;

        inputs.forEach(function(input) {
            let value = parseInt(input.value) || 0;

            total += value;

            let errorId = "error_" + input.id.replace("qty_", "");
            let errorEl = document.getElementById(errorId);

            if (total > stock && value > 0) {
                // ❌ input ini yang bikin kelebihan
                input.style.border = "2px solid red";

                if (errorEl) {
                    errorEl.style.display = "block";
                    errorEl.innerText = "Jumlah melebihi sisa stok";
                }
            } else {
                // ✅ aman
                input.style.border = "";

                if (errorEl) {
                    errorEl.style.display = "none";
                }
            }
        });

        updateSubmitButton();
    }
</script>

</html>
