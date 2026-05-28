<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk Kategori</title>
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
                    <h4>Purchase Order</h4>
                    <hr>
                    {{-- <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Jika ingin menyambung jangan pakai '&' dan spasi, pakai underscore (_) contoh :
                                Muffins_and_Cupcakes</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div> --}}
                    <form id="formGeneralMaster" action="{{ route('purchase_order.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Perusahaan Supplier</strong></label>
                            <select name="supplier" class="form-control" id="supplier_company" required>
                                <option value="">=== Pilih Supplier ===</option>
                                @foreach ($supplier as $sp)
                                    <option value="{{ $sp->supplier_code }}">{{ $sp->store }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Purchase Order (PO)</strong></label>
                            <input type="date" name="purchase_date" class="form-control"
                                value="{{ old('purchase_date') }}" autocomplete="off" required>
                            <x-input-error :messages="$errors->get('purchase_date')" class="text-danger" />
                        </div>


                        <div class="form-group">
                            <div style="display:flex; justify-content: space-between;margin-bottom: 20px;"
                                class="dflex-group">
                                <label><strong>Pilih Item</strong></label>
                                <a class="btn btn-primary" href="{{ route('item_create') }}"><i class="fa fa-plus"></i>
                                    Buat Item Baru</a>
                            </div>

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
                                                <th>Kategori</th>
                                                <th>Massa</th>
                                                <th>Tgl Expired</th>
                                                <th>Harga (satuan)</th>
                                                <th>Jumlah Item (hanya angka)</th>
                                                <th>Qty Ratio (jumlah isi dalam pembelian) &nbsp;
                                                    <span> <a href="#" data-toggle="modal"
                                                            data-target="#showInfo"><i
                                                                class="fas fa-info-circle"></i></a></span>
                                                </th>
                                                <th>Subtotal</th>



                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach ($items as $item)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>

                                                        <input class="allowed-checkbox" type="checkbox"
                                                            name="item[{{ $item->item_code }}]"
                                                            value="{{ $item->item_code }}"
                                                            {{ old('item.' . $item->item_code) ? 'checked' : '' }}>
                                                        <input type="text"
                                                            name="raw_material[{{ $item->item_code }}]"
                                                            value="{{ old('raw_material.' . $item->item_code, $item->raw_material) }}"
                                                            hidden>

                                                    </td>
                                                    <td>

                                                        {{ '[' . $item->item_code . '] ' . ' - ' . $item->name }}
                                                    </td>
                                                    <td>{{ $item->category_name }}</td>
                                                    <td>
                                                        @if ($item->weight_type)
                                                            {{ $item->weight_type }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($item->item_category == 1)
                                                            <input type="date" class="form-control"
                                                                name="expired_date[{{ $item->item_code }}]"
                                                                id=""
                                                                value="{{ old('expired_date.' . $item->item_code) }}">
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div style="display: flex;" class="input-group">
                                                            <span style="text-align: center;" class="input-group-text">
                                                                Rp
                                                            </span>
                                                            <input type="number" class="form-control price"
                                                                name="price[{{ $item->item_code }}]" id=""
                                                                value="{{ old('price.' . $item->item_code) }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div style="display: flex;" class="input-group">
                                                            <input class="form-control quantity"
                                                                name="quantity[{{ $item->item_code }}]" type="number"
                                                                value="{{ old('quantity.' . $item->item_code) }}">
                                                            <span style="text-align: center;" class="input-group-text">
                                                                {{ $item->purchase_unit ?? '-' }}
                                                            </span>
                                                            <x-input-error :messages="$errors->get('quantity.' . $item->item_code)" class="text-danger" />
                                                        </div>
                                                    </td>
                                                    @php
                                                        $allowed_inv_unit = [
                                                            'Butir',
                                                            'Pcs',
                                                            'Pack',
                                                            'Box',
                                                            'Bungkus',
                                                            'Dus',
                                                            'Lusin',
                                                            'Kaleng',
                                                        ];

                                                        $not_allowed = [
                                                            'Kilogram',
                                                            'Gram',
                                                            'Miligram',
                                                            'Liter',
                                                            'Quintal',
                                                        ];
                                                    @endphp

                                                    @if ($item->inventory_unit)
                                                        {{-- semua unit yang memang langsung tidak boleh --}}
                                                        @if (in_array($item->inventory_unit, $not_allowed))
                                                            <td style="text-align: center;">-</td>
                                                        @else
                                                            <td>
                                                                <div style="display: flex;" class="input-group">
                                                                    <input class="form-control quantity"
                                                                        name="qty_ratio[{{ $item->item_code }}]"
                                                                        type="number"
                                                                        value="{{ old('qty_ratio.' . $item->item_code) }}">

                                                                    <span class="input-group-text">
                                                                        {{ $item->inventory_code ?? '-' }}
                                                                    </span>

                                                                    <x-input-error :messages="$errors->get(
                                                                        'qty_ratio.' . $item->item_code,
                                                                    )"
                                                                        class="text-danger" />
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @elseif($item->inventory_unit == null)
                                                        @if ($item->material_code)
                                                            <td style="text-align: center;">
                                                                <small class="text-danger">*tidak ada satuan
                                                                    inventory
                                                                    <a
                                                                        href="{{ route('material_update', $item->material_code) }}"><i
                                                                            class="fa fa-edit"></i> ubah</a></small>
                                                            </td>
                                                        @else
                                                            <td style="text-align: center;">-</td>
                                                        @endif
                                                    @endif
                                                    <td>
                                                        <input class="form-control subTotal" type="number" readonly>
                                                        <x-input-error :messages="$errors->get('quantity.' . $item->item_code)" class="text-danger" />

                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('item_code')" class="text-danger" />
                            <x-input-error :messages="$errors->get('quantity_used')" class="text-danger" />
                        </div>


                        <div class="form-group">
                            <label><strong>Total Biaya (*Hanya angka)</strong></label>
                            <input id="grandTotal" type="number" name="total_amount" class="form-control"
                                value="{{ old('total_amount') }}" autocomplete="off" readonly>
                            <x-input-error :messages="$errors->get('total_amount')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Status Pengiriman</strong></label>
                            <div style="display: flex; gap:20px;" class="group-checkbox">
                                <input id="delivery" type="radio" value="Y" name="delivery"> Ya
                                <input id="nodelivery" type="radio" value="N" name="delivery"> Tidak
                            </div>
                        </div>

                        <div id="showDeliveryDate" class="form-group">
                            <label><strong>Perkiraan Tanggal Pengiriman</strong></label>
                            <input type="date" name="expected_delivery_date" class="form-control"
                                value="{{ old('expected_delivery_date') }}" autocomplete="off">
                            <x-input-error :messages="$errors->get('expected_delivery_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Upload bukti transaksi pembayaran <span
                                        class="text-danger">*wajib</span></strong></label>
                            <input type="file" name="payment_invoice" class="form-control" id=""
                                required>
                            <x-input-error :messages="$errors->get('payment_invoice')" class="text-danger" />
                        </div>


                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>


            <div wire:ignore class="modal fade" id="showInfo" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Informasi Kuantitas Ratio pembelian bahan
                                baku</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Misalkan purchase order Bahan baku Telur ayam:</p>
                            <p>Dalam 1 Kg = 15 Butir telur => maka isi 15 setiap membeli 1 kg</p>
                            <br>
                            <small class="text-danger">*Harga dan isi setiap pembelian bahan baku tentative
                                (berubah-ubah) tergantung situasi pasar</small>


                        </div>
                        <div class="modal-footer">

                            <button id="btn-delete-general" type="button" data-dismiss="modal" aria-label="Close"
                                class="btn-general-delete"><span class="btn-text">Tutup</span>
                                <span class="spinner"></span></button>

                        </div>
                    </div>
                </div>
            </div>
</body>



<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<script>
    document.getElementById('supplier_company').addEventListener('change', function() {
        var supplier_company = this.value;

        const showRawMaterial = document.getElementById('showRawMaterial');

        fetch('/get_category/' + supplier_company)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Category not found');
                }
                return response.json();
            }).then(res => {
                if (res.data.length > 0 && res.data[0].category_name === 'Raw Material') {
                    showRawMaterial.style.display = "block";
                } else {
                    showRawMaterial.style.display = "none";
                }
            })
    });


    const deliveryYes = document.getElementById('delivery');
    const deliveryNo = document.getElementById('nodelivery');
    const showDeliveryDate = document.getElementById('showDeliveryDate');

    showDeliveryDate.style.display = 'none';

    deliveryYes.addEventListener('change', function() {
        if (this.checked) {
            showDeliveryDate.style.display = 'block';
        }
    });

    deliveryNo.addEventListener('change', function() {
        if (this.checked) {
            showDeliveryDate.style.display = 'none';
        }
    })


    document.addEventListener('DOMContentLoaded', function() {

        function calculate() {
            let grandTotal = 0;

            const rows = document.querySelectorAll('tr');

            rows.forEach(row => {
                const price = row.querySelector('.price');
                const qty = row.querySelector('.quantity');
                const subTotal = row.querySelector('.subTotal');

                if (price && qty && subTotal) {
                    let priceVal = parseFloat(price.value) || 0;
                    let qtyVal = parseFloat(qty.value) || 0;

                    let result = priceVal * qtyVal;
                    subTotal.value = result;

                    grandTotal += result;
                }
            });

            document.getElementById('grandTotal').value = grandTotal;
        }

        // trigger saat input berubah
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('price') || e.target.classList.contains('quantity')) {
                calculate();
            }
        });

    });
</script>

<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }

    #showRawMaterial {
        display: none;
    }
</style>

</html>
