<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Edit Produk Waste</title>
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
                    <h4>Edit Data Produk Waste</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Produk yang diinput sebagai product waste harus sudah dipastikan tidak layak jual, baik
                                karena gagal produksi, rusak, kedaluwarsa, atau tidak memenuhi standar kualitas..</li>
                            <li>Setiap input product waste wajib memiliki satu kategori waste yang valid sesuai daftar
                                kategori yang tersedia di sistem (tidak boleh kosong atau bebas input).
                            </li>
                            <li>Jumlah produk yang diproduksi produk harus valid, tidak boleh negatif atau melebihi
                                kapasitas
                                produksi produk harian.</li>
                            <li>Tanggal waste harus sama dengan atau maksimal H+1 dari tanggal kejadian, untuk menjaga
                                akurasi laporan harian dan kontrol produksi.</li>
                            <li>Data product waste hanya dapat diinput oleh karyawan yang berwenang (terdaftar di
                                sistem), dan wajib melalui proses persetujuan (approval) sebelum data dianggap final.
                            </li>

                        </ul>
                    </div>

                    <form action="{{ route('product_waste_edit', $product_wastes->waste_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label><strong>Kode Produk Waste</strong></label>
                            <input type="text" class="form-control" value="{{ $product_wastes->waste_code }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Produksi</strong></label>
                            <input type="text" class="form-control"
                                value="{{ $product_wastes->production_code . ' - ' . $product_wastes->product_name }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Kerusakan</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1001']->waste_code }}" name="waste_type[WASTE1001]"
                                placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Expired</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1002']->waste_code }}" name="waste_type[WASTE1002]"
                                placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kelebihan Produksi</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1003']->waste_code }}" name="waste_type[WASTE1003]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Operasional</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1004']->waste_code }}" name="waste_type[WASTE1004]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Keamanan Produk</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1005']->waste_code }}" name="waste_type[WASTE1005]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Pengemasan Produk</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1006']->waste_code }}" name="waste_type[WASTE1006]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Tampilan & Penanganan Masalah</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1007']->waste_code }}" name="waste_type[WASTE1007]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Produk Tidak Terjual</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1008']->waste_code }}" name="waste_type[WASTE1008]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Komplain/Pengembalian Produk oleh Pelanggan</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1009']->waste_code }}" name="waste_type[WASTE1009]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Distribusi</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1010']->waste_code }}" name="waste_type[WASTE10010]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Mesin Produksi</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1011']->waste_code }}" name="waste_type[WASTE1011]"
                                placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kesalahan Manusia (Human Error)</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1012']->waste_code }}" name="waste_type[WASTE1012]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Major</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1013']->waste_code }}" name="waste_type[WASTE1013]"
                                placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Lainnya</strong></label>
                            <input type="number" class="form-control"
                                value="{{ $product_wastes['WASTE1014']->waste_code }}" name="waste_type[WASTE1014]"
                                placeholder="0">
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
