<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Vouchers</title>
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
                    <h4>Ubah Data voucher</h4>
                    <hr>
                    <form action="{{ route('voucher_edit', $vouchers->voucher_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><strong>Kode Voucher</strong></label>
                            <input type="text" class="form-control" value="{{ $vouchers->voucher_code }}"
                                autocomplete="off" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Voucher</strong></label>
                            <input type="text" name="voucher_name" class="form-control"
                                value="{{ $vouchers->voucher_name }}" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Jumlah nominal (Opsional)</strong></label>
                            <br>
                            <small style="font-style: oblique;">Jika sudah pakai jumlah nominal maka opsi Diskon tidak
                                berlaku/tidak diisi</small>
                            <input type="text" name="nominal" class="form-control" value="{{ $vouchers->nominal }}"
                                placeholder="Masukan jumlah nominal" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon (opsional)</strong></label>
                            <input type="text" name="discount" class="form-control" value="{{ $vouchers->discount }}"
                                placeholder="Masukan discount" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Minimal transaksi</strong></label>
                            <input type="text" name="min_transaction" class="form-control"
                                value="{{ $vouchers->min_transaction }}" placeholder="Masukan minimal transaksi"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Voucher</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ $vouchers->quota }}"
                                placeholder="Masukan kuota voucher min : 2 " autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Status Voucher</strong></label>
                            <select name="status" class="form-control" id="">
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $sts->id == $vouchers->status ? 'selected' : '' }}>
                                        {{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Jenis Vocuher</strong></label>
                            <select name="voucher_type" id="" class="form-control">
                                <option value="regular" {{ $vouchers->voucher_type == 'regular' ? 'selected' : '' }}>
                                    Regular</option>

                                <option value="birth_day"
                                    {{ $vouchers->voucher_type == 'birth_day' ? 'selected' : '' }}>
                                    Ulang Tahun</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku voucher</strong></label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $vouchers->start_date ? $start_date->format('Y-m-d') : null) }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku voucher</strong></label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $vouchers->end_date ? $end_date->format('Y-m-d') : null) }}"
                                autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Voucher</button>
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
