<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Produk</title>
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
                    <h4>Ubah Data Produk</h4>
                    <form id="formGeneralMaster" action="{{ route('edit_product', $products->product_code) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <hr>
                        <div class="form-group">
                            <label><strong>SKU Produk</strong></label>
                            <input type="text" class="form-control" name="product_code"
                                value="{{ $products->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ $products->product }}">
                            <x-input-error :messages="$errors->get('product_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Produk</strong></label>
                            <select class="form-control" name="category_id" id="">
                                {{-- <option value="">==== Pilih Kategori Produk ====</option> --}}
                                @foreach ($products_category as $Produk)
                                    <option value="{{ $Produk->id }}"
                                        {{ $Produk->category_name == $products->category ? 'selected' : '' }}>
                                        {{ $Produk->category_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tipe Produk</strong></label>
                            <select class="form-control" name="product_type" id="">
                                {{-- <option value="">==== Pilih Kategori Produk ====</option> --}}
                                @foreach ($product_type as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $type->type_name == $products->product_type ? 'selected' : '' }}>
                                        {{ $type->type_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_type')" class="text-danger" />
                        </div>

                        @if ($products->product_status)
                            <div class="form-group">
                                <label><strong>Status Produk</strong></label>
                                <select class="form-control" name="product_status" id="">
                                    @foreach ($status as $sts)
                                        <option value="{{ $sts->id }}"
                                            {{ $sts->status_name == $products->product_status ? 'selected' : '' }}>
                                            {{ $sts->status_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        @elseif($products->product_status == null)
                            <div class="form-group">
                                <label><strong>Status Produk</strong></label>
                                <select class="form-control" name="product_status" id="">
                                    <option value="">== Pilih Status ===</option>
                                    @foreach ($status as $sts)
                                        <option value="{{ $sts->id }}">
                                            {{ $sts->status_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        @endif

                        <div class="form-group">
                            <label for=""><strong>Apakah Produk ini memiliki Variant? *(Minuman :Hot/Ice) atau
                                    (Makanan :
                                    Besar/Sedang/Kecil)</strong></label>
                            <br>
                            <small class="text-danger">*Jika Ya maka data Harga dan Discount akan terhapus
                                permanent dari Master data Product</small>
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
                        </div>


                        @if ($products->product_variant == 'N')
                            <div class="form-price-group" id="normalPrice">
                                <div class="form-group">
                                    <label><strong>Harga Produk</strong></label>
                                    <input id="priceProduct" type="text" inputmode="numeric" name="price_after"
                                        class="form-control" value="{{ $products->price }}">
                                    <input type="text" inputmode="numeric" name="price_before" class="form-control"
                                        value="{{ $products->price }}" hidden>
                                    <x-input-error :messages="$errors->get('price')" class="text-danger" />
                                </div>

                                <div class="form-group">
                                    <label><strong>Diskon</strong></label>
                                    <input id="discountProduct" type="text" inputmode="numeric" name="discount_after"
                                        class="form-control" value="{{ $products->discount }}">
                                    <input type="text" inputmode="numeric" name="discount_before"
                                        class="form-control" value="{{ $products->discount }}" hidden>
                                </div>

                                <div class="form-group">
                                    <label><strong>Harga setelah diskon</strong></label>
                                    <input id="discountPriceProduct" type="text" inputmode="numeric"
                                        name="price_after_discount_after" class="form-control"
                                        value="{{ $products->price_after_discount }}" readonly>
                                    <input type="text" inputmode="numeric" name="price_after_discount_before"
                                        class="form-control" value="{{ $products->price_after_discount }}" readonly
                                        hidden>
                                </div>

                                <div class="form-group">
                                    <label><strong>Tanggal Harga Efektif</strong></label>
                                    <input type="date" name="price_effective_from_after" class="form-control"
                                        value="{{ old('business_effective_date', $products->price_effective_from ? $business_effective_date->format('d M Y') : null) }}"
                                        autocomplete="off">
                                    <input type="date" name="price_effective_from_before" class="form-control"
                                        value="{{ old('business_effective_date', $products->price_effective_from ? $business_effective_date->format('d M Y') : null) }}"
                                        autocomplete="off" hidden>
                                </div>

                            </div>
                        @endif

                        <div class="form-group">
                            <label><strong>Berat Produk (optional)</strong></label>
                            <input type="text" inputmode="numeric" name="product_weight" class="form-control"
                                value="{{ $products->product_weight }}">
                            <x-input-error :messages="$errors->get('product_weight')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Unit Produk</strong></label>
                            <select name="product_weight_type" class="form-control" id="" required>
                                @foreach ($unit_category as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ $products->product_weight_type == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_weight_type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi Produk</strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4">
                                    {{ $products->description }}
                                </textarea>
                        </div>


                        <label><strong>Gambar/Foto Produk </strong></label>
                        <div style="display: flex; gap:10px; flex-wrap: wrap;" class="form-group">

                            @if ($product_images->isEmpty())
                                <p class="text-danger">Tidak ada foto</p>
                            @else
                                @foreach ($product_images as $p_images)
                                    <div style="display:block;width:100px; padding:2px;" class="dblock-image">

                                        <img width="100" height="100"
                                            src="{{ asset('storage/' . $p_images->images) }}" alt="">
                                        <button type="button" class="btn btn-danger mt-2 delete-image-btn"
                                            data-id="{{ $p_images->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                @endforeach

                            @endif

                        </div>
                        <br>
                        <div class="form-group">
                            <label><strong>Upload Gambar/Foto Produk </strong></label>
                            <input type="file" name="images" class="form-control">

                        </div>

                        <hr class="hr-menu">
                        <h4>Reward Point Rule</h4>
                        <hr>


                        <div class="form-group">
                            <label><strong>Masukan jumlah Point</strong></label>
                            @if ($products->point == null)
                                <small class="text-danger">*Tidak ada Point untuk produk ini</small>
                            @endif
                            <input type="text" name="point" class="form-control"
                                value="{{ $products->point }}" autocomplete="off">
                        </div>

                        @if ($point)
                            <div class="form-group">
                                <label><strong>Status Point</strong></label>
                                <select class="form-control" name="status" id="">
                                    @foreach ($status as $sts)
                                        <option value="{{ $sts->id }}"
                                            {{ $sts->status_name == $products->point_status ? 'selected' : '' }}>
                                            {{ $sts->status_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        @endif

                        <div class="form-group">
                            <label><strong>Tanggal awal</strong></label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('point_start_date', $products->point_start_date ? $point_start_date->format('d M Y') : null) }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir</strong></label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('point_end_date', $products->point_end_date ? $point_end_date->format('d M Y') : null) }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $products->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $products->updated_by ?: '-' }}"
                                readonly>
                        </div>

                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>

                    <form id="deleteImageForm" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
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
        const priceInput = document.querySelector('input[name="price_after"]');
        const discountInput = document.querySelector('input[name="discount_after"]');
        const priceAfterInput = document.querySelector('input[name="price_after_discount_after"]');

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
