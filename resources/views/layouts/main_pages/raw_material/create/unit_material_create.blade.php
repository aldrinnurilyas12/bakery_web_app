<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Bahan Baku</title>
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
                    <h4>Tambah Data Bahan Baku</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('unit_material_save') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan Kode Satuan Unit</strong></label>
                            <input type="text" name="unit_code" class="form-control" value="{{ old('unit_code') }}"
                                placeholder="Masukan kode satuan unit (maksimal 5 karakter)" autocomplete="off">
                            <x-input-error :messages="$errors->get('unit_code')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan Nama Satuan Unit</strong></label>
                            <input type="text" name="unit_name" class="form-control" value="{{ old('unit_name') }}"
                                placeholder="Masukan nama satuan unit" autocomplete="off">
                            <x-input-error :messages="$errors->get('unit_name')" class="text-danger" />
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
