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
                    <form action="{{ route('master_production_product.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            <select name="product" class="form-control" id="">
                                <option value="">=== Pilih Produk ===</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->product_code }}">
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
                                            @foreach ($raw_materials as $raw)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        @if ($raw->quantity == 0)
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
                        </div>

                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <select name="production_type" class="form-control" id="">
                                <option value="">=== Pilih Tipe Produksi ===</option>
                                <option value="per_day">Per Hari</option>
                                <option value="per_week">Per Minggu</option>
                                <option value="per_month">Per Bulan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Total Target Produksi Produk</strong></label>
                            <input type="text" name="target_total" class="form-control"
                                value="{{ old('target_total') }}" placeholder="Masukan jumlah target total produk"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Produksi Produk</strong></label>
                            <input type="date" name="production_date" class="form-control" autocomplete="off">
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




</html>
