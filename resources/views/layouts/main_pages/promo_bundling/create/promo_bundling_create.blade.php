<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Promo Bundling</title>
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
                    <h4>Tambah Promo Bundling</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                           <li>Nama bundling harus unik dan mudah dipahami.</li>
                            <li>Bundling harus terdiri dari minimal 2 produk.</li>
                            <li>Pastikan seluruh produk dalam bundling masih aktif dan tersedia.</li>
                            <li>Harga bundling harus lebih rendah atau sama dengan total harga produk satuan (sesuai kebijakan).</li>
                            <li>Tentukan periode mulai dan berakhir bundling dengan benar.</li>
                            <li>Pastikan stok bundling mengikuti stok produk dengan jumlah paling sedikit (jika menggunakan stok otomatis).</li>
                            <li>Unggah gambar bundling dengan rasio yang sesuai agar tampil optimal.</li>
                            <li>Periksa kembali data sebelum menyimpan karena bundling akan ditampilkan kepada pelanggan.</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('promo_bundling.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Promo Bundling</strong></label>
                            <input type="text" name="bundling_name" class="form-control"
                                value="{{ old('bundling_name') }}" placeholder="Masukan nama promo bundling" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('bundling_name')" class="text-danger" />
                        </div>

                         <div class="form-group">
                            <label><strong>Harga Promo</strong></label>
                            <input type="number" name="price" class="form-control" value="{{ old('price') }}"
                                placeholder="Masukan harga promo bundling" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('price')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Stok</strong></label>
                            <input type="number" name="quantity_promo" class="form-control" value="{{ old('quantity_promo') }}"
                                placeholder="Masukan quantity" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('quantity_promo')" class="text-danger" />
                        </div>

                         <div class="form-group">
                            <label><strong>Tanggal Berlaku Promo</strong></label>
                            <div style="display:flex; gap:20px;" class="form-flex">
                                <div class="form-inputt">
                                     <label><strong>Tanggal awal promo</strong></label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                                </div>

                                <span style="    align-content: center;align-self: center;margin-top:30px;" class="s_d">S/d</span>

                                 <div class="form-inputt">
                                     <label><strong>Tanggal akhir promo</strong></label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                                </div>
                            </div>
                        </div>

                        <br>
                                <label><strong>Pilih Produk</strong></label>
                                <div style="color: black; height: 300px;background: white;overflow: auto;"
                                    class="modal-body">
                                    <div class="table-responsive">
                                        <table style="font-size: 14px; color:black;" class="table" id="dataTable"
                                            width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pilih</th>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>

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
                                                        <td>

                                                                <input class="allowed-checkbox" type="checkbox"
                                                                    name="product[{{ $prd->product_code }}]"
                                                                    value="{{ $prd->product_code }}"
                                                                    {{ old('product.' . $prd->product_code) == $prd->product_code ? 'checked' : '' }}>
                                                                 <input type="hidden" name="variant[{{ $prd->variant_code }}]"
                                                                    value="{{ $prd->variant_code }}">
                                                        </td>
                                                        <td>{{ '[' . $prd->product_code . '] ' . ' - ' . $prd->product }}
                                                        </td>
                                                        <td>
                                                                <input id="qty_{{ $prd->product_code }}"
                                                                    class="form-control"
                                                                    name="quantity[{{ $prd->product_code }}]"
                                                                    type="number"
                                                                    oninput="validateSingle('{{ $prd->product_code }}')"
                                                                    value="{{ old('quantity.' . $prd->product_code) }}">

                                                                <small id="error_{{ $prd->product_code }}"
                                                                    style="color:red; display:none;">
                                                                    Jumlah melebihi stok
                                                                </small>

                                                                <x-input-error :messages="$errors->get(
                                                                    'quantity.' . $prd->product_code,
                                                                )" class="text-danger" />

                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                           
                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                           <textarea class="form-control" name="description" id="" cols="30" rows="5">

                           </textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Upload Gambar</strong></label>
                            <input class="form-control" type="file" name="images">
                            <x-input-error :messages="$errors->get('images')" class="text-danger" />
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
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
