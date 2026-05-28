<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Harga Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h4>Tambah Data Harga Produk</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('product_price_save', $product->product_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control"
                                value="{{ $product->product }}" placeholder="Masukan nama Produk" autocomplete="off"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>HPP Produk</strong></label>
                            <input type="text" class="form-control"
                                value="{{'Rp' . number_format($product->hpp) }}" readonly autocomplete="off"
                                required>
                             <input hidden type="text" name="hpp" class="form-control" value="{{ $product->hpp }}" readonly>
                        </div>



                        <div class="form-group">
                            <label for=""><strong>Apakah Produk ini memiliki Variant? *(Minuman :Hot/Ice) atau
                                    (Makanan :
                                    Besar/Sedang/Kecil)</strong></label>
                            <br>
                            <div style="display: flex;gap:30px;" class="radio-variant">
                                <div class="sub-radio-variant">
                                    <input id="categorySelectYes" name="product_variant" value="Y" type="radio"
                                        required>
                                    <label for="variant_yes">Ya</label>
                                </div>
                                <div class="sub-radio-variant">
                                    <input id="categorySelectNo" name="product_variant" value="N" type="radio"
                                        checked>
                                    <label for="variant_no">Tidak</label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('product_variant')" class="text-danger" />
                        </div>
                        

                        <div id="normalPrice" class="price-form-group">
                            <div class="form-group">
                                <label><strong>Harga Produk</strong></label>
                                <input type="number" name="price" class="form-control"
                                    placeholder="Masukan harga Produk" autocomplete="off">
                                <x-input-error :messages="$errors->get('price')" class="text-danger" />
                            </div>

                            <div class="form-group">
                                <label><strong>Diskon (%) (optional)</strong></label>
                                <small class="text-danger">*Masukan 0 jika produk tidak diskon</small>
                                <input type="text" name="discount" class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label><strong>Harga Setelah Diskon (optional)</strong></label>
                                <input type="text" name="price_after_discount" class="form-control" autocomplete="off" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Harga Efektif</strong></label>
                                <input type="date" name="price_effective_from" class="form-control"
                                    value="{{ old('price_effective_from') }}" autocomplete="off" >
                            </div>
                        </div>


                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Tambah
                                Produk</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>

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
@elseif (Session::has('failed_message'))
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


<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.querySelector('input[name="price"]');
        const priceInputIce = document.querySelector('input[name="price_ice"]');
        const priceInputHot = document.querySelector('input[name="price_hot"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const priceAfterInput = document.querySelector('input[name="price_after_discount"]');

        function calculateDiscount() {
            let price = parseFloat(priceInput.value) || parseFloat(priceInputHot.value) || parseFloat(
                priceInputIce.value) || 0;

            let discount = parseFloat(discountInput.value) || 0;
            if (discount > 0) {
                fixPrice = price - (price * (discount / 100));
            } else if (discount === 0) {
                fixPrice = price;
            }

            priceAfterInput.value = fixPrice;
        }


        priceInput.addEventListener('input', calculateDiscount);
        discountInput.addEventListener('input', calculateDiscount);
    });


    document.addEventListener("DOMContentLoaded", function() {
        const radioYes = document.getElementById("categorySelectYes");
        const radioNo = document.getElementById("categorySelectNo");
        const normalPrice = document.getElementById('normalPrice');

        function togglePrice() {
            if (radioYes.checked) {
                normalPrice.style.display = 'none';
            } else if (radioNo.checked) {
                normalPrice.style.display = 'block';

            }
        }

        radioYes.addEventListener("change", togglePrice);
        radioNo.addEventListener("change", togglePrice);
    });
</script>


</html>
