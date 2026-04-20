<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Item</title>
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
                                Master Data / <a href="{{ route('master_items.index') }}">Items</a>
                            </div>
                            @if ($items->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('item_create') }}">Tambah
                                            Item</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($items->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Kode Item</th>
                                                <th>Raw Material</th>
                                                <th>Nama Item</th>
                                                <th>Kategori</th>
                                                <th>Tipe Weight</th>
                                                <th>Dibuat pada</th>
                                                <th>Dibuat ole</th>
                                                <th>Diubah pada</th>
                                                <th>Diubah oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <div style="display: flex;gap:10px;" class="btn-action">

                                                                @if ($item->category_name == 'Raw Material')
                                                                    <a
                                                                        href="{{ route('material_update', $item->raw_material) }}"><i
                                                                            class="fas fa-edit"></i></a>
                                                                @else
                                                                    <a
                                                                        href="{{ route('item_update', $item->item_code) }}"><i
                                                                            class="fas fa-edit"></i></a>
                                                                @endif
                                                                {{-- <a href="#" data-toggle="modal"
                                                                    data-target="#deleteModal{{ $item->item_code }}"><i
                                                                        class="fas fa-trash"></i></a> --}}
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->item_code }}</td>
                                                    <td> {{ $item->raw_material ?: '-' }} </td>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td>{{ $item->category_name }}</td>
                                                    <td>{{ $item->weight_type ?: '-' }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->created_by }}</td>
                                                    <td>{{ $item->updated_at ?: '-' }}</td>
                                                    <td>{{ $item->updated_by ?: '-' }}</td>
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
                                                <h3>Belum ada data Item</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('category_create') }}">Tambah
                                                        Item</a>
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

    @foreach ($items as $item)
        <div wire:ignore class="modal fade" id="deleteModal{{ $item->item_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $item->item_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data kategori produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus Kategori
                        {{ $item->category_name }} ?</div>
                    <div class="modal-footer">
                        <form class="form-delete" action="{{ route('master_items.destroy', $item->item_code) }}"
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
