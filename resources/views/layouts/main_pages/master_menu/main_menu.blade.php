<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Main Menu</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
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
                @php
                    $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
                    $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Lainnya > <strong>Main Menu</strong>
                            </div>

                            <div style="display:flex; gap:20px;" class="content-btn">

                                @if ($main_menu->isNotEmpty())
                                    @if (!$user_permission_forbidden)
                                        <div class="button-add-product">
                                            <a style="color:black;" class="btn btn-info" href="#"
                                                data-toggle="modal" data-target="#changeStatus">Update Status</a>
                                            |
                                            <div class="button-add-product">
                                                <a class="btn-general" href="{{ route('main_menu_create') }}">Tambah
                                                    Menu Utama</a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($main_menu->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>SubMenu</th>
                                                <th>Nama Menu</th>
                                                <th>Icon</th>
                                                <th>Lokasi</th>
                                                <th>Status</th>
                                                <th>Deskripsi</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($main_menu as $menu)
                                                @php
                                                    $totalSubmenu = DB::table('submenu')
                                                        ->where('main_menu', $menu->id)
                                                        ->count();
                                                @endphp
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <div style="display: flex;gap:10px;" class="btn-action">

                                                                <a href="{{ route('main_menu_update', $menu->id) }}"><i
                                                                        class="fas fa-edit"></i></a>
                                                                {{-- <a href="#" data-toggle="modal"
                                                                    data-target="#deleteModal{{ $menu->id }}"><i
                                                                        class="fas fa-trash"></i></a> --}}
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td><a href="{{ route('submenu_list', $menu->id) }}"><i
                                                                class="fas fa-file-alt"></i></a>
                                                        @if ($totalSubmenu > 0)
                                                            &nbsp; {{ $totalSubmenu }} Menu
                                                        @else
                                                            &nbsp; -
                                                        @endif
                                                    </td>
                                                    <td>{{ $menu->menu_name }}</td>
                                                    <td><i class="{{ $menu->icon }} "></i>
                                                    </td>
                                                    <td> {{ $menu->location ?: '-' }} </td>
                                                    <td>
                                                        @if ($menu->status == 7)
                                                            <span class="text-success">Aktif</span>
                                                        @else
                                                            <span class="text-danger">Nonaktif</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $menu->description ?: '-' }}</td>
                                                    <td>{{ $menu->created_at }}</td>
                                                    <td>{{ $menu->updated_at }}</td>
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
                                                <h3>Belum ada Menu Utama</h3>
                                                @if (!$user_permission_forbidden)
                                                    <p class="text-secondary">Tambah data Menu Utama Website </p>
                                                    <a class="btn btn-primary" href="{{ 'main_menu_create' }}">Tambah
                                                        Menu Utama</a>
                                                @endif
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

    <div wire:ignore class="modal fade" id="changeStatus" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah status data Submenu </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin mengubah status semua submenu
                    ?
                    <form action="{{ route('submenu_update_status') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">

                            <select class="form-control" name="status" id="">
                                <option value="">=== Pilih status ===</option>
                                <option value="7">Aktifkan semua</option>
                                <option value="8">Nonaktifkan semua</option>
                            </select>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Ya ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

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

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
