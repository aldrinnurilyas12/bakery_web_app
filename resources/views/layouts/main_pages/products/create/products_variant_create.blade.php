<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk Variant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Variant Produk</h4>
                    <form action="{{ route('save_product_variant') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <hr>
                        <div class="form-group">
                            <label><strong>Kode Produk</strong></label>
                            <input type="text" name="product" class="form-control"
                                value="{{ $products->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->product }}" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->category }}" readonly>
                        </div>

                        @if ($products->category == 'Coffee')
                            <div class="form-group">
                                <label><strong>Tipe Variant</strong></label>
                                <select name="variant_type" class="form-control">
                                    <option value="">=== Pilih tipe variant ===</option>
                                    <option value="ice">Ice</option>
                                    <option value="hot">Hot</option>
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <label><strong>Tipe Variant</strong></label>
                                <select name="variant_type" class="form-control">
                                    <option value="">=== Pilih tipe variant ===</option>
                                    <option value="small">Kecil</option>
                                    <option value="medium">Sedang</option>
                                    <option value="large">Besar</option>
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label><strong>Harga Variant Produk</strong></label>
                            <input type="text" inputmode="numeric" name="variant_price" class="form-control"
                                value="{{ old('variant_price') }}">
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon</strong></label>
                            <input type="text" inputmode="numeric" name="variant_discount" class="form-control"
                                value="{{ old('variant_discount') }}">
                        </div>

                        <div class="form-group">
                            <label><strong>Harga setelah diskon</strong></label>
                            <input type="text" inputmode="numeric" name="variant_price_after_discount"
                                class="form-control" value="{{ old('variant_price_after_discount') }}" readonly>
                        </div>


                        <div style="display: flex; gap:20px;" class="button-groupe">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                            <a class="btn btn-info" href="{{ route('products_data') }}">Kembali</a>
                        </div>
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
@endif

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>



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
</script>

</html>
