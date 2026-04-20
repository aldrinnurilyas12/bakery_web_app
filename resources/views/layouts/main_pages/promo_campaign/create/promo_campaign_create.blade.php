<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Promo Campaign</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
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
                    <h4>Tambah Data Promo Campaign</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Promo dan voucher hanya dapat dibuat oleh user dengan role Admin atau Marketing.</li>
                            <li>Setiap promo dan voucher wajib memiliki periode aktif yang jelas (tanggal mulai dan
                                berakhir).</li>
                            <li>Kode voucher harus unik dan tidak boleh sama dengan kode voucher lain yang masih aktif.
                            </li>
                            <li>Nilai promo atau voucher harus valid (memiliki nilai minimal transaksi).</li>
                            <li>Promo dan voucher yang sudah digunakan dalam transaksi tidak boleh dihapus, hanya dapat
                                dinonaktifkan.</li>

                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('master_promo_campaign.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label style="margin: 0;"><strong>Masukan kode promo</strong></label>
                            <br>
                            <small style="color:gray;margin:0;">*Tidak pakai spasi (contoh : PROMO25DES)</small>
                            <input type="text" name="promo_code" class="form-control" value="{{ old('promo_code') }}"
                                placeholder="Masukan kode promo" autocomplete="off">
                            <x-input-error :messages="$errors->get('promo_code')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan nama promo</strong></label>
                            <input type="text" name="promo_name" class="form-control" value="{{ old('promo_name') }}"
                                placeholder="Masukan nama promo" autocomplete="off">
                            <x-input-error :messages="$errors->get('promo_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah minimal transaksi</strong></label>
                            <input type="text" name="min_transaction" class="form-control"
                                value="{{ old('min_transaction') }}" placeholder="Masukan jumlah minimal transaksi"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('min_transaction')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi </strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Promo</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ old('quota') }}"
                                placeholder="Masukan kuota promo min : 2 " autocomplete="off">
                            <x-input-error :messages="$errors->get('quota')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Status Promo</strong></label>
                            <select name="status" class="form-control" id="">
                                <option value="">=== Pilih Status ===</option>
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal promo</strong></label>
                            <input type="date" name="start_date" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir promo</strong></label>
                            <input type="date" name="end_date" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Foto/Gambar (opsional)</strong></label>
                            <input type="file" name="images" class="form-control" autocomplete="off">
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
