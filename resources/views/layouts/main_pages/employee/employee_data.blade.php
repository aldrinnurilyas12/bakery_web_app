<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Karyawan</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
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
                                Master Data > <strong> Karyawan</strong>
                            </div>

                            @if ($v_employee->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn-general" href="{{ route('employee_create') }}">Tambah
                                            Karyawan</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($v_employee->isNotEmpty())

                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Informasi Karyawan</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($v_employee as $employee)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <table class="table table-bordered" width="100%"
                                                            cellspacing="0">
                                                            <tr>
                                                                <th>Aksi</th>
                                                                <td></td>
                                                                <td><a class="btn btn-primary"
                                                                        href="{{ route('employee_edit', $employee->nik) }}"><i
                                                                            class="fa fa-edit"></i> Ubah</a></td>
                                                            </tr>

                                                            <tr>
                                                                <th>Status Karyawan</th>
                                                                <td>
                                                                    {{ $employee->status }}
                                                                </td>
                                                                <td>
                                                                    @if ($employee->status == 'Active')
                                                                        <a class="btn btn-danger" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#deleteEmployee{{ $employee->nik }}"><i
                                                                                class="fa fa-edit"></i> Nonaktifkan</a>
                                                                    @elseif($employee->status == 'Inactive')
                                                                        <a class="btn btn-primary" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#deleteEmployee{{ $employee->nik }}"><i
                                                                                class="fa fa-edit"></i> Aktifkan</a>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Status Akun Web</th>
                                                                <td>
                                                                    {{ $employee->account_status }}
                                                                </td>
                                                                <td>
                                                                    @if ($employee->account_status == 'Account Active')
                                                                        <a class="btn btn-danger" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#accountStatus{{ $employee->nik }}"><i
                                                                                class="fa fa-edit"></i> Nonaktifkan</a>
                                                                    @elseif($employee->account_status == 'Inactive')
                                                                        <a class="btn btn-primary" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#accountStatus{{ $employee->nik }}"><i
                                                                                class="fa fa-edit"></i> Aktifkan</a>
                                                                    @elseif($employee->account_status == 'Account Unverified')
                                                                        <span class="text-danger">Akun belum aktif/belum
                                                                            verifikasi</span>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Aktivitas</th>
                                                                <td></td>
                                                                <td><a class="btn btn-warning"
                                                                        href="{{ route('employee_activity', $employee->nik) }}"><i
                                                                            class="fa fa-eye"></i> Lihat</a></td>
                                                            </tr>
                                                        </table>


                                                    </td>
                                                    <td>

                                                        <table class="table table-bordered" width="100%"
                                                            cellspacing="0">
                                                            <tr>
                                                                <th>NIK</th>
                                                                <td> {{ $employee->nik }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Nama Karyawan</th>
                                                                <td> {{ $employee->name }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Alamat</th>
                                                                <td> {{ $employee->address ?: '-' }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>No.HP</th>
                                                                <td> {{ $employee->phone_number }}
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <th>Email</th>
                                                                <td>{{ $employee->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Posisi</th>
                                                                <td>{{ $employee->position_name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Store</th>
                                                                <td>{{ $employee->store_name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tanggal Masuk</th>
                                                                <td>{{ \Carbon\carbon::parse($employee->start_date)->format('d M Y') }}
                                                                </td>
                                                            </tr>

                                                            @if ($employee->end_date)
                                                                <tr>
                                                                    <th>Tanggal Keluar/Resign</th>
                                                                    <td>{{ \Carbon\carbon::parse($employee->end_date)->format('d M Y') }}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                        </table>
                                                    </td>
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
                                                <h3>Belum ada data karyawan</h3>
                                                @if (!$user_permission_forbidden)
                                                    <p class="text-secondary">Tambah data karyawan</p>
                                                    <a class="btn btn-primary" href="{{ 'employee_create' }}">Tambah
                                                        Data</a>
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

    {{-- Nonactive & Active Employee --}}
    @foreach ($v_employee as $emp)
        <div wire:ignore class="modal fade" id="deleteEmployee{{ $emp->nik }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $emp->nik }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        @if ($emp->status == 'Active')
                            <h5 class="modal-title" id="exampleModalLabel">Nonaktifkan karyawan</h5>
                        @else
                            <h5 class="modal-title" id="exampleModalLabel">Aktifkan karyawan</h5>
                        @endif
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        @if ($emp->status == 'Active')
                            <label for="">Anda yakin ingin nonaktifkan Karyawan
                                {{ $emp->nik . ' - ' . $emp->name }}</label>
                            <br>
                            <br>
                            <p><span class="text-danger"> <strong>*Jika status karyawan nonaktif maka karyawan tidak
                                        bisa
                                        akses web admin kembali.</strong></span></p>
                        @else
                            <label for="">Anda yakin ingin aktifkan kembali Karyawan
                                {{ $emp->nik . ' - ' . $emp->name }}</label>
                        @endif
                        <form action="{{ route('employee_update_status', $emp->nik) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <br>
                            @if ($emp->status == 'Active')
                                <div class="form-group">
                                    <input type="checkbox" value="8" name="status" required>
                                    <label for="">Ya, Nonaktifkan</label>
                                </div>
                            @else
                                <div class="form-group">
                                    <input type="checkbox" value="7" name="status" required>
                                    <label for="">Ya, Aktifkan</label>
                                </div>
                            @endif
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    {{-- Nonactive User Account --}}
    @foreach ($v_employee as $emp)
        <div wire:ignore class="modal fade" id="accountStatus{{ $emp->nik }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $emp->nik }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        @if ($emp->account_status == 'Account Active')
                            <h5 class="modal-title" id="exampleModalLabel">Nonaktifkan Akun Pengguna</h5>
                        @else
                            <h5 class="modal-title" id="exampleModalLabel">Aktifkan Akun Pengguna</h5>
                        @endif
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf

                        @if ($emp->account_status == 'Account Active')
                            <label for="">Anda yakin ingin nonaktifkan Akun Pengguna
                                {{ $emp->nik . ' - ' . $emp->name }}</label>
                        @else
                            <label for="">Anda yakin ingin aktifkan kembali Akun Pengguna
                                {{ $emp->nik . ' - ' . $emp->name }}</label>
                        @endif
                        <form action="{{ route('user_active_update', $emp->nik) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <br>
                            @if ($emp->account_status == 'Account Active')
                                <div class="form-group">
                                    <input type="checkbox" value="8" name="is_active" required>
                                    <label for="">Ya, Nonaktifkan</label>
                                </div>
                            @else
                                <div class="form-group">
                                    <input type="checkbox" value="7" name="is_active" required>
                                    <label for="">Ya, Aktifkan</label>
                                </div>
                            @endif
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
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
