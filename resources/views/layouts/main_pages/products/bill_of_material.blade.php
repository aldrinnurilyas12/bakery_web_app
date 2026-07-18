<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Bill of Material</title>
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
                                Master Data > <strong> Bill of Material </strong>
                            </div>
                            @if ($bill_of_material->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    @if ($bill_of_material->first()->status == 7)
                                    @else
                                        <div class="button-add-product">
                                            <a class="btn-general"
                                                href="{{ route('add_ingredients', $bill_of_material->first()->product_code) }}">Tambah
                                                Bill of Material</a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <div class="card-header">

                            <div style="display: flex; justify-content: space-between;" class="title">
                                <a class="btn btn-primary" href="{{ route('products_data') }}">kembali</a>

                                <div class="button-add-product">
                                    <a class="btn-general"
                                        href="{{ route('product_price', $bill_of_material->first()->product_code) }}">Tambah
                                        Harga Produk</a>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div style="font-size: 13px;" class="alert alert-info">
                            <ul>
                                <li>Harga HPP hanya bisa aktif satu saja, jika ingin menambah data Bill of Material baru
                                    maka ubah status
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            @if ($bill_of_material->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Detail</th>
                                                <th>Produk</th>
                                                <th>HPP</th>
                                                <th>Status</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($bill_of_material as $bom)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Aksi</th>
                                                                    <th>Detail</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        @if ($bom->status == 7)
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#showStatus{{ $bom->ingredients_code }}"><i
                                                                                    class="fa fa-edit"></i></a>
                                                                    </td>
                                                                @else
                                                                    <span>-</span>
                                            @endif
                                            <td>
                                                <a
                                                    href="{{ route('bill-of-material-detail', $bom->ingredients_code) }}"><i
                                                        class="fa fa-eye"></i></a>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    </td>
                                    <td>{{ $bom->product_name }}</td>
                                    <td> {{ 'Rp.' . number_format($bom->hpp) ?: '-' }} </td>
                                    <td>
                                        @if ($bom->status == 7)
                                            <span class="text-success">Aktif</span>
                                        @else
                                            <span class="text-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $bom->created_at }}</td>
                                    <td>{{ $bom->updated_at }}</td>
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
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div style="display: block;" class="text-content">
                                        <h3>Belum ada Bill of Material untuk Produk Ini</h3>
                                        @if (!$user_permission_forbidden)
                                            <a class="btn btn-primary"
                                                href="{{ route('add_ingredients', $bill_of_material->first()->product_code) }}">Tambah
                                                Bill of Material</a>
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


    @foreach ($bill_of_material as $bom)
        <div wire:ignore class="modal fade" id="showStatus{{ $bom->ingredients_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $bom->ingredients_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah status BoM => {{ $bom->ingredients_code }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-delete" action="{{ route('update_status_bom', $bom->ingredients_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>Kode BoM (Bill of Material)</strong></label>
                                <input name="ingredients_code" type="text" class="form-control"
                                    value="{{ $bom->ingredients_code }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for=""><strong>Ubah status</strong></label>
                                <select class="form-control" name="status" id="" required>
                                    @foreach ($status as $st)
                                        <option value="{{ $st->id }}">{{ $st->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                class="btn-text">Menyimpan</span>
                            <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>


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
