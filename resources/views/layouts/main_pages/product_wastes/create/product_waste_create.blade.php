<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk Waste</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Data Produk Waste</h4>
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
                            <li>Ketika input data product waste pilih salah satu dari kedua kategori (Production Product
                                atau Products Daily).
                            </li>

                        </ul>
                    </div>
                    <form action="{{ route('product_waste_save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text"
                                value="{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}"
                                class="form-control" autocomplete="off" readonly>
                        </div>
                        <hr class="menu">
                        <div class="category">
                            <label for=""><strong>Pilih Kategori</strong></label>
                            <br>
                            <small class="text-danger">*Hanya pilih salah satu</small>
                            <hr class="hr-menu">

                            <div class="form-group">
                                <label><strong>Kode Produksi</strong></label>
                                <select name="production_code" class="form-control" id="">
                                    <option value="">=== Pilih ===</option>
                                    @foreach ($production as $item)
                                        <option value="{{ $item->production_code }}">
                                            {{ $item->production_code . ' - ' . $item->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label><strong>Produk Daily</strong></label>
                                <select name="product_daily" class="form-control" id="">
                                    <option value="">=== Pilih ===</option>
                                    @foreach ($product_daily as $item)
                                        <option value="{{ $item->daily_code }}">
                                            {{ $item->daily_code . ' - ' . $item->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <br>
                        <label for=""><strong>Kategori Waste</strong></label>
                        <hr class="hr-menu">
                        <div class="form-group">
                            <label for=""><strong>Kerusakan</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1001]" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Expired</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1002]" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kelebihan Produksi</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1003]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Operasional</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1004]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Keamanan Produk</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1005]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Pengemasan Produk</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1006]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Tampilan & Penanganan Masalah</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1007]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Produk Tidak Terjual</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1008]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Komplain/Pengembalian Produk oleh
                                    Pelanggan</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1009]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Distribusi</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE10010]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Mesin Produksi</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1011]" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kesalahan Manusia (Human Error)</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1012]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Major</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1013]" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Lainnya</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1014]" placeholder="0">
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>

@if (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>




</html>
