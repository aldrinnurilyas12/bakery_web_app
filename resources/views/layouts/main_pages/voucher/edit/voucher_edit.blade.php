<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Vouchers</title>
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
                    <h4>Ubah Data voucher</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('voucher_edit', $vouchers->voucher_code) }}"
                        method="POST" enctype="multipart/form-data">
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
                            <x-input-error :messages="$errors->get('voucher_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Jumlah nominal (Opsional)</strong></label>
                            <br>
                            <small style="font-style: oblique;">Jika sudah pakai jumlah nominal maka opsi Diskon tidak
                                berlaku/tidak diisi</small>
                            <input id="nominalInput" type="text" name="nominal" class="form-control"
                                value="{{ $vouchers->nominal }}" placeholder="Masukan jumlah nominal"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Diskon (opsional)</strong></label>
                            <input id="discountInput" type="text" name="discount" class="form-control"
                                value="{{ $vouchers->discount }}" placeholder="Masukan discount" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Minimal transaksi</strong></label>
                            <input type="text" name="min_transaction" class="form-control"
                                value="{{ $vouchers->min_transaction }}" placeholder="Masukan minimal transaksi"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('min_transaction')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Voucher</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ $vouchers->quota }}"
                                placeholder="Masukan kuota voucher min : 2 " autocomplete="off">
                            <x-input-error :messages="$errors->get('quota')" class="text-danger" />
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
                            <x-input-error :messages="$errors->get('status')" class="text-danger" />
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
                            <x-input-error :messages="$errors->get('voucher_type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku voucher</strong></label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $vouchers->start_date ? $start_date->format('Y-m-d') : null) }}"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('start_datex ')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku voucher</strong></label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $vouchers->end_date ? $end_date->format('Y-m-d') : null) }}"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $vouchers->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $vouchers->updated_by ?: '-' }}"
                                readonly>
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


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nominalInput = document.getElementById("nominalInput");
        const discountInput = document.getElementById("discountInput");

        nominalInput.addEventListener("input", function() {
            if (nominalInput.value.trim() !== "") {
                discountInput.value = "";
                discountInput.disabled = true;
            } else {
                discountInput.disabled = false;
            }
        });

        discountInput.addEventListener("input", function() {
            if (discountInput.value.trim() !== "") {
                nominalInput.value = "";
                nominalInput.disabled = true;
            } else {
                nominalInput.disabled = false;
            }
        });
    });
</script>

</html>
