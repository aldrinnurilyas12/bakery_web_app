<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Aktivitas Karyawan</title>
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
                                Master Data > <strong> Aktivitas Karyawan </strong>
                            </div>
                        </div>
                        <div class="card-header">
                            <a class="btn btn-primary" href="{{ route('master_employee.index') }}">Kembali</a>
                        </div>
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="filter-reports">

                                <div class="date" style="display: flex; gap:20px;">
                                    <div class="start-date">
                                        <label for=""><strong>NIK</strong></label>
                                        <input class="form-control" type="text" value="{{ $employee->nik }}"
                                            readonly>
                                    </div>
                                    <div class="start-date">
                                        <label for=""><strong>Karyawan</strong></label>
                                        <input class="form-control" type="text" value="{{ $employee->name }}"
                                            readonly>
                                    </div>
                                    <div class="start-date">
                                        <label for=""><strong>Posisi</strong></label>
                                        <input class="form-control" type="text"
                                            value="{{ $employee->position_name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <hr>
                        <h5 style="text-align: center;"><strong>Session Login/Logout Karyawan</strong></h5>
                        <hr>
                        <div class="card-body">
                            @if ($user_session->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="tableSession" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Session</th>
                                                <th>IP Address</th>
                                                <th>User Agent/Perangkat</th>
                                                <th>Tanggal Aktivitas</th>
                                                <th>Created at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($user_session as $session)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>{{ $session->method_type }}</td>
                                                    <td> {{ $session->ip_address }} </td>
                                                    <td class="user-agent" style="width:100px;">
                                                        {{ $session->user_agent }}
                                                    </td>
                                                    <td> {{ $session->activity_date }} </td>
                                                    <td>{{ $session->created_at }}</td>
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
                                            <div style="display: block;align-content: center;" class="text-content">
                                                <h3>Tidak ada Session Karyawan</h3>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>


                        <hr>
                        <h5 style="text-align: center;"><strong>Aktivitas Karyawan</strong></h5>
                        <hr>
                        <div class="card-body">
                            @if ($log_activities->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="tableActivity" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Module</th>
                                                <th>Metode</th>
                                                <th>IP Address</th>
                                                <th>User Agent/Perangkat</th>
                                                <th>Deskripsi</th>
                                                <th>Tanggal Aktivitas</th>
                                                <th>Created at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($log_activities as $log)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>{{ $log->module }}</td>
                                                    <td>{{ $log->method_type }}</td>
                                                    <td> {{ $log->ip_address }} </td>
                                                    <td>{{ $log->user_agent }}</td>
                                                    <td>
                                                        <textarea name="" id="" cols="30" rows="5" readonly>
                                                            {{ $log->description }}
                                                        </textarea>
                                                    </td>
                                                    <td> {{ $log->activity_date }} </td>
                                                    <td>{{ $log->created_at }}</td>
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
                                                <h3>Tidak ada Aktivitas Karyawan</h3>
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

<script>
    $(document).ready(function() {

        $('#tableSession').DataTable({
            pageLength: 10,
            responsive: true,
            autoWidth: false
        });

        $('#tableActivity').DataTable({
            pageLength: 5,
            responsive: true,
            autoWidth: false
        });

    });
</script>

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
