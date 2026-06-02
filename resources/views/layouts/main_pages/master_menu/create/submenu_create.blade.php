<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk</title>
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
                    <h4>Tambah Data Submenu {{ $main_menu->menu_name }}</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Jika ingin menyambung jangan pakai '&' dan spasi, pakai underscore (_) contoh :
                                Muffins_and_Cupcakes</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('submenu_save') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Menu Utama</strong></label>
                            <input type="text" class="form-control" value="{{ $main_menu->menu_name }}"
                                id="inputEmail4" autocomplete="off" readonly>
                            <input type="text" name="main_menu" class="form-control" hidden
                                value="{{ $main_menu->id }}" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('main_menu')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Submenu</strong></label>
                            <input type="text" name="submenu_name" class="form-control"
                                value="{{ old('submenu_name') }}" placeholder="Masukan nama submenu" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('submenu_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Submenu Link</strong></label>
                            <input type="text" name="submenu_link" class="form-control"
                                value="{{ old('submenu_link') }}" placeholder="Masukan nama submenu" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('submenu_link')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Akses diluar jam operasional</strong></label>
                            <select class="form-control" name="allow_access_outside_operational_hours" id="">
                                <option value="">=== Pilih ===</option>
                                <option value="Y">Ya</option>
                                <option value="N">Tidak</option>
                            </select>
                            <x-input-error :messages="$errors->get('allow_access_outside_operational_hours')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Icon</strong></label>
                            <input type="text" name="icon" class="form-control"
                                placeholder="Masukan icon contoh : fa fa-users" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('icon')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                            <textarea name="description" class="form-control" value="{{ old('description') }}"></textarea>
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
