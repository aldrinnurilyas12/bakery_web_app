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
                    <form action="{{ route('role_permission_save') }}" method="POST">
                        @csrf

                        <div class="card mb-4">
                            <div style="display: flex; justify-content:space-between;" class="card-header">

                                <div class="title">
                                    Lainnya > <strong> User Role Permission </strong>
                                </div>

                                <div style="display: flex; gap:20px;" class="button-group">

                                    <div class="canceled-btn">
                                        <button type="button" class="btn btn-warning" id="uncheckAll">
                                            Hapus Semua Centang
                                        </button>
                                    </div>

                                    <div class="save-changes">
                                        <button class="btn-general" type="submit">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Menu</th>

                                                @foreach ($role as $r)
                                                    <th class="text-center">
                                                        {{ $r->role }}
                                                        <br>
                                                        <input type="checkbox" class="check-all"
                                                            data-role="{{ $r->id }}">
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($submenu as $sub)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><strong>{{ $sub->submenu_name }} </strong>
                                                        &nbsp; &nbsp; [{{ $sub->submenu_link }}]</td>
                                                    @foreach ($role as $r)
                                                        <td class="text-center">
                                                            <input type="checkbox" class="role-{{ $r->id }}"
                                                                name="permission[{{ $sub->id }}][]"
                                                                value="{{ $r->id }}"
                                                                {{ isset($permissions[$sub->id . '_' . $r->id]) ? 'checked' : '' }}>
                                                        </td>
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                    </form>
                </div>
        </div>
        </main>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Centang semua per kolom
            document.querySelectorAll('.check-all').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let roleId = this.dataset.role;

                    document.querySelectorAll('.role-' + roleId).forEach(function(item) {
                        item.checked = checkbox.checked;
                    });
                });
            });

            // Hapus semua centang
            document.getElementById('uncheckAll').addEventListener('click', function() {

                // Hilangkan centang semua checkbox body
                document.querySelectorAll('input[class^="role-"]').forEach(function(checkbox) {
                    checkbox.checked = false;
                });

                // Hilangkan centang checkbox header
                document.querySelectorAll('.check-all').forEach(function(checkbox) {
                    checkbox.checked = false;
                });

            });

        });
    </script>

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
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>



<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
