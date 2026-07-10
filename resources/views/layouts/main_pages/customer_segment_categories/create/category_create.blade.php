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
                    <h4>Tambah Data Kategori Segment Pelanggan</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Misal: Min Transaksi > Rp. 200.000 dan total transaksi > 20 maka masuk kategori Champions</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('customer_segment.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Segment</strong></label>
                            <input type="text" name="segment_name" class="form-control"
                                value="{{ old('segment_name') }}" placeholder="Masukan nama kategori" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('segment_name')" class="text-danger" />
                        </div>

                        <div style="display:flex; gap:20px;" class="form-group">

                             <div class="form-flex">
                            <label><strong>Minimum Transaksi</strong></label>
                            <input type="number" name="min_transaction" class="form-control" value="{{ old('min_transaction') }}"
                                placeholder="Masukan minimal Transaksi" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('min_transaction')" class="text-danger" />
                             </div>

                            <div class="form-flex">
                                <label><strong>Maksimal Transaksi</strong></label>
                                <input type="number" name="max_transaction" class="form-control" value="{{ old('max_transaction') }}"
                                    placeholder="Masukan maksimal transaksi" id="inputEmail4" autocomplete="off">
                                <x-input-error :messages="$errors->get('max_transaction')" class="text-danger" />
                            </div>
                        </div>

                        <div style="display:flex; gap:20px;" class="form-group"> 
                            
                             <div class="form-flex">
                            <label><strong>Minimum Belanja</strong></label>
                            <input type="number" name="min_spent" class="form-control" value="{{ old('min_spent') }}"
                                placeholder="Masukan minimal belanja" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('min_spent')" class="text-danger" />
                             </div>
                       
                            <div class="form-flex">
                                <label><strong>Maksimal Belanja</strong></label>
                                <input type="number" name="max_spent" class="form-control" value="{{ old('max_spent') }}"
                                    placeholder="Masukan maksimal belanja" id="inputEmail4" autocomplete="off">
                                <x-input-error :messages="$errors->get('max_spent')" class="text-danger" />
                            </div>
                        </div>

                        <div style="display:flex; gap:20px;" class="form-group">
                            <div class="form-flex">
                                <label><strong>Recency (Time/Hari)</strong></label>
                                <input type="number" name="recency" class="form-control" value="{{ old('recency') }}"
                                    placeholder="Waktu dalam transaksi" id="inputEmail4" autocomplete="off">
                                <x-input-error :messages="$errors->get('recency')" class="text-danger" />
                            </div>

                            <div class="form-indicatr">
                                <label><strong>Indikasi Recency</strong></label>
                                <select name="indicator" class="form-control" id="">
                                    <option value="">=== Pilih indikator ===</option>
                                    <option value=">">Lebih dari (>)</option>
                                    <option value="<">Kurang dari (<)</option>
                                    <option value="=">Sama dengan (=)</option>
                                </select>
                                <x-input-error :messages="$errors->get('recency')" class="text-danger" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Warna Segment</strong></label>
                            <input type="color" name="color" class="form-control" value="{{ old('color') }}" autocomplete="off">
                            <x-input-error :messages="$errors->get('color')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Urutan Segment</strong></label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}"
                                placeholder="Urutan segment, Misal : urutan 1 => Champions" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('sort_order')" class="text-danger" />
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
