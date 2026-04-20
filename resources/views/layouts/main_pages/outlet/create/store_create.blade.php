<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk</title>
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
                    <h4>Tambah Data Store</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('store.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Store</strong></label>
                            <input type="text" name="store_name" class="form-control" value="{{ old('store_name') }}"
                                placeholder="Masukan nama store" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('store_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Lokasi</strong></label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}"
                                placeholder="Masukan lokasi store" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('location')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Kepala Store</strong></label>
                            @if ($employee->isNotEmpty())
                                <select class="form-control" name="head_of_branch" id="">
                                    <option value="">=== Pilih Kepala Store ===</option>
                                    @foreach ($employee as $emp)
                                        <option value="{{ $emp->nik }}">{{ $emp->nik . ' - ' . $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('head_of_branch')" class="text-danger" />
                            @else
                                <p class="text-danger">Data karyawan tidak ada</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label><strong>Latitude</strong></label>
                            <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}"
                                placeholder="Masukan kode latitude" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('latitude')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Longitude</strong></label>
                            <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}"
                                placeholder="Masukan kode longitude" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('longitude')" class="text-danger" />
                        </div>
                        @if ($employee->isNotEmpty())
                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        @endif
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

@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@elseif (Session::has('failed_message'))
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

</html>
