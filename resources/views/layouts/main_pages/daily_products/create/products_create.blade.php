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
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                {{-- @php
                    $products = DB::table('v_products as vp')
                        ->select('vp.product_code', 'vp.product')
                        ->leftJoin('products_daily as dp', 'vp.product_code', '=', 'dp.product_code')
                        ->leftJoin('product_variant as pv', 'vp.product_code', '=', 'pv.product')

                        ->get();

                    dd($products);
                @endphp --}}
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Data Daily Produk</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Input Product Daily hanya dapat dilakukan setiap jam operasional Store pada pukul
                                06.00–08.00.</li>
                            <li>Perubahan data Product Daily hanya diperbolehkan sebelum pukul 08.30.</li>
                            <li>Input dan perubahan Product Daily hanya dapat dilakukan oleh user dengan role Admin dan
                                Supervisor.</li>
                            <li>Penghapusan Product Daily hanya dapat dilakukan oleh Admin.</li>
                            <li>Setiap input dan perubahan Product Daily wajib tercatat siapa yang melakukan dan kapan
                                dilakukan.</li>
                        </ul>
                    </div>
                    <form action="{{ route('master_daily_products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            @if ($products->isNotEmpty())
                                <select id="productSelect" class="form-control" name="product_code">
                                    <option value="">==== Pilih Produk ====</option>
                                    @foreach ($products as $item)
                                        <option value="{{ $item->product_code }}"
                                            data-variant="{{ $item->variant_code }}">
                                            @if ($item->variant_code)
                                                {{ $item->product . ' - ' . $item->variant_type }}
                                            @else
                                                {{ $item->product }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-secondary">Anda belum buat data Kategori, <a
                                        href="{{ route('category_create') }}">Buat kategori</a> </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="text" id="variantCodeInput" name="variant_code" class="form-control"
                                readonly hidden>
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah stok</strong></label>
                            <input type="text" name="stock_available" class="form-control"
                                value="{{ old('stock_available') }}" placeholder="Masukan jumlah stock"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah Point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" autocomplete="off">
                        </div>


                        <div style="display:flex; gap:20px;" class="btn-groupe">
                            <button type="submit" class="btn btn-primary">Tambah Item</button>
                            <a class="btn btn-info" href="{{ route('dailyproducts_data') }}">Kembali</a>
                        </div>

                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
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
</script>




</html>
