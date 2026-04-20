<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Variant Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control"
                                value="{{ '[' . $variant->product_code . ']' . ' - ' . $variant->product }}" readonly>
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
                            <input type="text" inputmode="numeric" name="variant_price" class="form-control"
                                value="{{ $variant->variant_price }}">
                            <x-input-error :messages="$errors->get('variant_price')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon</strong></label>
                            <input type="text" inputmode="numeric" name="variant_discount" class="form-control"
                                value="{{ $variant->variant_discount }}">
                        </div>

                        <div class="form-group">
                            <label><strong>Harga setelah diskon</strong></label>
                            <input type="text" inputmode="numeric" name="variant_price_after_discount"
                                class="form-control" value="{{ $variant->variant_price_after_discount }}" readonly>
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
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.querySelector('input[name="variant_price"]');
        const discountInput = document.querySelector('input[name="variant_discount"]');
        const priceAfterInput = document.querySelector('input[name="variant_price_after_discount"]');

        function calculateDiscount() {
            let price = parseFloat(priceInput.value) || 0;
            let discount = parseFloat(discountInput.value) || 0;
            if (discount > 0) {
                fixPrice = price - (price * (discount / 100));
            } else if (discount === 0) {
                fixPrice = 0;
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
