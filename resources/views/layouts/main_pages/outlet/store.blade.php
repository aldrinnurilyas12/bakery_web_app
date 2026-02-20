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
                                Master Data / <a href="{{ route('master_category.index') }}">Store</a>
                            </div>
                            @if ($store->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('store_create') }}">Tambah
                                            Store</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <hr>
                        <div style="font-size: 13px;" class="alert alert-info">
                            <ul>
                                <li>Jika ingin mengganti Head Store silahkan hapus dahulu Head Store yang ingin dipilih
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            @if ($store->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Kode Store</th>
                                                <th>Store</th>
                                                <th>Lokasi</th>
                                                <th>Kepala Store</th>
                                                @if ($session_user->role_name == 'IT Developer')
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                @endif
                                                <th>Status</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($store as $outlet)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <div style="display: flex;gap:10px;" class="btn-action">

                                                                <a
                                                                    href="{{ route('store_update', $outlet->store_code) }}"><i
                                                                        class="fas fa-edit"></i></a>

                                                                {{-- <a href="#" data-toggle="modal"
                                                                    data-target="#deleteModal{{ $outlet->store_code }}"><i
                                                                        class="fas fa-trash"></i></a> --}}
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>{{ $outlet->store_code }}</td>
                                                    <td> {{ $outlet->store_name }} </td>
                                                    <td>{{ $outlet->location ?: '-' }}</td>
                                                    <td> {{ $outlet->nik . ' - ' . $outlet->name }}
                                                        @if ($outlet->head_of_branch)
                                                            <br>
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#deleteHeadStore{{ $outlet->store_code }}"><i
                                                                    class="fas fa-trash"></i></a>
                                                        @endif
                                                    </td>
                                                    @if ($session_user->role_name == 'IT Developer')
                                                        <td>{{ $outlet->latitude ?: '-' }}</td>
                                                        <td> {{ $outlet->longitude ?: '-' }} </td>
                                                    @endif
                                                    <td> {{ $outlet->status_name ?: 'Tidak aktif' }}
                                                        <br>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#updateStatus{{ $outlet->store_code }}"><i
                                                                class="fas fa-edit"></i></a>
                                                    </td>
                                                    <td>{{ $outlet->created_at }}</td>
                                                    <td>{{ $outlet->updated_at }}</td>
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
                                                <h3>Belum ada Store</h3>
                                                @if (!$user_permission_forbidden)
                                                    <p class="text-secondary">Tambah data kategori </p>
                                                    <a class="btn btn-primary" href="{{ 'category_create' }}">Tambah
                                                        Kategori</a>
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

    @foreach ($store as $outlet)
        <div wire:ignore class="modal fade" id="deleteHeadStore{{ $outlet->store_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $outlet->store_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Kepala Store</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus Kepala Store
                        {{ $outlet->store_name }} ?</div>
                    <div class="modal-footer">
                        <form action="{{ route('delete_head_store', $outlet->store_code) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal update status --}}

    @foreach ($store as $outlet)
        <div wire:ignore class="modal fade" id="updateStatus{{ $outlet->store_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $outlet->store_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Status Store</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin mengubah status
                        {{ $outlet->store_name }} ?
                        <hr>
                        <form action="{{ route('update_status_store', $outlet->store_code) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select class="form-control" name="status" id="">
                                <option value="">=== Pilih Status ===</option>
                                @foreach ($status as $item)
                                    <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach


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
