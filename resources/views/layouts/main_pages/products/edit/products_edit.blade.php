<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Produk</title>
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
                <div class="container-fluid px-4">
                    <h4>Ubah Data Produk</h4>
                    <form action="{{ route('edit_product', $products->product_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <hr>
                        <div class="form-group">
                            <label><strong>Kode Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ $products->product }}">
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
                        </div>
                        <div class="form-group">
                            <label><strong>Harga Produk</strong></label>
                            <input type="text" inputmode="numeric" name="price" class="form-control"
                                value="{{ $products->price }}">
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon</strong></label>
                            <input type="text" inputmode="numeric" name="discount" class="form-control"
                                value="{{ $products->discount }}">
                        </div>

                        <div class="form-group">
                            <label><strong>Harga setelah diskon</strong></label>
                            <input type="text" inputmode="numeric" name="price_after_discount" class="form-control"
                                value="{{ $products->price_after_discount }}" readonly>
                        </div>


                        <div class="form-group">
                            <label><strong>Berat Produk (optional)</strong></label>
                            <input type="text" inputmode="numeric" name="product_weight" class="form-control"
                                value="{{ $products->product_weight }}">
                        </div>
                        <div class="form-group">
                            <label><strong>Tanggal Kadaluarsa</strong></label>
                            <input type="date" name="expired_date" class="form-control"
                                value="{{ old('expired_date', $products->expired_date ? $expired_date->format('Y-m-d') : null) }}">
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
                            <input type="file" name="images[]" multiple class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </form>

                    <form id="deleteImageForm" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>



                </div>
            </main>



        </div>
    </div>
</body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.querySelector('input[name="price"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const priceAfterInput = document.querySelector('input[name="price_after_discount"]');

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
</script>

</html>
