<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Kategori</title>
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
                                Master Data / <a href="{{ route('modules_documentation.index') }}">Module
                                    Documentation</a>
                            </div>
                            @if ($module_documentation->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('module_create') }}">Tambah
                                            dokumentasi module</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($module_documentation->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>PDF File</th>
                                                <th>Nama Module</th>
                                                <th>Module</th>
                                                <th>Deskripsi</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($module_documentation as $key => $module)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <div style="display: flex;gap:10px;" class="btn-action">

                                                                <a
                                                                    href="{{ route('module_update', $module->url_path) }}"><i
                                                                        class="fas fa-edit"></i></a>

                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#deleteModule{{ $module->url_path }}"><i
                                                                        class="fas fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <div style="display: flex; gap:10px;" class="action-user">
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#showPdf{{ $module->url_path }}"><i
                                                                    class="fas fa-file"></i></a>
                                                            <a
                                                                href="{{ asset('storage/' . $module->attachment_file) }}">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                    </td>
                                                    <td>{{ $module->module_name }}</td>
                                                    <td>{{ $module->submenu_name }}</td>
                                                    <td>
                                                        <textarea class="form-control" cols="30" rows="2">{{ $module->description }}</textarea>
                                                    </td>
                                                    <td>{{ $module->created_at }}</td>
                                                    <td>{{ $module->updated_at }}</td>
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
                                                <h3>Belum ada Dokumentasi Modul</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('module_create') }}">Tambah
                                                        Dokumentasi Modul</a>
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

    @foreach ($module_documentation as $module)
        <div wire:ignore class="modal fade" id="showPdf{{ $module->url_path }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $module->url_path }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Show Attachment File</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{ asset('storage/' . $module->attachment_file) }}" width="100%"
                            height="600px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    @foreach ($module_documentation as $module)
        <div wire:ignore class="modal fade" id="deleteModule{{ $module->url_path }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $module->url_path }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data Dokumentasi Modul</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        apakah anda yakin ingin menghapus module {{ $module->module_name }}
                    </div>
                    <div class="modal-footer">
                        <form class="form-delete" action="{{ route('delete_module', $module->url_path) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                    class="btn-text">Hapus</span>
                                <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
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
