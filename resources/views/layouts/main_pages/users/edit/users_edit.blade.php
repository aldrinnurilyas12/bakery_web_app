<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Pengguna</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h4>Ubah data Pengguna</h4>
                    <hr>

                    @foreach ($v_users as $user)
                        <form action="{{ route('users_update', $user->nik) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label><strong>NIK</strong></label>
                                <input class="form-control" type="text" value="{{ $user->nik }}" id=""
                                    autocomplete="off" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Nama Pengguna</strong></label>
                                <input class="form-control" type="text" name="username" value="{{ $user->username }}"
                                    id="" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                    autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label><strong>Role Saat ini</strong></label>
                                <input class="form-control" type="text" value="{{ $user->role }}"
                                    autocomplete="off" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Pilih Role Pengguna</strong></label>
                                <select class="form-control" name="role" id="">
                                    @foreach ($role as $r)
                                        <option value="{{ $r->id }}"
                                            {{ $user->role_id == $r->id ? 'selected' : '' }}>
                                            {{ $r->role }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label><strong>Diperbarui pada</strong></label>
                                <input type="text" class="form-control" value="{{ $user->updated_at ?: '-' }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Diperbarui oleh</strong></label>
                                <input type="text" class="form-control" value="{{ $user->updated_by ?: '-' }}"
                                    readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </form>
                    @endforeach
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
@elseif(Session::has('failed_message'))
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


<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
