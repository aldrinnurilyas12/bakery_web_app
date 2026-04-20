<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Data Daily Produk</h4>
                    <hr>
                    <div class="card-header">
                        &nbsp; <a class="btn btn-primary" href="{{ route('dailyproducts_data') }}">Kembali</a>
                    </div>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Input Product Daily hanya dapat dilakukan setiap jam operasional Store pada pukul
                                06.00–08.00.</li>
                            <li>Data Produk akan muncul ketika proses Distribusi Produk dari Central berhasil</li>
                            <li>Perubahan data Product Daily hanya diperbolehkan sebelum pukul 08.30.</li>
                            <li>Input dan perubahan Product Daily hanya dapat dilakukan oleh user dengan role Admin dan
                                Supervisor.</li>
                            <li>Penghapusan Product Daily hanya dapat dilakukan oleh Admin.</li>
                            <li>Produk akan muncul ketika masa Produksi Produk sudah selesai</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('master_daily_products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text" class="form-control"
                                value="{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            @if ($products->isNotEmpty())
                                <select id="productSelect" class="form-control" name="distribution_store">
                                    <option value="">==== Pilih Produk ====</option>
                                    @foreach ($products as $item)
                                        <option value="{{ $item->distribution_store_code }}">
                                            {{ $item->distribution_store_code . ' - ' . '[' . $item->product . ($item->variant ? ' - ' . $item->variant : '') . ']' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('production')" class="text-danger" />
                            @else
                                <p class="text-secondary">Produk belum didistribusi pada Store ini </p>
                            @endif
                        </div>

                        @if ($products->isNotEmpty())
                            <div class="form-group">
                                <input type="text" id="variantCodeInput" name="variant_code" class="form-control"
                                    readonly hidden>
                            </div>

                            <div class="form-group">
                                <label><strong>Jumlah stock tersedia</strong></label>
                                <input type="text" name="stock_available" class="form-control" id="showStock"
                                    value="{{ old('stock_available') }}" placeholder="Stok produk akan muncul"
                                    autocomplete="off" readonly>
                                <x-input-error :messages="$errors->get('stock_available')" class="text-danger" />
                            </div>

                            <div style="display:flex; gap:20px;" class="btn-groupe">
                                <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                        Data</span>
                                    <span class="spinner"></span></button>
                            </div>
                        @endif

                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productSelect = document.getElementById("productSelect");
        const variantInput = document.getElementById("variantCodeInput");

        productSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const variantCode = selectedOption.dataset.variant;

            if (variantCode) {
                variantInput.value = variantCode;
            } else {
                variantInput.value = ""
            }
        });
    });

    document.getElementById('productSelect').addEventListener('change', function() {
        var productSelect = this.value;

        if (!productSelect) {
            document.getElementById('showStock').value = '';
            return;
        }

        fetch('/get_stock_product/' + productSelect).then(response => {
            if (!response.ok) {
                throw new Error('Stock not found');
            }
            return response.json();
        }).then(data => {
            if (data.data.received_quantity) {
                document.getElementById('showStock').value = data.data.received_quantity;
            }
        })
    })
</script>




</html>
