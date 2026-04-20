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
                            <select name="supplier" class="form-control" id="supplier_company">
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
                                value="{{ old('purchase_date') }}" autocomplete="off">
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
                                                <th>Harga (satuan)</th>
                                                <th>Jumlah Item (hanya angka)</th>

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
                                                            value="{{ $item->item_code }}">
                                                        <input type="text"
                                                            name="raw_material[{{ $item->item_code }}]"
                                                            value="{{ $item->raw_material }}" hidden>

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
                                                    <td><input type="number" class="form-control"
                                                            name="price[{{ $item->item_code }}]" id="">
                                                    </td>
                                                    <td>
                                                        <input class="form-control"
                                                            name="quantity[{{ $item->item_code }}]" type="number">

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
                            <input type="number" name="total_amount" class="form-control"
                                value="{{ old('total_amount') }}" autocomplete="off">
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
                            <input type="file" name="payment_invoice" class="form-control" id="" required>
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
</script>


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
