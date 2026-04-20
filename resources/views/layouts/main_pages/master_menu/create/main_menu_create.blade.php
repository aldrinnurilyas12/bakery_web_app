<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Menu Utama</title>
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
                    <h4>Tambah Data Menu Utama</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('master_main_menu.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Menu Utama</strong></label>
                            <input type="text" name="menu_name" class="form-control" value="{{ old('menu_name') }}"
                                placeholder="Masukan nama menu utama" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('menu_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Lokasi Menu Utama</strong></label>
                            <select class="form-control" name="location" id="">
                                <option value="">=== Pilih Lokasi ===</option>
                                <option value="admin">Admin</option>
                                <option value="main_web">Website Utama</option>
                            </select>
                            <x-input-error :messages="$errors->get('location')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Icon</strong></label>
                            <input type="text" name="icon" class="form-control" value="{{ old('icon') }}"
                                placeholder="Masukan icon contoh : fa fa-users" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('icon')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                            <textarea name="description" class="form-control" value="{{ old('description') }}">
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
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
