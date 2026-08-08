<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Informasi Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <section class="introduction-profil">

                    <div class="container-content">
                        <div style="display: flex; gap:20px;" class="container-information">
                            <div style="padding: 25px; border-radius: 50%; background:#bb0239;color:white;height:max-content;"
                                class="bg-info-profile">
                                {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->inisial }}
                            </div>
                            <div style="display: block;" class="content-profil">
                                <h4>{{ $employee->name }}</h4>
                                <p class="text-secondary">{{ $employee->email }}</p>
                                <p>{{ $employee->position_name }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="update-profil">
                    <div class="container-fluid px-4">
                        <h4><strong>Profil Pengguna</strong></h4>
                        <hr>


                        <form id="formGeneralMaster" method="POST"
                            action="{{ route('user_profile_update', $employee->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <table class="table table-bordered">
                                <tbody>

                                    <tr>
                                        <th>NIK Karyawan</th>
                                        <td>
                                            <input type="text" name="nik" class="form-control"
                                                value="{{ $employee->nik }}" readonly>
                                            @if ($errors->has('nik'))
                                                <span class="text-danger">{{ $errors->first('nik') }}</span>
                                            @endif
                                        </td>
                                    </tr>


                                    <tr>
                                        <th>Nama Karyawan</th>
                                        <td>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $employee->name }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <td>
                                            <input class="form-control" type="date"
                                                value="{{ old('birth_date', $employee->birth_date ? $birth_date->format('d M Y') : null) }}"
                                                name="birth_date" autocomplete="off">
                                            @if ($errors->has('birth_date'))
                                                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Alamat Rumah</th>
                                        <td>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $employee->address }}">
                                            @if ($errors->has('address'))
                                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                            @endif
                                        </td>
                                    </tr>


                                    <tr>
                                        <th>No.HP/Telepon</th>
                                        <td>
                                            <input type="text" name="phone_number" class="form-control"
                                                value="{{ $employee->phone_number }}">
                                            @if ($errors->has('phone_number'))
                                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                            @endif
                                        </td>
                                    </tr>


                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $employee->email }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Kantor</th>
                                        <td>{{ $employee->store_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Posisi</th>
                                        <td>{{ $employee->position_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Username Akun</th>
                                        <td>{{ $employee->username }}</td>
                                    </tr>

                                    <tr>
                                        <th>Tanggal masuk</th>
                                        <td>{{ $employee->start_date }}</td>
                                    </tr>

                                    <tr>
                                        <th>Tanggal buat akun</th>
                                        <td>{{ $employee->created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                    Data</span>
                                <span class="spinner"></span></button>
                        </form>

                        <br>
                        <br>
                    </div>


                    <div class="container-fluid px-4">
                        <h4><strong>Ubah Kata Sandi</strong></h4>
                        <hr>


                        <form id="formGeneralMaster" method="POST"
                            action="{{ route('change_password_employee', $employee->nik) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <table class="table table-bordered">
                                <tbody>

                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            <input placeholder="Masukan email anda" type="text" name="input_email"
                                                class="form-control" autocomplete="off">
                                            @if ($errors->has('input_email'))
                                                <span class="text-danger">{{ $errors->first('input_email') }}</span>
                                            @endif
                                        </td>
                                    </tr>


                                    <tr>
                                        <th>Kata sandi baru</th>
                                        <td>
                                            <div style="position: relative;">
                                                <input id="password" type="password" name="password"
                                                    class="form-control" autocomplete="off"
                                                    placeholder="Masukan kata sandi baru">
                                                <i class="fas fa-eye" id="togglePassword"
                                                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                                </i>
                                                @if ($errors->has('password'))
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Konfirmasi Kata sandi</th>
                                        <td>
                                            <div style="position: relative;">
                                                <input id="confirmPassword" type="password" name="confirm_password"
                                                    autocomplete="off" class="form-control"
                                                    placeholder="Konfirmasi kata sandi">
                                                <i class="fas fa-eye" id="toggleConfirmPassword"
                                                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                                </i>
                                                @if ($errors->has('confirm_password'))
                                                    <span
                                                        class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Ubah
                                    kata sandi</span>
                                <span class="spinner"></span></button>
                        </form>

                        <br>
                        <br>
                    </div>
                </section>
            </main>
</body>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function() {
        console.log('ICON PASSWORD DIKLIK');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // ganti icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
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
@elseif (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }

    .container-content {
        width: 100%;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;

    }

    .container-information {
        display: block;
        padding-top: 40px;
    }

    .content-profil {
        align-content: center;

    }

    .img-content {
        box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px, rgb(209, 213, 219) 0px 0px 0px 1px inset;
    }

    .content-profil h4 {
        font-weight: bold;
    }

    .content-profil p {
        font-size: 14px;
    }
</style>


</html>
