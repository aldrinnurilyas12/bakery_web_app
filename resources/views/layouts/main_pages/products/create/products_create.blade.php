<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Item</title>
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
                    <h4>Tambah Data Produk</h4>
                    <hr>
                    <form action="{{ route('master_products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ old('product_name') }}" placeholder="Masukan nama Produk" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Produk</strong></label>
                            @if ($product_category->isNotEmpty())
                                <select class="form-control" name="category_id" id="">
                                    <option value="">==== Pilih Kategori Produk ====</option>
                                    @foreach ($product_category as $item)
                                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-secondary">Anda belum buat data Kategori, <a
                                        href="{{ route('category_create') }}">Buat kategori</a> </p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label><strong>Harga Produk</strong></label>
                            <input type="text" name="price" class="form-control" value="{{ old('price') }}"
                                placeholder="Masukan harga Produk" autocomplete="off">
                        </div>


                        <div class="form-group">
                            <label><strong>Diskon (%) (optional)</strong></label>
                            <small class="text-danger">*Masukan 0 jika produk tidak diskon</small>
                            <input type="text" name="discount" class="form-control" value="{{ old('discount') }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Harga Setelah Diskon (optional)</strong></label>
                            <input type="text" name="price_after_discount" class="form-control"
                                value="{{ old('price_after_discount') }}" autocomplete="off" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Berat Produk (optional (Gram/Kg/MG))</strong></label>
                            <input type="text" name="product_weight" class="form-control"
                                value="{{ old('product_weight') }}" placeholder="Masukan berat Produk (optional)"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Kadaluasa</strong></label>
                            <input type="date" name="expired_date" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi Produk</strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4"></textarea>
                        </div>


                        <div class="form-group">
                            <label><strong>Gambar/Foto Produk (Max 4 Foto)</strong></label>
                            <input type="file" name="images[]" multiple required class="form-control" required>
                        </div>



                        <button type="submit" class="btn btn-primary">Tambah Produk</button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
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
</script>


</html>
