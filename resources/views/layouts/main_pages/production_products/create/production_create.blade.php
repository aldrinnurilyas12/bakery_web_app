<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produksi Produk</title>
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
                            <li>Penggunaan bahan baku harus mengikuti panduan pada tabel Ingredients</li>
                            <li>Produk yang sudah dicatat produksi produk tidak boleh dihapus, hanya dapat diubah dengan
                                alasan valid dan tercatat.</li>
                            <li>Jika stok kosong segera melakukan Purchase Order</li>
                            <li>Jadwal untuk melakukan input data Produksi Produk pada jam 05.00 s.d 07.30</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('master_production_product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if($products->isNotEmpty())
                            <div style="color: black; height: 400px;background: white;overflow: auto;" class="modal-body">
                                <div class="table-responsive">
                                    <table style="font-size: 14px; color:black;" class="table" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pilih</th>
                                                <th>Produk</th>
                                                <th>Tipe Produk</th>
                                                <th>Total Target Produksi (*per Produk)</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php $no = 1; ?>
                                            @foreach ($products as $prd)
                                                @php
                                                    $key = $prd->product_code . '_' . $prd->variant_code;
                                                @endphp
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td> <input class="allowed-checkbox" type="checkbox"
                                                            name="product[{{ $key }}]"
                                                            value="{{ $prd->product_code }}"
                                                            {{ old('product.' . $key) == $prd->product_code ? 'checked' : '' }}>
                                                        <input type="hidden" name="variant[{{ $key }}]"
                                                            value="{{ $prd->variant_code }}">
                                                    </td>
                                                    <td>{{ $prd->product_name }}
                                                        <span style="font-weight:bold;">
                                                            @if ($prd->product_variant == 'Y')
                                                                {{ '[' . $prd->category . ']' }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>{{ $prd->product_type }}</td>
                                                    <td> <input class="form-control"
                                                            name="qty_target_total[{{ $key }}]" type="number"
                                                            value="{{ old('qty_target_total.' . $key) }}">
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <x-input-error :messages="$errors->get('product')" class="text-danger" />
                            </div>
                        @else
                             <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                    class="empty-transaction">

                                    <div style="display: flex;" class="empty-content">
                                        <div style="display: flex; gap:20px;margin:auto;" >
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                alt="">
                                            <div style="display: block;align-content: center;" class="text-content">
                                                <h3>Data produk belum ada</h3>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        @endif

                        <br>
                        <br>

                        @if($raw_materials->isNotEmpty())
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
                                                    <th>Stok</th>
                                                    <th>Unit</th>
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
                                                                -
                                                            @else
                                                                <input class="allowed-checkbox" type="checkbox"
                                                                    name="raw_material[{{ $raw->material_code }}]"
                                                                    value="{{ $raw->material_code }}"
                                                                    {{ old('raw_material.' . $raw->material_code) == $raw->material_code ? 'checked' : '' }}>
                                                            @endif
                                                        </td>
                                                        <td>{{ '[' . $raw->material_code . '] ' . ' - ' . $raw->material_name }}
                                                        </td>
                                                        <td>
                                                            @if($raw->quantity == 0)
                                                            <span>-</span>
                                                            @else
                                                            {{ $raw->quantity }}
                                                            <input type="hidden" id="stock_{{ $raw->material_code }}"
                                                                value="{{ $raw->quantity }}">
                                                            {{ $raw->unit_name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="unit[{{ $raw->material_code }}]" id="">
                                                            <option value="">=== Pilih unit ===</option>
                                                            @foreach ($units as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->unit_name }}
                                                                </option>
                                                            @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            @if ($raw->quantity == 0)
                                                                <input type="number" placeholder="Stok Kosong"
                                                                    class="form-control" readonly>
                                                            @else
                                                                <input id="qty_{{ $raw->material_code }}"
                                                                    class="form-control"
                                                                    name="quantity_used[{{ $raw->material_code }}]"
                                                                    type="number"
                                                                    oninput="validateSingle('{{ $raw->material_code }}')"
                                                                    value="{{ old('quantity_used.' . $raw->material_code) }}">

                                                                <small id="error_{{ $raw->material_code }}"
                                                                    style="color:red; display:none;">
                                                                    Jumlah melebihi stok
                                                                </small>

                                                                <x-input-error :messages="$errors->get(
                                                                    'quantity_used.' . $raw->material_code,
                                                                )" class="text-danger" />
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('raw_material')" class="text-danger" />
                                <x-input-error :messages="$errors->get('quantity_used')" class="text-danger" />
                            </div>
                        @else
                             <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                    class="empty-transaction">

                                    <div style="display: flex;" class="empty-content">
                                        <div style="display: flex; gap:20px;margin:auto;" >
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                alt="">
                                            <div style="display: block;align-content: center;" class="text-content">
                                                <h3>Data bahan baku belum ada</h3>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        @endif

                        @if($products->isNotEmpty())
                            <div class="form-group">
                                <label><strong>Tipe Produksi</strong></label>
                                <select name="production_type" class="form-control" id="" required>
                                    <option value="per_day" {{ old('production_type') == 'per_day' ? 'selected' : '' }}>
                                        Per Hari
                                    </option>

                                    <option value="per_week" {{ old('production_type') == 'per_week' ? 'selected' : '' }}>
                                        Per Minggu
                                    </option>

                                    <option value="per_month"
                                        {{ old('production_type') == 'per_month' ? 'selected' : '' }}>
                                        Per Bulan
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('production_type')" class="text-danger" />
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Produksi Produk</strong></label>
                                <input type="date" name="production_date" class="form-control" autocomplete="off"
                                    value="{{ old('production_date') }}" required>
                                <x-input-error :messages="$errors->get('production_date')" class="text-danger" />
                            </div>

                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        @endif
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }


    #showIngredients {
        display: none;
    }
</style>

<script>
    document.getElementById('products').addEventListener('change', function() {
        var products = this.value;
        const showVariant = document.getElementById('showSelectVariant');
        const variantSelect = document.getElementById('variantSelect');
        const showVariantCode = document.getElementById('showVariantCode');

        const showRawMaterial = document.getElementById('showRawMaterial');
        const showIngredients = document.getElementById('showIngredients');

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

        fetch('/get_ingredients/' + products)
            .then(response => {
                if (!response.ok) throw new Error('Ingredients not found');
                return response.json();

            }).then(res => {
                const tbody = document.getElementById('ingredientsTbody');
                tbody.innerHTML = '';
                if (res.data && res.data.length > 0) {
                    res.data.forEach((item, index) => {
                        tbody.innerHTML +=
                            `<tr>
                            <td>${index + 1}</td>
                       <td>${ item.material_name }</td>
                       <td>${ item.quantity  }  ${item.weight}</td>
                      </tr>`
                    });
                    showIngredients.style.display = "block";
                } else {
                    showIngredients.style.display = "none";
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




    function validateSingle(code) {
        let stock = parseInt(document.getElementById("stock_" + code).value) || 0;
        let input = document.getElementById("qty_" + code);
        let value = parseInt(input.value) || 0;

        let errorEl = document.getElementById("error_" + code);

        if (value > stock) {
            input.style.border = "2px solid red";
            input.setCustomValidity("Melebihi stok");

            if (errorEl) errorEl.style.display = "block";
        } else {
            input.style.border = "";
            input.setCustomValidity("");

            if (errorEl) errorEl.style.display = "none";
        }

        // update tombol submit
        updateSubmitButton();
    }


    function updateSubmitButton() {
        let inputs = document.querySelectorAll('[id^="qty_"]');
        let btn = document.getElementById("btnMaster");

        let hasError = false;

        inputs.forEach(function(input) {
            if (input.style.border.includes("red")) {
                hasError = true;
            }
        });

        if (hasError) {
            btn.type = "button";
            btn.className = "btn-general-secondary";
        } else {
            btn.type = "submit";
            btn.className = "btn-general";
        }
    }
</script>


</html>
