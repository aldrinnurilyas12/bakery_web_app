<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Submenu</title>
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
                    <h4>Ubah Data Submenu {{ $submenu->submenu_name }}</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div>


                    <form id="formGeneralMaster" action="{{ route('submenu_edit', $submenu->submenu_id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><strong>Menu Utama</strong></label>
                            <input type="text" class="form-control" value="{{ $submenu->menu_name }}"
                                id="inputEmail4" autocomplete="off" readonly>
                            <input type="text" name="main_menu" class="form-control" hidden
                                value="{{ $submenu->id }}" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('main_menu')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Submenu</strong></label>
                            <input type="text" name="submenu_name" class="form-control"
                                value="{{ $submenu->submenu_name }}" placeholder="Masukan nama submenu" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('submenu_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Submenu Link</strong></label>
                            <input type="text" name="submenu_link" class="form-control"
                                value="{{ $submenu->submenu_link }}" placeholder="Masukan nama submenu" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('submenu_link')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tipe</strong></label>
                            <select class="form-control" name="type" id="">
                                <option value="admin" {{ $submenu->type == 'admin' ? 'selected' : '' }}>Admin Web
                                </option>
                                <option value="main_web" {{ $submenu->type == 'main_web' ? 'selected' : '' }}>Customer
                                    Web
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Akses diluar jam operasional</strong></label>
                            <select class="form-control" name="allow_access_outside_operational_hours" id="">
                                <option value="Y"
                                    {{ $submenu->allow_access_outside_operational_hours == 'Y' ? 'selected' : '' }}>Ya
                                </option>
                                <option value="N"
                                    {{ $submenu->allow_access_outside_operational_hours == 'N' ? 'selected' : '' }}>
                                    Tidak</option>
                            </select>
                            <x-input-error :messages="$errors->get('allow_access_outside_operational_hours')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Icon</strong></label>
                            <input type="text" name="icon" class="form-control"
                                placeholder="Masukan icon contoh : fa fa-users" value="{{ $submenu->icon }}"
                                id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('icon')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Status</strong></label>
                            <select class="form-control" name="status" id="">
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $submenu->status == $sts->id ? 'selected' : '' }}>
                                        {{ $sts->status_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi</strong></label>
                            <textarea name="description" class="form-control">
                                {{ $submenu->description }}
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $submenu->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>

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
