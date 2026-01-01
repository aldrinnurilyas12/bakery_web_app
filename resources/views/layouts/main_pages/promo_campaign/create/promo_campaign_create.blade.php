<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Promo Campaign</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
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
                    <form action="{{ route('master_promo_campaign.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            <select class="form-control" name="product" id="">
                                <option value="">==== Pilih Produk ====</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->product_code }}">
                                        {{ $item->product }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label style="margin: 0;"><strong>Masukan kode promo</strong></label>
                            <br>
                            <small style="color:gray;margin:0;">*Tidak pakai spasi (contoh : PROMO25DES)</small>
                            <input type="text" name="promo_code" class="form-control" value="{{ old('promo_code') }}"
                                placeholder="Masukan kode promo" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan nama promo</strong></label>
                            <input type="text" name="promo_name" class="form-control" value="{{ old('promo_name') }}"
                                placeholder="Masukan nama promo" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah minimal transaksi</strong></label>
                            <input type="text" name="min_transaction" class="form-control"
                                value="{{ old('min_transaction') }}" placeholder="Masukan jumlah minimal transaksi"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi </strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Promo</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ old('quota') }}"
                                placeholder="Masukan kuota promo min : 2 " autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Status Promo</strong></label>
                            <select name="status" class="form-control" id="">
                                <option value="">=== Pilih Status ===</option>
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal promo</strong></label>
                            <input type="date" name="start_date" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir promo</strong></label>
                            <input type="date" name="end_date" class="form-control" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Promo</button>
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
