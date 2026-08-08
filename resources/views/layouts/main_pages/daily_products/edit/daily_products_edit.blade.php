<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Produk Daily</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Ubah Data Produk Daily</h4>
                    <form action="{{ route('daily_product_edit', $product->daily_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <hr>
                        <div class="form-group">
                            <label><strong>Kode Daily Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $product->daily_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>SKU Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $product->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ $product->product }}" readonly>
                        </div>
                        {{-- @if ($product->stock_available == null || 0)
                            <div class="form-group">
                                <label><strong>Stok Produk</strong></label>
                                <small class="text-danger">*Stok Kosong mohon update stok produk ini</small>
                                <input type="text" name="stock_available" class="form-control"
                                    value="{{ $product->stock_available }}">
                                <x-input-error :messages="$errors->get('stock_available')" class="text-danger" />
                            </div>
                        @endif --}}

                        <div class="form-group">
                            <label><strong>Status Produk</strong></label>
                            <select class="form-control" name="status" id="">
                                {{-- <option value="">==== Pilih Kategori Produk ====</option> --}}
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $sts->id == $product->status ? 'selected' : '' }}>
                                        {{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Expired</strong></label>
                            <input type="date" name="expired_date"
                                value="{{ old('expired_date', $product->expired_date ? $expired_date->format('d M Y') : null) }}"
                                class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('expired_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text" class="form-control" value="{{ $product->store_name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $product->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $product->updated_by ?: '-' }}"
                                readonly>
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
