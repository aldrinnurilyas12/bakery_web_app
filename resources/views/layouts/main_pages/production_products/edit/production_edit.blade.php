<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Bahan Baku</title>
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
                    <h4>Ubah Data Production</h4>
                    <hr>
                    <form action="{{ route('production_edit', $production->production_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label><strong>Kode Produksi</strong></label>
                            <input class="form-control" type="text" value="{{ $production->production_code }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            <select name="product" class="form-control" id="">
                                @foreach ($products as $item)
                                    <option value="{{ $item->product_code }}"
                                        {{ $item->product_code == $production->product_code ? 'selected' : '' }}>
                                        {{ $item->product_code . ' - ' . $item->product }}</option>
                                @endforeach

                            </select>
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
                                            @php
                                                $raw_material_usages = DB::table('raw_material_usages')
                                                    ->where('production_code', $production->production_code)
                                                    ->pluck('raw_material')
                                                    ->map(fn($v) => trim(strtolower($v))); // Collection untuk checkbox

                                                // Ambil quantity_used untuk semua raw_material di production ini
                                                $quantity_usages = DB::table('raw_material_usages as r')
                                                    ->leftJoin(
                                                        'raw_material as rw',
                                                        'r.raw_material',
                                                        '=',
                                                        'rw.material_code',
                                                    )
                                                    ->where('r.production_code', $production->production_code)
                                                    ->select('r.raw_material', 'r.quantity_used')
                                                    ->get()
                                                    ->keyBy('raw_material'); // jadikan key by material code supaya gampang lookup
                                            @endphp
                                            @foreach ($raw_materials as $raw)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <input type="checkbox" class="allowed-checkbox"
                                                            name="raw_material[]" value="{{ $raw->material_code }}"
                                                            data-target="qty-{{ $raw->material_code }}"
                                                            {{ $raw_material_usages->contains(trim(strtolower($raw->material_code))) ? 'checked' : '' }}>
                                                    </td>
                                                    <td>{{ '[' . $raw->material_code . '] ' . ' - ' . $raw->material_name }}
                                                    </td>
                                                    <td>{{ $raw->quantity }}</td>
                                                    <td>{{ $raw->material_type }}</td>

                                                    <td>
                                                        <input class="form-control quantity-input" type="number"
                                                            id="qty-{{ $raw->material_code }}"
                                                            name="quantity_used[{{ $raw->material_code }}]"
                                                            value="{{ $quantity_usages[$raw->material_code]->quantity_used ?? 0 }}"
                                                            {{ !$raw_material_usages->contains(trim(strtolower($raw->material_code))) ? 'disabled' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <select name="production_type" class="form-control" id="">
                                <option value="per_day"
                                    {{ $production->production_type == 'per_day' ? 'selected' : '' }}>
                                    Per Hari</option>
                                <option value="per_week"
                                    {{ $production->production_type == 'per_week' ? 'selected' : '' }}>
                                    Per Minggu</option>
                                <option value="per_month"
                                    {{ $production->production_type == 'per_month' ? 'selected' : '' }}>
                                    Per Bulan</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <input type="text" name="target_total" class="form-control"
                                value="{{ $production->target_total }}"
                                placeholder="Masukan jumlah target total produk" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Produksi Produk</strong></label>
                            <input
                                value="{{ old('production_date', $production->production_date ? $production_date->format('Y-m-d') : null) }}"
                                type="date" name="production_date" class="form-control" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
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
    document.querySelectorAll('.allowed-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const target = document.getElementById(this.dataset.target);
            if (this.checked) {
                target.disabled = false;
            } else {
                target.disabled = true;
                target.value = 0; // reset supaya tidak “keisi sendiri”
            }
        });
    });
</script>




</html>
