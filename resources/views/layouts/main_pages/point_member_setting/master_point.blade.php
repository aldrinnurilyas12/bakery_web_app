<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Point Settings</title>
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
                    $user_permission_forbidden = $session_user->role_name == 'Casheer';
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Master Data > <strong> Pengaturan Point Transaksi Member </strong>
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

                                @if ($points->isNotEmpty())
                                    @if (!$user_permission_forbidden)
                                        @if (!$check_last_active)
                                            <div class="button-add-product">
                                                <a class="btn btn-primary" href="{{ route('point_create') }}">Tambah
                                                    Point</a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div style="font-size: 13px;" class="alert alert-info">
                            <ul>
                                <li>Point digunakan saat pelanggan melakukan transaksi dan mempunyai member</li>
                                <li>Point tidak bisa diubah namun bisa di nonaktifkan secara manual</li>
                                <li>Status point akan berubah jika sudah melewati masa tanggal expired</li>
                                <li>Jika ingin menambahkan data point terbaru maka tunggu masa expired point dan atau
                                    bisa nonaktifkan point yang sedang berjalan</li>
                            </ul>
                        </div>
                        <div class="card-body">
                            @if ($points->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Point</th>
                                                <th>Status</th>
                                                <th>Tanggal berlaku</th>
                                                <th>Tanggal expired</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($points as $point)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        @if ($point->status_name == 'Active')
                                                            <td>
                                                                <div style="display: flex;gap:10px;" class="btn-action">

                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#changeStatus{{ $point->id }}"><i
                                                                            class="fas fa-edit"></i></a>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    @endif
                                                    <td>{{ $point->point }}</td>
                                                    <td>

                                                        @if ($point->status_name == 'Active')
                                                            <span class="text-success">{{ $point->status_name }}</span>
                                                        @else
                                                            <span class="text-danger">{{ $point->status_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td> {{ \Carbon\Carbon::parse($point->start_date)->format('Y-m-d') }}
                                                    </td>
                                                    <td> {{ \Carbon\Carbon::parse($point->end_date)->format('Y-m-d') }}
                                                    </td>
                                                    <td>{{ $point->created_at }}</td>
                                                    <td>{{ $point->updated_at }}</td>
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
                                                <h3>Belum ada Point</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('point_create') }}">Tambah Point</a>
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

    @foreach ($points as $point)
        @php
            $status = DB::table('status_category')
                ->whereIn('id', ['7', '8'])
                ->get();
        @endphp
        <div wire:ignore class="modal fade" id="changeStatus{{ $point->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $point->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data kategori produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin merubah data Point ?

                        <form class="form-delete" action="{{ route('point_update_status', $point->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>ID</strong></label>
                                <input type="text" class="form-control" value="#{{ $point->id }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for=""><strong>Jumlah Point</strong></label>
                                <input type="text" class="form-control" value="{{ $point->point }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for=""><strong>Ubah Status</strong></label>
                                <select class="form-control" name="status" id="">
                                    <option>=== Pilih Status ===</option>
                                    @foreach ($status as $st)
                                        <option value="{{ $st->id }}">{{ $st->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                    </div>
                    <div class="modal-footer">

                        <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                class="btn-text">Simpan perubahan</span>
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
