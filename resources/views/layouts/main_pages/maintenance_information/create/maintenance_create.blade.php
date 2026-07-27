<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Informasi Maintenance</title>
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
                    <h4>Tambah Informasi Maintenance</h4>
                    <hr>

                    <form id="formGeneralMaster" action="{{ route('maintenance_information.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Informasi Maintenance</strong></label>
                            <input type="text" name="maintenance_information" class="form-control"
                                value="{{ old('maintenance_information') }}" placeholder="Masukan nama informasi"
                                id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('maintenance_information')" class="text-danger" />
                        </div>

                        <div style="display:block; gap:20px;" class="date-group">
                            <div style="display:flex; gap:20px;" class="date-group">
                                <div class="form-group">
                                    <label><strong>Tanggal awal</strong></label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                                </div>
                                <div class="form-group">
                                    <label><strong>Jam awal</strong></label>
                                    <input type="time" name="hour_start" class="form-control"
                                        value="{{ old('hour_start') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('hour_start')" class="text-danger" />
                                </div>
                            </div>


                            <div style="display:flex; gap:20px;" class="date-group">
                                <div class="form-group">
                                    <label><strong>Tanggal akhir</strong></label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                                </div>

                                <div class="form-group">
                                    <label><strong>Jam akhir</strong></label>
                                    <input type="time" name="hour_end" class="form-control"
                                        value="{{ old('hour_end') }}" id="inputEmail4" autocomplete="off">
                                    <x-input-error :messages="$errors->get('hour_end')" class="text-danger" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Pesan Informasi Maintenance</strong></label>
                            <textarea class="form-control" name="message" id="" cols="30" rows="5"></textarea>
                            <x-input-error :messages="$errors->get('message')" class="text-danger" />
                        </div>

                        <div style="display:flex; gap:20px;" class="date-group">
                            <div class="form-group">
                                <label for=""><strong>Pilih Jenis Informasi Perbaikan Sistem </strong></label>
                                <br>
                                <input name="type[]" value="admin_web" type="checkbox"> Admin Web
                                <input name="type[]" value="customer_web" type="checkbox"> Customer Web
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
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
