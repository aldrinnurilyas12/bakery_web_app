<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah data Dokumentasi Module</title>
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
                    <h4>Tambah Data Modul Dokumentasi</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Upload file hanya format PDF tidak lebih dari 5MB</li>
                        </ul>
                    </div>
                    <form id="formGeneralMaster" action="{{ route('modules_documentation.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Module Dokumentasi</strong></label>
                            <input type="text" name="module_name" class="form-control"
                                value="{{ old('module_name') }}"
                                placeholder="Masukan nama module, Contoh: Module Produksi version 1.0" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('module_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Module</strong></label>
                            <select name="url_path" class="form-control" id="">
                                <option value="">=== Pilih Menu Module ===</option>
                                @foreach ($submenu as $sub)
                                    <option value="{{ $sub->submenu_link }}">{{ $sub->submenu_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('url_path')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="5"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Attachment File (PDF only)</strong></label>
                            <input type="file" name="attachment_file" class="form-control"
                                placeholder="Masukan icon contoh : fa fa-users" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('attachment_file')" class="text-danger" />
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
