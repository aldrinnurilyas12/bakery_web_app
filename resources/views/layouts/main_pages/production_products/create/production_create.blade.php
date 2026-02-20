<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produksi Produk</title>
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
                    <h4>Tambah Data Produksi Produk</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Input data produksi produk hanya boleh dilakukan oleh user dengan role Admin atau Staff
                                Produksi produk.</li>
                            <li>Setiap data produksi produk wajib mencantumkan tanggal produksi produk dan shift
                                produksi produk yang jelas.
                            </li>
                            <li>Jumlah produk yang diproduksi produk harus valid, tidak boleh negatif atau melebihi
                                kapasitas
                                produksi produk harian.</li>
                            <li>Produk yang sudah dicatat produksi produk tidak boleh dihapus, hanya dapat diubah dengan
                                alasan valid dan tercatat.</li>
                            <li>Jika stok kosong segera melakukan input stok kembali pada modul Raw Material(Bahan
                                Baku).</li>
                            <li>Jadwal untuk melakukan input data Produksi Produk pada jam 05.00 s.d 07.30</li>
                        </ul>
                    </div>
                    <form action="{{ route('master_production_product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text"
                                value="{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}"
                                class="form-control" autocomplete="off" readonly>
                        </div>
                        <hr class="hr-menu">

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            <select name="product" class="form-control" id="products">
                                <option value="">=== Pilih Produk ===</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->product_code }}">
                                        {{ $item->product }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product')" class="text-danger" />
                        </div>

                        {{-- HIDE THIS --}}
                        <div class="form-group" id="showSelectVariant" style="display: none;">
                            <label><strong>Variant</strong></label>
                            <select name="variant" class="form-control" id="variantSelect">
                                <option value="">=== Pilih Variant Produk ===</option>
                            </select>
                            <x-input-error :messages="$errors->get('product')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <input hidden type="text" name="variant" id="showVariantCode">
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
                                                <th>Nama Material</th>
                                                <th>Stok</th>
                                                <th>Massa</th>
                                                <th>Jumlah Pemakaian (hanya angka)</th>

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
                                                    <td>{{ $raw->quantity }}</td>
                                                    <td>{{ $raw->material_type }}</td>

                                                    <td>
                                                        @if ($raw->quantity == 0)
                                                            <input type="number" placeholder="Stok Kosong"
                                                                class="form-control" readonly>
                                                        @else
                                                            <input class="form-control"
                                                                name="quantity_used[{{ $raw->material_code }}]"
                                                                type="number">

                                                            <x-input-error :messages="$errors->get(
                                                                'quantity_used.' . $raw->material_code,
                                                            )" class="text-danger" />
                                                        @endif

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
                            <x-input-error :messages="$errors->get('quantity_used')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <select name="production_type" class="form-control" id="">
                                <option value="">=== Pilih Tipe Produksi ===</option>
                                <option value="per_day">Per Hari</option>
                                <option value="per_week">Per Minggu</option>
                                <option value="per_month">Per Bulan</option>
                            </select>
                            <x-input-error :messages="$errors->get('production_type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <input type="number" name="target_total" class="form-control"
                                value="{{ old('target_total') }}" placeholder="Masukan jumlah target total produk"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('target_total')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Produksi Produk</strong></label>
                            <input type="date" name="production_date" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('production_date')" class="text-danger" />
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah Data</button>
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
    document.getElementById('products').addEventListener('change', function() {
        var products = this.value;
        const showVariant = document.getElementById('showSelectVariant');
        const variantSelect = document.getElementById('variantSelect');
        const showVariantCode = document.getElementById('showVariantCode');

        // // ✅ RESET DULU
        variantSelect.innerHTML = '<option value="">=== Pilih Variant Produk ===</option>';
        showVariantCode.value = '';

        if (!products) {
            document.getElementById('showVariant').value = '';
            return;
        }

        fetch('/get_variant/' + products)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Variant not found');
                }
                return response.json();
            }).then(res => {
                variantSelect.innerHTML = '<option value="">=== Pilih Variant Produk ===</option>';
                if (res.data && res.data.length > 0) {

                    res.data.forEach(function(item) {
                        variantSelect.innerHTML +=
                            `<option value="${item.name}">${item.name}</option>`;
                    })
                    showVariant.style.display = "block";

                } else {
                    showVariant.style.display = "none";
                }
            })
    });


    document.getElementById('variantSelect').addEventListener('change', function() {
        let variantSelect = this.value;
        let products = document.getElementById('products').value;

        const showVariantCode = document.getElementById('showVariantCode');

        showVariantCode.value = '';
        if (!variantSelect || !products) return;

        fetch('/get_variant_code/' + products + '/' + variantSelect)
            .then(response => {
                if (!response.ok) {
                    showVariantCode.value = '';
                }
                return response.json();
            }).then(res => {

                // kosongkan dulu untuk safety
                showVariantCode.value = '';

                if (res.data && res.data.variant_code !== null && res.data.variant_code !== '') {
                    showVariantCode.value = res.data.variant_code;
                }
            }).catch(() => {
                showVariantCode.value = '';
            });
    })
</script>


</html>
