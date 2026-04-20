<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Kategori</title>
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
                    <h4>Ubah Data Store</h4>
                    <form id="formGeneralMaster" action="{{ route('edit_store', $store->store_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><strong>Nama Store</strong></label>
                            <input type="text" name="store_name" class="form-control"
                                value="{{ $store->store_name }}" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('store_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Lokasi</strong></label>
                            <input type="text" name="location" class="form-control" value="{{ $store->location }}"
                                id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('location')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kepala Store saat ini</strong></label>
                            <input type="text" class="form-control"
                                value="{{ $head_store->nik . ' - ' . $head_store->name }}" id="inputEmail4"
                                autocomplete="off" readonly>

                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Kepala Store</strong></label>
                            @if ($employee->isNotEmpty())
                                <select class="form-control" name="head_of_branch" id="">
                                    <option value="">== Pilih Kepala Store ==</option>
                                    @foreach ($employee as $emp)
                                        <option value="{{ $emp->nik }}">
                                            {{ $emp->nik . ' - ' . $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-danger">Tidak ada karyawan</p>
                            @endif
                            <x-input-error :messages="$errors->get('head_of_branch')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Latitude</strong></label>
                            <input type="text" name="latitude" class="form-control" value="{{ $store->latitude }}"
                                placeholder="Masukan kode latitude" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('latitude')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Longitude</strong></label>
                            <input type="text" name="longitude" class="form-control" value="{{ $store->longitude }}"
                                placeholder="Masukan kode longitude" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('longitude')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $store->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>

                </div>
                <br>
                <br>
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
