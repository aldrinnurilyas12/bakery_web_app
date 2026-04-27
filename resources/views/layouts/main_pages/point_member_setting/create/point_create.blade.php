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
                    <h4>Tambah Data Point Member</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Point digunakan saat pelanggan melakukan transaksi dan mempunyai member</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('point_member_setting.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Jumlah Point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('point')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal berlaku</strong></label>
                            <input type="date" name="start_date" class="form-control" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku</strong></label>
                            <input type="date" name="end_date" class="form-control" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
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
