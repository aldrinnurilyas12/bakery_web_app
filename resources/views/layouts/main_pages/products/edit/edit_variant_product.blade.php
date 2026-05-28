<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Variant Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                    <h4>Ubah Data Variant Produk</h4>
                    <form id="formGeneralMaster" action="{{ route('edit_variant', $variant->variant_code) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @METHOD('PUT')
                        <hr>
                        <div class="form-group">
                            <label><strong>Kode Variant</strong></label>
                            <input type="text" class="form-control" value="{{ $variant->variant_code }}" readonly>
                            <input type="text" class="form-control" name="product_code"
                                value="{{ $variant->product_code }}" readonly hidden>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control"
                                value="{{ '[' . $variant->product_code . ']' . ' - ' . $variant->product }}" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>HPP Produk</strong></label>
                            <input type="text" class="form-control"
                                value="{{ 'Rp.' . number_format($variant->hpp) }}" readonly>
                            <input name="hpp" type="text" class="form-control"
                                value="{{ $variant->hpp }}" hidden readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $variant->category }}" readonly>
                        </div>

                        @if ($variant->category == 'Coffee')
                            <div class="form-group">
                                <label><strong>Tipe Variant</strong></label>
                                <select name="variant_type" class="form-control">
                                    @foreach ($variant_category_drink as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $variant->variant_type ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <label><strong>Tipe Variant</strong></label>
                                <select name="variant_type" class="form-control">
                                    @foreach ($variant_category_food as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $variant->variant_type ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label><strong>Harga Variant Produk</strong></label>
                            <input type="number" inputmode="numeric" name="variant_price_after" class="form-control"
                                value="{{ $variant->variant_price }}">
                            <x-input-error :messages="$errors->get('variant_price_after')" class="text-danger" />
                            <input type="text" inputmode="numeric" name="variant_price_before" class="form-control"
                                value="{{ $variant->variant_price }}" hidden>
                            <x-input-error :messages="$errors->get('variant_price_before')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon</strong></label>
                            <input type="number" inputmode="numeric" name="variant_discount_after" class="form-control"
                                value="{{ $variant->variant_discount }}">
                            <input type="text" inputmode="numeric" name="variant_discount_before"
                                class="form-control" value="{{ $variant->variant_discount }}" hidden>
                        </div>

                        <div class="form-group">
                            <label><strong>Harga setelah diskon</strong></label>
                            <input type="text" inputmode="numeric" name="variant_price_after_discount_after"
                                class="form-control" value="{{ $variant->variant_price_after_discount }}" readonly>
                            <input type="text" inputmode="numeric" name="variant_price_after_discount_before"
                                class="form-control" value="{{ $variant->variant_price_after_discount }}" readonly
                                hidden>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Harga Efektif</strong></label>
                            <input type="date" name="price_effective_from_after" class="form-control"
                                value="{{ old('business_effective_date', $variant->price_effective_from ? $business_effective_date->format('Y-m-d') : null) }}"
                                autocomplete="off">
                            <input type="date" name="price_effective_from_before" class="form-control"
                                value="{{ old('business_effective_date', $variant->price_effective_from ? $business_effective_date->format('Y-m-d') : null) }}"
                                autocomplete="off" hidden>
                        </div>


                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>



        </div>
    </div>
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
        const priceInput = document.querySelector('input[name="variant_price_after"]');
        const discountInput = document.querySelector('input[name="variant_discount_after"]');
        const priceAfterInput = document.querySelector('input[name="variant_price_after_discount_after"]');

        function calculateDiscount() {
            let price = parseFloat(priceInput.value) || 0;
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


    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-image-btn');
        const deleteForm = document.getElementById('deleteImageForm');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const imageId = this.getAttribute('data-id');

                if (confirm("Yakin ingin menghapus gambar ini?")) {
                    deleteForm.setAttribute('action', "{{ url('/delete_image') }}/" + imageId);
                    deleteForm.submit();
                }
            });
        });
    });




    document.addEventListener("DOMContentLoaded", function() {
        const radioYes = document.getElementById("categorySelectYes");
        const radioNo = document.getElementById("categorySelectNo");
        const showPrice = document.getElementById('normalPrice');
        const removePrice = document.getElementById('priceProduct');
        const removePriceDiscount = document.getElementById('discountPriceProduct');
        const removeDiscount = document.getElementById('discountProduct');

        const initialPrice = removePrice.value;
        const initialDiscount = removeDiscount.value;
        const initialPriceAfterDiscount = removePriceDiscount.value;

        function togglePrice() {
            if (radioYes.checked) {
                showPrice.style.display = 'none';

                removePrice.value = "";
                removeDiscount.value = "";
                removePriceDiscount.value = "";

                removePrice.disabled = true;
                removeDiscount.disabled = true;
                removePriceDiscount.disabled = true;
            } else if (radioNo.checked) {
                showPrice.style.display = 'block';

                removePrice.disabled = false;
                removeDiscount.disabled = false;
                removePriceDiscount.disabled = false;

                removePrice.value = initialPrice;
                removeDiscount.value = initialDiscount;
                removePriceDiscount.value = initialPriceAfterDiscount;
            }
        };


        radioYes.addEventListener("change", togglePrice);
        radioNo.addEventListener("change", togglePrice);

        togglePrice();

    });
</script>

</html>
