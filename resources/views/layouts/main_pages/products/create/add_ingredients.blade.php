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
                    <h4>Tambah Ingredients Produk (Bill of Material)</h4>
                    <form id="formGeneralMaster" action="{{ route('save_ingredients') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <hr>
                        <div class="form-group">
                            <label><strong>SKU Produk</strong></label>
                            <input type="text" name="product" class="form-control"
                                value="{{ $products->product_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label><strong>Nama Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->product_name }}" readonly>
                        </div>

                         <div class="form-group">
                            <label><strong>Unit Produk</strong></label>
                            <input type="text" class="form-control" value="{{ $products->type_name ?: '-' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Bahan Baku</strong></label>
                            <div style="color: black; height: 400px;background: white;overflow: auto;"
                                class="modal-body">
                                <div class="table-responsive">
                                    <table style="font-size: 14px; color:black;" class="table" 
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pilih</th>
                                                <th>Bahan Baku</th>
                                                <th>Harga</th>
                                                <th>Satuan Unit</th>
                                                <th>Isi Satuan</th>
                                                <th>Harga satuan</th>
                                                <th>Harga per unit</th>
                                                <th>Jumlah Penggunaan</th>
                                                <th>Subtotal</th>
                                                <th>Unit</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $no = 1;
                                            ?>
                                            @foreach ($raw_materials as $raw)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        @if ($raw->quantity == 0)
                                                            <a href="{{ route('po_create') }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        @else
                                                            <input class="allowed-checkbox" type="checkbox"
                                                                name="raw_material[{{ $raw->material_code }}]"
                                                                value="{{ $raw->material_code }}">
                                                        @endif
                                                    </td>
                                                    <td>{{ '[' . $raw->material_code . '] ' . ' - ' . $raw->material_name }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if ($raw->price)
                                                           <span id="price"> {{ 'Rp.' . number_format($raw->price) }}</span>
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $raw->purchase_unit_name }}</td>
                                                    <td>
                                                        @if ($raw->qty_ratio)
                                                            {{ $raw->qty_ratio ?: '-' }}
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (in_array($raw->inventory_unit_name, ['Butir', 'Pcs', 'Pack', 'Dus', 'Lusin', 'Kaleng']))
                                                            <span>-</span>
                                                        @else
                                                           <span id="price_unit">Rp.</span> {{ $raw->price / 1000 }}
                                                            <input type="hidden" class="price_unit" value="{{ $raw->price / 1000 }}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($raw->qty_ratio)
                                                            <span id="price_satuan">Rp.</span>{{ $raw->price / $raw->qty_ratio }}
                                                             <input type="hidden" class="price_satuan" value="{{ $raw->price / $raw->qty_ratio }}">
                                                        @else
                                                            <span>-</span>
                                                        @endif

                                                    @if($raw->price)
                                                    <td>
                                                        <input class="form-control quantity"
                                                            name="quantity[{{ $raw->material_code }}]" type="number">

                                                        <x-input-error :messages="$errors->get('quantity_used.' . $raw->material_code)" class="text-danger" />
                                                    </td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    @endif

                                                     <td>
                                                        <input type="text" name="subtotal[{{ $raw->material_code }}]"  class="form-control subtotal" id="" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="unit[{{ $raw->material_code }}]"
                                                            class="form-control">
                                                            <option value="">== Pilih ==</option>
                                                            @foreach ($material_unit as $unit)
                                                                        <option value="{{ $unit->id }}">
                                                                            {{ $unit->unit_name }}
                                                                        </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('raw_material')" class="text-danger" />
                            <x-input-error :messages="$errors->get('quantity')" class="text-danger" />
                        </div>

                        <div class="form-group">
                              <label><strong>Total HPP</strong></label>
                            <div style="display:flex;" class="result-hpp">
                                <span style="background: gray; color:black;padding:4px;text-align: center;">Rp</span>
                                <input class="form-control" type="text" id="resultHpp" name="hpp" readonly>
                            </div>
                            
                        </div>


                        <div style="display: block; gap:20px;" class="button-groupe">

                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        </div>
                        <br>
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

    document.addEventListener('DOMContentLoaded', function () {

    const qtyInputs = document.querySelectorAll('.quantity');

    qtyInputs.forEach(function(input) {

        input.addEventListener('input', function () {

            let row = this.closest('tr');

            // ambil quantity
            let qty = parseFloat(this.value) || 0;

            // ambil price_unit
            let priceUnitElement = row.querySelector('.price_unit');

            // ambil price_satuan
            let priceSatuanElement = row.querySelector('.price_satuan');

            let priceUnit = 0;
            let priceSatuan = 0;

            if (priceUnitElement) {
                priceUnit = parseFloat(priceUnitElement.value) || 0;
            }

            if (priceSatuanElement) {
                priceSatuan = parseFloat(priceSatuanElement.value) || 0;
            }

            // jika price_unit kosong gunakan price_satuan
            let finalPrice = priceUnit > 0 ? priceUnit : priceSatuan;

            // hitung subtotal
            let subtotal = finalPrice * qty;

            // tampilkan subtotal per row
            let subtotalInput = row.querySelector('.subtotal');

            if (subtotalInput) {
                subtotalInput.value = subtotal.toFixed(0);
            }

            // hitung grand total
            let grandTotal = 0;

            document.querySelectorAll('.subtotal').forEach(function(sub) {

                let value = sub.value.replace(/\./g, '').replace(',', '.');

                grandTotal += parseFloat(value) || 0;
            });

            // tampilkan ke resultHpp
            document.getElementById('resultHpp').value =
                grandTotal.toFixed(0);

        });

    });

});
</script>

</html>
