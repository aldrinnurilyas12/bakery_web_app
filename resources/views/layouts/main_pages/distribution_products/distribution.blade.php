<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Inventory Distribution</title>
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
                                Master Data / <a href="{{ route('master_category.index') }}">Distribusi Produk</a>
                            </div>
                            <div style="display: flex;gap:10px;" class="flex-content">

                                @if ($module_documentation)
                                    <div style="align-self: center; background: rgb(222, 222, 255);padding:8px; border-radius: 5px;"
                                        class="documentation-module">
                                        <a title="Dokumentasi Modul"
                                            href="{{ route('show_module_documentation', $module_documentation->url_path) }}">
                                            <i aria-label="Module Documentation" class="fa fa-file"></i>
                                        </a>
                                    </div>
                                @endif

                                @if ($distribution->isNotEmpty())
                                    @if (!$user_permission_forbidden)
                                        <div class="button-add-product">
                                            <a class="btn btn-primary" href="{{ route('distribution_create') }}">Buat
                                                Distribusi Produk</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($distribution->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Kode Distribusi</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Dibuat pada</th>
                                                <th>Dibuat oleh</th>
                                                <th>Diubah pada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($distribution as $dst)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <table style="font-size: 14px; color:black;"
                                                                class="table table-bordered" id="dataTable"
                                                                width="100%" cellspacing="0">

                                                                <tr>
                                                                    @if ($dst->attachment_file)
                                                                        <th>Attachment</th>
                                                                    @endif
                                                                    <th>Detail </th>
                                                                </tr>

                                                                <tr>
                                                                    @if ($dst->attachment_file)
                                                                        <td> <a href="#" data-toggle="modal"
                                                                                data-target="#showModal{{ $dst->distribution_code }}"><i
                                                                                    class="fas fa-eye"></i></a></td>
                                                                    @endif
                                                                    <td> <a
                                                                            href="{{ route('distribution_detail', $dst->distribution_code) }}"><i
                                                                                class="fas fa-list"></i></a></td>
                                                                </tr>

                                                            </table>
                                                        </td>
                                                    @endif
                                                    <td>{{ $dst->distribution_code }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($dst->distribution_date)->format('Y-m-d') }}
                                                    </td>
                                                    <td> {{ $dst->status_name ?: '-' }} </td>
                                                    <td>{{ $dst->created_at }}</td>
                                                    <td>{{ $dst->emp_name }}</td>
                                                    <td>{{ $dst->updated_at }}</td>
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
                                                <h3>Belum ada data Distribusi Produk</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('distribution_create') }}">Buat distribusi</a>
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
