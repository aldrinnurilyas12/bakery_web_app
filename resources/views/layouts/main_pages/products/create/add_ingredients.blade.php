<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Ingredients Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h4>Tambah Ingredients Produk</h4>
                    <form id="formGeneralMaster" action="{{ route('save_ingredients') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <hr>
                        <div class="form-group">
                            <label><strong>Kode Produk</strong></label>
                            <input type="text" name="product" class="form-control"
                                value="{{ $products->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->product_name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Bahan Baku</strong></label>
                            <div style="color: black; height: 400px;background: white;overflow: auto;"
                                class="modal-body">
                                <div class="table-responsive">
                                    <table style="font-size: 14px; color:black;" class="table" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pilih</th>
                                                <th>Bahan Baku</th>
                                                <th>Qty</th>
                                                <th>Massa</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach ($raw_materials as $raw)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        @if ($raw->quantity == 0)
                                                            <a
                                                                href="{{ route('material_update', $raw->material_code) }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        @else
                                                            <input class="allowed-checkbox" type="checkbox"
                                                                name="raw_material[{{ $raw->material_code }}]"
                                                                value="{{ $raw->material_code }}">
                                                        @endif
                                                    </td>
                                                    <td>{{ '[' . $raw->material_code . '] ' . ' - ' . $raw->material_name }}
                                                    </td>

                                                    <td>
                                                        <input class="form-control"
                                                            name="quantity[{{ $raw->material_code }}]" type="number">

                                                        <x-input-error :messages="$errors->get('quantity_used.' . $raw->material_code)" class="text-danger" />
                                                    </td>
                                                    <td>

                                                        <select name="weight[{{ $raw->material_code }}]"
                                                            class="form-control" id="">
                                                            <option value="">=== Pilih Massa Bahan Baku ===
                                                            </option>
                                                            <option value="pcs">Pcs</option>
                                                            <option value="miligram">Miligram</option>
                                                            <option value="gram">Gram</option>
                                                            <option value="kilogram">Kilogram</option>
                                                            <option value="quintal">Quintal</option>
                                                            <option value="ton">Ton</option>
                                                            <option value="sachet">Sachet</option>
                                                            <option value="pack">Pack</option>
                                                            <option value="liter">Liter</option>
                                                            <option value="mililiter">Mililiter</option>
                                                        </select>
                                                    </td>

                                                    {{-- <td>
                                                        <input class="disallowed-checkbox" type="checkbox"
                                                            name="disallowed[]" value="{{ $raw->id }}"
                                                            {{ in_array($raw->id, $disallowedData) ? 'checked' : '' }}>
                                                    </td>  --}}
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('raw_material')" class="text-danger" />
                            <x-input-error :messages="$errors->get('quantity')" class="text-danger" />
                        </div>



                        <div style="display: block; gap:20px;" class="button-groupe">

                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        </div>
                        <br>
                        <a style="width:100%;" class="btn btn-info" href="{{ route('products_data') }}">Kembali</a>
                    </form>
                    <br>
                    <br>
                </div>
            </main>



        </div>
    </div>

</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>


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
