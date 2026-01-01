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
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
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
                    <form action="{{ route('user_register.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label><strong>Pilih Karyawan</strong></label>
                            <select class="form-control" name="nik" id="">
                                <option value="">=== Pilih Karyawan ===</option>
                                @foreach ($employee as $emp)
                                    <option value="{{ $emp->nik }}">{{ $emp->nik . ' - ' . $emp->name }}</option>
                                @endforeach

                            </select>
                        </div>


                        <div class="form-group">
                            <label><strong>Nama Pengguna</strong></label>
                            <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                                placeholder="Masukan nama pengguna" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Email</strong></label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Masukan email anda" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Role Pengguna</strong></label>
                            <select class="form-control" name="role" id="">
                                <option value="">=== Pilih Role ===</option>
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label><strong>Buat Kata Sandi</strong></label>
                            <input class="form-control" type="password" name="password" placeholder="Masukan kata sandi"
                                id="" autocomplete="off">
                        </div>
                        <div style="display: flex; gap:20px;" class="btn-grouped">
                            <button type="submit" class="btn btn-primary">Buat Akun</button>
                            <a class="btn btn-info" href="{{ route('users_data') }}">Kembali</a>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>

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
@endif
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
