<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Buat Akun Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h4>Buat Akun Pengguna</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('user_register.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label><strong>Pilih Karyawan</strong></label>
                            <select class="form-control" name="nik" id="emp_nik">
                                <option value="">=== Pilih Karyawan ===</option>
                                @foreach ($employee as $emp)
                                    <option value="{{ $emp->nik }}">{{ $emp->nik . ' - ' . $emp->name }}</option>
                                @endforeach

                            </select>
                            <x-input-error :messages="$errors->get('nik')" class="text-danger" />
                        </div>


                        <div class="form-group">
                            <label><strong>Nama Pengguna</strong></label>
                            <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                                placeholder="Masukan nama pengguna" id="showUsername" autocomplete="off">
                            <x-input-error :messages="$errors->get('username')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Email</strong></label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Email akan muncul disini" id="showEmail" autocomplete="off" readonly>
                            <x-input-error :messages="$errors->get('email')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Role Pengguna</strong></label>
                            <select class="form-control" name="role" id="">
                                <option value="">=== Pilih Role ===</option>
                                @foreach ($role as $r)
                                    <option value="{{ $r->id }}">{{ $r->role }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="text-danger" />
                        </div>


                        <div id="showPassword" class="form-group">
                            <label><strong>Buat Kata Sandi</strong></label>
                            <input class="form-control" type="password" name="password" placeholder="Masukan kata sandi"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('password')" class="text-danger" />
                        </div>
                        <div style="display: flex; gap:20px;" class="btn-grouped">
                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<script>
    document.getElementById('emp_nik').addEventListener('change', function() {
        var emp_nik = this.value;

        const usernameField = document.getElementById('showUsername');
        const passwordField = document.getElementById('showPassword');

        if (!emp_nik) {
            document.getElementById('showEmail').value = '';
            document.getElementById('showUsername').value = '';
            return;
        }

        fetch('/get_email/' + emp_nik).then(response => {
            if (!response.ok) {
                throw new Error('Email not found');
            }
            return response.json();
        }).then(data => {
            if (data.email) {
                document.getElementById('showEmail').value = data.email;
            }
            if (data.username) {
                document.getElementById('showUsername').value = data.username;
                usernameField.readOnly = true;
                passwordField.hidden = true;

            }
            if (data.username == null) {
                document.getElementById('showUsername').value = '';
                usernameField.readOnly = false;
                passwordField.hidden = false;
            }
        })
    })
</script>

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
@elseif(Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 4000,
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

</html>
