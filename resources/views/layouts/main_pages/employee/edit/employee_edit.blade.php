<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Karyawan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Edit data Karyawan</h4>
                    <hr>


                    <form action="{{ route('update_employee', $employee->nik) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><strong>NIK Karyawan</strong></label>
                            <input class="form-control" type="text" name="nik" value="{{ $employee->nik }}"
                                placeholder="Masukan NIK Karyawan" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Karyawan</strong></label>
                            <input class="form-control" type="text" name="name" value="{{ $employee->name }}"
                                placeholder="Masukan nama karyawan" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Lahir</strong></label>
                            <input class="form-control"
                                value="{{ old('birth_date', $employee->birth_date ? $birth_date->format('Y-m-d') : null) }}"
                                type="date" name="birth_date" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Alamat</strong></label>
                            <textarea class="form-control" name="address" id="" cols="30" rows="4">
                                    {{ $employee->address }}
                                </textarea>
                        </div>

                        <div class="form-group">
                            <label><strong>No. Telepon/Handphone</strong></label>
                            <input class="form-control" type="text" value="{{ $employee->phone_number }}"
                                name="phone_number" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Email</strong></label>
                            <input class="form-control" type="email" name="email" value="{{ $employee->email }}"
                                placeholder="Masukan email anda" id="" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Posisi Pekerjaan</strong></label>
                            <select class="form-control" name="position" id="">
                                @foreach ($job_position as $job)
                                    <option value="{{ $job->position_code }}"
                                        {{ $job->position_code == $employee->position_code ? 'selected' : '' }}>
                                        {{ $job->position_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Toko</strong></label>
                            <select class="form-control" name="branch" id="">
                                @foreach ($branch as $toko)
                                    <option value="{{ $toko->id }}"
                                        {{ $toko->id == $employee->branch_id ? 'selected' : '' }}>
                                        {{ $toko->branch_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Mulai Bekerja</strong></label>
                            <input class="form-control" type="date"
                                value="{{ old('start_date', $employee->start_date ? $start_date->format('Y-m-d') : null) }}"
                                name="start_date" autocomplete="off">
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </form>

                </div>
            </main>
</body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
