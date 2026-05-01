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
                            <li>Ketika input data product waste pilih salah satu dari kedua kategori (Production Product
                                atau Products Daily).
                            </li>

                        </ul>
                    </div>
                    <form id="formGeneralMaster"
                        action="{{ route('waste_distribution_save', $distribution->distribution_store_code) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label><strong>Store</strong></label>
                            <input type="text"
                                value="{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}"
                                class="form-control" autocomplete="off" readonly>
                        </div>
                        <hr class="menu">
                        <div class="category">

                            <input hidden class="form-control" type="text" value="{{ $distribution->distribution }}"
                                name="distribution_code" readonly>

                            <input hidden class="form-control" type="text"
                                value="{{ $distribution->distribution_store_code }}" name="distribution" readonly>

                        </div>

                        <div class="card-body">
                            <label for=""><strong>Informasi detail</strong></label>
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Kode Referensi</th>
                                            <th>Produk</th>
                                            <th>Total distribusi</th>
                                            <th>Total diterima</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        ?>
                                        <tr>
                                            <td>
                                                {{ $distribution->distribution_store_code }}
                                            </td>
                                            <td>
                                                {{ $distribution->product_name }}
                                                &nbsp;
                                                @if ($distribution->variant)
                                                    <span>[{{ $distribution->name }}]</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $distribution->quantity }}
                                            </td>
                                            <td>
                                                {{ $distribution->received_quantity }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <input type="text" value="{{ $distribution->product_code }}" name="product_code"
                            class="form-control" hidden readonly>
                        <input type="text" value="{{ $distribution->variant_code }}" name="variant_code"
                            class="form-control" hidden readonly>

                        <div class="form-group">
                            <label><strong>Total Produk Waste</strong></label>
                            <input id="rejectTotal" type="number"
                                value="{{ $distribution->quantity - $distribution->received_quantity }}" min="0"
                                name="reject_quantity" class="form-control" readonly required>
                        </div>

                        <hr>

                        <label for=""><strong>Kategori Produk Waste</strong></label>
                        <hr class="hr-menu">

                        <div class="form-group">
                            <label for=""><strong>Rejected</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1015]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Kerusakan</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1001]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Expired</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1002]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kelebihan Produksi</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1003]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Pengemasan Produk</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1006]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>


                        <div class="form-group">
                            <label for=""><strong>Masalah Distribusi</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE10010]"
                                placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Kesalahan Manusia (Human Error)</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1012]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Masalah Lainnya</strong></label>
                            <input type="number" class="form-control" name="waste_type[WASTE1014]" placeholder="0">
                            <div id="wasteError" class="text-danger mt-2" style="display:none;">
                                Total kategori waste tidak boleh melebihi Total Produk Waste.
                            </div>
                        </div>

                        <hr>

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
    document.addEventListener('DOMContentLoaded', function() {
        const rejectTotal = document.getElementById('rejectTotal');
        const wasteInputs = document.querySelectorAll('input[name^="waste_type"]');
        const form = document.querySelector('form');
        const btn = document.getElementById("btnMaster");

        // buat elemen error tiap input
        wasteInputs.forEach(input => {
            let errorEl = document.createElement('div');
            errorEl.className = 'text-danger mt-1 error-message';
            errorEl.style.display = 'none';
            input.parentNode.appendChild(errorEl);
        });

        function updateSubmitButton() {
            let hasError = false;

            wasteInputs.forEach(function(input) {
                if (input.classList.contains('is-invalid')) {
                    hasError = true;
                }
            });

            if (hasError) {
                btn.type = "button";
                btn.className = "btn-general-secondary";
            } else {
                btn.type = "submit";
                btn.className = "btn-general";
            }
        }

        function validateWaste(changedInput = null) {
            let max = Number(rejectTotal.value) || 0;

            let total = 0;
            wasteInputs.forEach(input => {
                total += Number(input.value) || 0;
            });

            // reset semua
            wasteInputs.forEach(input => {
                let errorEl = input.parentNode.querySelector('.error-message');
                input.classList.remove('is-invalid');
                errorEl.style.display = 'none';
                errorEl.innerText = '';
            });

            // jika melebihi
            if (total > max && changedInput) {
                let errorEl = changedInput.parentNode.querySelector('.error-message');
                changedInput.classList.add('is-invalid');
                errorEl.innerText = 'Input ini menyebabkan total melebihi batas (' + max + ')';
                errorEl.style.display = 'block';
            }

            // update tombol setiap validasi
            updateSubmitButton();
        }

        // realtime
        wasteInputs.forEach(input => {
            input.addEventListener('input', function() {
                validateWaste(input);
            });
        });

        rejectTotal.addEventListener('input', function() {
            validateWaste();
        });

        // validasi awal
        validateWaste();

        // submit
        form.addEventListener('submit', function(e) {
            let hasError = false;

            wasteInputs.forEach(input => {
                if (input.classList.contains('is-invalid')) {
                    hasError = true;
                }
            });

            if (hasError) {
                e.preventDefault();
            }
        });
    });
</script>

<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

</html>
