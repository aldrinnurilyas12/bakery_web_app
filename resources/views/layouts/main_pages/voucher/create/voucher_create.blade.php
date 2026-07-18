<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah E-Voucher</title>
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
                    <h4>Tambah Data Voucher</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>E-Voucher hanya dapat dibuat oleh user dengan role Admin atau Marketing.</li>
                            <li>Setiap E-Voucher wajib memiliki periode aktif yang jelas (tanggal mulai dan
                                berakhir).</li>
                            <li>Kode E-Voucher harus unik dan tidak boleh sama dengan kode voucher lain yang masih
                                aktif.
                            </li>
                            <li>Nilai E-Voucher harus valid (memiliki nilai minimal transaksi dan kuota
                                E-Voucher).</li>
                            <li>E-Voucher yang sudah digunakan dalam transaksi tidak boleh dihapus, hanya dapat
                                dinonaktifkan.</li>

                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('master_voucher.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan nama voucher</strong></label>
                            <input type="text" name="voucher_name" class="form-control"
                                value="{{ old('voucher_name') }}" placeholder="Masukan nama voucher" autocomplete="off"
                                required>
                            <x-input-error :messages="$errors->get('voucher_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah nominal (Opsional)</strong></label>
                            <br>
                            <small style="font-style: oblique;">Jika sudah pakai jumlah nominal maka opsi Diskon tidak
                                berlaku/tidak diisi</small>
                            <input id="nominalInput" type="number" name="nominal" class="form-control"
                                value="{{ old('nominal') }}" placeholder="Masukan jumlah nominal" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan Diskon (%)</strong></label>
                            <input id="discountInput" type="number" name="discount" class="form-control"
                                value="{{ old('discount') }}" placeholder="Masukan discount" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan minimal transaksi (Rp)</strong></label>
                            <input type="number" name="min_transaction" class="form-control"
                                value="{{ old('min_transaction') }}" placeholder="Masukan minimal transaksi"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('min_transaction')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Voucher</strong></label>
                            <input type="number" name="quota" class="form-control" value="{{ old('quota') }}"
                                placeholder="Masukan kuota voucher min : 1 " autocomplete="off" required>
                            <x-input-error :messages="$errors->get('quota')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Jenis Voucher</strong></label>
                            <select name="voucher_type" id="" class="form-control" required>
                                <option value="">=== Pilih Jenis Voucher ===</option>
                                <option value="regular">Regular</option>
                                <option value="birth_day">Ulang Tahun</option>
                            </select>
                            <x-input-error :messages="$errors->get('voucher_type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku voucher</strong></label>
                            <input type="date" name="start_date" class="form-control" autocomplete="off" required>
                            <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku voucher</strong></label>
                            <input type="date" name="end_date" class="form-control" autocomplete="off" required>
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
