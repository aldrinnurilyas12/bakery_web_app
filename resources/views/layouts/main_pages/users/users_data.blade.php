<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Data Pengguna</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Master Data / <a href="{{ route('master_products.index') }}">Akun Pengguna</a>
                            </div>

                            @if ($v_users->isNotEmpty())
                                <div class="button-add-product">
                                    <a class="btn btn-primary" href="{{ route('users_register_account') }}">Tambah
                                        Pengguna</a>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($v_users->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Aksi</th>
                                                <th>Karyawan</th>
                                                <th>Nama Pengguna</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Aktif</th>
                                                <th>Created at</th>
                                                <th>Created by</th>
                                                <th>Updated at</th>
                                                <th>Updated by</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($v_users as $user)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <div style="display: flex;gap:10px;" class="btn-action">
                                                            <a href="{{ route('users_edit', $user->nik) }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#deleteAccount{{ $user->nik }}"><i
                                                                    class="fas fa-trash"></i></a>

                                                        </div>
                                                    </td>
                                                    <td>{{ $user->nik . ' - ' . $user->name }}</td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if ($user->role == '1')
                                                            Super Admin
                                                        @else
                                                            Admin
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $user->is_active }}
                                                        &nbsp;
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#changeStatus{{ $user->nik }}">
                                                            <br>
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->created_at }}</td>
                                                    <td>{{ $user->created_by }}</td>
                                                    <td>{{ $user->updated_at }}</td>
                                                    <td>{{ $user->updated_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                    class="empty-transaction">

                                    <div style="display: flex;" class="empty-content">
                                        <div style="display: flex; gap:20px;margin:auto;" class="alert-info">
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                alt="">
                                            <div style="display: block;" class="text-content">
                                                <h3>Belum ada item</h3>
                                                <p class="text-secondary">Tambah data produk anda</p>
                                                <a class="btn btn-primary" href="{{ 'product_create' }}">Tambah
                                                    Item</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    @foreach ($v_users as $user)
        <div wire:ignore class="modal fade" id="changeStatus{{ $user->nik }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $user->nik }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Akun Pengguna</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Ubah status Akun {{ $user->username }}
                        <br>
                        <form method="POST" action="{{ route('user_active_update', $user->nik) }}">
                            @csrf
                            @method('PUT')
                            <label for=""><strong> Pilih Status </strong></label>
                            <select name="is_active" class="form-control" id="">
                                <option value="">=== Pilih Status ===</option>
                                <option value="Y">Aktif</option>
                                <option value="N">Tidak</option>
                            </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    @foreach ($v_users as $user)
        <div wire:ignore class="modal fade" id="deleteAccount{{ $user->nik }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $user->nik }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Akun Pengguna</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Hapus Akun Pengguna
                        :{{ '[' . $user->nik . ']' . ' - ' . $user->username }}
                        <br>
                        <form action="{{ route('users_delete', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


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
