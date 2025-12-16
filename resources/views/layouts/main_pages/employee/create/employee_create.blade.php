<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Data Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Data Karyawan</h4>
                    <hr>
                    <form action="{{ route('master_employee.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label><strong>NIK Karyawan</strong></label>
                            <input class="form-control" type="text" name="nik" value="{{ old('nik') }}"
                                placeholder="Masukan NIK Karyawan" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Karyawan</strong></label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukan nama karyawan" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Lahir</strong></label>
                            <input class="form-control" type="date" name="birth_date" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Alamat</strong></label>
                            <textarea class="form-control" name="address" id="" cols="30" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label><strong>No. Telepon/Handphone</strong></label>
                            <input class="form-control" type="text" name="phone_number" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Email</strong></label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Masukan email anda" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Posisi Pekerjaan</strong></label>
                            <select class="form-control" name="position" id="">
                                <option value="">=== Pilih Posisi ===</option>
                                @foreach ($job_position as $job)
                                    <option value="{{ $job->position_code }}">{{ $job->position_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Toko</strong></label>
                            <select class="form-control" name="branch" id="">
                                <option value="">=== Pilih Toko ===</option>
                                @foreach ($branch as $toko)
                                    <option value="{{ $toko->id }}">{{ $toko->branch_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Mulai Bekerja</strong></label>
                            <input class="form-control" type="date" name="start_date" autocomplete="off">
                        </div>




                        <button type="submit" class="btn btn-primary">Tambah Data</button>
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
