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
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
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


                        <div class="container-information">
                            <div class="content-profil">
                                <h4>{{ $employee->name }}</h4>
                                <p class="text-secondary">{{ $employee->email }}</p>
                                <p>{{ $employee->position_name }}</p>
                            </div>



                        </div>
                    </div>
                </section>



                <br>
                <section class="update-profil">
                    <div class="container-fluid px-4">
                        <h4><strong>Profil Pengguna</strong></h4>
                        <hr>


                        <form method="POST" action="{{ route('user_profile_update', $employee->nik) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label><strong>NIK</strong></label>
                                <input type="text" name="nik" class="form-control" value="{{ $employee->nik }}"
                                    readonly>
                                @if ($errors->has('nik'))
                                    <span class="text-danger">{{ $errors->first('nik') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Nama Karyawan</strong></label>
                                <input type="text" name="name" class="form-control" value="{{ $employee->name }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Lahir</strong></label>
                                <input class="form-control" type="date"
                                    value="{{ old('birth_date', $employee->birth_date ? $birth_date->format('Y-m-d') : null) }}"
                                    name="birth_date" autocomplete="off">
                                @if ($errors->has('birth_date'))
                                    <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Alamat</strong></label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ $employee->address }}">
                                @if ($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>


                            <div class="form-group">
                                <label><strong>No. Handphone</strong></label>
                                <input type="text" name="phone_number" class="form-control"
                                    value="{{ $employee->phone_number }}">
                                @if ($errors->has('phone_number'))
                                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $employee->email }}">
                            </div>

                            <div class="form-group">
                                <label><strong>Kantor</strong></label>
                                <input type="text" class="form-control" name="branch"
                                    value="{{ $employee->branch_id }}" hidden>
                                <input type="text" class="form-control" value="{{ $employee->branch_name }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Posisi</strong></label>
                                <input type="text" class="form-control" name="position"
                                    value="{{ $employee->position_code }}" hidden>
                                <input type="text" class="form-control" value="{{ $employee->position_name }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Username Akun</strong></label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ $employee->username }}" readonly>
                                @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label><strong>Tanggal Masuk Karyawan</strong></label>
                                <input type="text" name="start_date" class="form-control"
                                    value="{{ $employee->start_date }}" readonly>
                            </div>



                            <div class="form-group">
                                <label><strong>Tanggal Buat Akun</strong></label>
                                <input type="text" class="form-control" value="{{ $employee->created_at }}"
                                    readonly>
                            </div>


                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </form>

                        <br>
                        <br>
                    </div>
                </section>
            </main>
</body>
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

</html>
