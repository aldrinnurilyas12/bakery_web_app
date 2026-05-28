<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                    <h4>Tambah Data Produk</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('master_products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ old('product_name') }}" placeholder="Masukan nama Produk" autocomplete="off"
                                required>
                            <x-input-error :messages="$errors->get('product_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Produk</strong></label>
                            @if ($product_category->isNotEmpty())
                                <select class="form-control" name="category_id" required>
                                    <option value="">==== Pilih Kategori Produk ====</option>
                                    @foreach ($product_category as $item)
                                        <option value="{{ $item->id }}"
                                            data-name="{{ strtolower($item->category_name) }}">
                                            {{ $item->category_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-secondary">Anda belum buat data Kategori, <a
                                        href="{{ route('category_create') }}">Buat kategori</a> </p>
                            @endif
                            <x-input-error :messages="$errors->get('category_id')" class="text-danger" />
                        </div>


                        <div class="form-group">
                            <label><strong>Tipe Produk</strong></label>
                            @if ($product_types->isNotEmpty())
                                <select class="form-control" name="product_type" required>
                                    <option value="">==== Pilih Tipe Produk ====</option>
                                    @foreach ($product_types as $item)
                                        <option value="{{ $item->id }}"
                                            data-name="{{ strtolower($item->type_name) }}">
                                            {{ $item->type_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-secondary">Anda belum buat data tipe produk, <a
                                        href="{{ route('category_create') }}">Buat tipe produk</a> </p>
                            @endif
                            <x-input-error :messages="$errors->get('product_type')" class="text-danger" />
                        </div>


                        {{-- <div class="form-group">
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
                        </div> --}}
                        

                        {{-- <div id="normalPrice" class="price-form-group">
                            <div class="form-group">
                                <label><strong>Harga Produk</strong></label>
                                <input type="text" name="price" class="form-control" value="{{ old('price') }}"
                                    placeholder="Masukan harga Produk" autocomplete="off">
                                <x-input-error :messages="$errors->get('price')" class="text-danger" />
                            </div>

                            <div class="form-group">
                                <label><strong>Diskon (%) (optional)</strong></label>
                                <small class="text-danger">*Masukan 0 jika produk tidak diskon</small>
                                <input type="text" name="discount" class="form-control"
                                    value="{{ old('discount') }}" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label><strong>Harga Setelah Diskon (optional)</strong></label>
                                <input type="text" name="price_after_discount" class="form-control"
                                    value="{{ old('price_after_discount') }}" autocomplete="off" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Harga Efektif</strong></label>
                                <input type="date" name="price_effective_from" class="form-control"
                                    value="{{ old('price_effective_from') }}" autocomplete="off">
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label><strong>Berat Produk</strong></label>
                            <input type="number" name="product_weight" class="form-control"
                                value="{{ old('product_weight') }}" placeholder="Masukan berat Produk (optional)"
                                autocomplete="off" required>
                            <x-input-error :messages="$errors->get('product_weight')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Unit Produk</strong></label>
                            <select name="product_weight_type" class="form-control" id="" required>
                                <option value="">=== Pilih Unit Produk ===</option>
                                @foreach ($unit_category as $unit )
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_weight_type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi Produk</strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4">
                                {{ old('description') }}
                            </textarea>
                        </div>


                        <div class="form-group">
                            <label><strong>Gambar/Foto Produk</strong></label>
                            <input type="file" name="images" class="form-control" required>
                            <x-input-error :messages="$errors->get('images')" class="text-danger" />
                        </div>


                        <hr class="hr-menu">
                        <h4>Reward Point Rule</h4>
                        <small class="text-danger">*Point adalah point produk yang digunakan saat pelanggan melakukan
                            transaksi</small> <br>
                        <small class="text-info">*Kosongkan point jika produk tidak memiliki point</small>
                        <hr>

                        <div class="form-group">
                            <label><strong>Masukan jumlah Point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" autocomplete="off">
                            <x-input-error :messages="$errors->get('point')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal</strong></label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('point') }}" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir</strong></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('point') }}"
                                autocomplete="off">
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
