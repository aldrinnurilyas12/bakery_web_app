<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Menu Utama</title>
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
                @php
                    $location = ['admin', 'main_web'];
                @endphp
                <br>
                <div class="container-fluid px-4">
                    <h4>Ubah Data Menu Utama</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div>


                    <form id="formGeneralMaster" action="{{ route('main_menu_edit', $main_menu->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="text" name="id" value="{{ $main_menu->id }}" hidden>
                        <div class="form-group">
                            <label><strong>Nama Menu Utama</strong></label>
                            <input type="text" name="menu_name" class="form-control"
                                value="{{ $main_menu->menu_name }}" placeholder="Masukan nama menu utama"
                                id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('menu_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Lokasi Menu Utama</strong></label>
                            <select class="form-control" name="location" id="">
                                @foreach (['admin', 'main_web'] as $loc)
                                    <option value="{{ $loc }}"
                                        {{ $loc == $main_menu->location ? 'selected' : '' }}>
                                        {{ $loc }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('location')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Icon</strong></label>
                            <input type="text" name="icon" class="form-control" value="{{ $main_menu->icon }}"
                                placeholder="Masukan icon contoh : fa fa-users" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('icon')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Status</strong></label>
                            <select class="form-control" name="status" id="">
                                @foreach ($status as $st)
                                    <option value="{{ $st->id }}"
                                        {{ $st->id == $main_menu->status ? 'selected' : '' }}>
                                        {{ $st->status_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                            <textarea name="description" class="form-control">
                                {{ $main_menu->description }}
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $main_menu->updated_at ?: '-' }}"
                                readonly>
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
