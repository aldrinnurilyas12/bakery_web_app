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
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('product_waste_save') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text"
                                value="{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}"
                                class="form-control" autocomplete="off" readonly>
                        </div>
                        <hr class="menu">
                        <div class="category">
                            <div class="form-group">
                                <label><strong>Produk Daily</strong></label>
                                <select name="product_daily" class="form-control" id="productDaily" required>
                                    <option value="">=== Pilih ===</option>
                                    @foreach ($products_daily as $item)
                                        <option value="{{ $item->daily_code }}">
                                            {{ $item->daily_code . ' - ' . $item->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('product_daily')" class="text-danger" />
                            </div>
                        </div>


                        <div class="group-product">
                            <div class="form-group">
                                <input id="showProduct" type="text" value="{{ old('product') }}" class="form-control"
                                    name="product_code" hidden>
                                <input id="showVariant" type="text" value="{{ old('variant') }}" class="form-control"
                                    name="variant_code" hidden>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Jumlah Produk</strong></label>
                            <input type="text" class="form-control" id="showStock"
                                value="{{ old('received_quantity') }}" placeholder="Jumlah produk akan muncul"
                                autocomplete="off" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal terima produk</strong></label>
                            <input type="text" class="form-control" id="showReceivedDate"
                                placeholder="Tanggal akan muncul disini" autocomplete="off" readonly>
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
                            <input type="number" class="form-control" name="waste_type[WASTE10010]"
                                placeholder="0">
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


                        <div id="waste-form" class="waste-form-input">

                            <div class="form-group">
                                <label for=""><strong>Notes <small class="text-danger">(*Jika ada produk
                                            yang
                                            mengalami
                                            kerusakan)</small></strong></label>
                                <textarea class="form-control" name="reason" id="" cols="30" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label><strong>Attachment File <small class="text-danger"> (*Wajib jika produk
                                            tidak
                                            sesuai)</small></strong></label>
                                <input type="file" name="attachment_files" class="form-control">
                            </div>
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

<script>
    document.getElementById('productDaily').addEventListener('change', function() {
        var productSelect = this.value;

        if (!productSelect) {
            document.getElementById('showStock').value = '';
            return;
        }

        fetch('/get_qty_product/' + productSelect).then(response => {
            if (!response.ok) {
                throw new Error('Stock not found');
            }
            return response.json();
        }).then(data => {
            if (data.data.received_quantity) {
                document.getElementById('showStock').value = data.data.received_quantity;
                document.getElementById('showProduct').value = data.data.product;
                document.getElementById('showVariant').value = data.data.variant;
                document.getElementById('showReceivedDate').value = data.data.received_date;
            }
        })
    })

    document.addEventListener('DOMContentLoaded', function() {

        const stockInput = document.getElementById('showStock');
        const wasteInputs = document.querySelectorAll('input[name^="waste_type"]');

        function validateWaste() {
            let stock = parseInt(stockInput.value) || 0;

            // reset semua error dulu
            wasteInputs.forEach(input => {
                let errorEl = input.nextElementSibling;
                if (errorEl && errorEl.classList.contains('error-text')) {
                    errorEl.textContent = '';
                }
            });

            let runningTotal = 0;

            wasteInputs.forEach(input => {
                let value = parseInt(input.value) || 0;
                runningTotal += value;

                let errorEl = input.nextElementSibling;

                // buat elemen error kalau belum ada
                if (!errorEl || !errorEl.classList.contains('error-text')) {
                    errorEl = document.createElement('small');
                    errorEl.classList.add('text-danger', 'error-text');
                    input.parentNode.appendChild(errorEl);
                }

                // ❗ cek apakah input ini yang bikin over
                if (runningTotal > stock && value > 0) {
                    errorEl.textContent = `Melebihi stok! Sisa hanya ${stock - (runningTotal - value)}`;
                }
            });
        }

        wasteInputs.forEach(input => {
            input.addEventListener('input', validateWaste);
        });

    });
</script>



<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>


</html>
