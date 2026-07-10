<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Produk Daily</title>
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
                                Master Data / <a href="{{ route('master_category.index') }}">Produk Daily Detail</a>
                            </div>
                            @if ($daily_products->isNotEmpty())
                               Store : {{ $daily_products->first()->store_name }}
                            @endif
                        </div>
                        <hr>
                        <div style="font-size: 13px;" class="alert alert-info">
                            <ul>
                                <li>Nonaktifkan Produk Daily jika produk sudah melewati masa batas Expired
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            @if ($daily_products->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Aksi</th>
                                                <th>Info Produk</th>
                                                <th>Info Distribusi</th>
                                                <th>Info Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($daily_products as $key => $daily)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if($daily->status_expired == 'Expired' && $daily->expired_status == 'N')
                                                    <td><a class="btn-general" href="../#" data-toggle="modal"
                                                        data-target="#showUpdateExp{{ $daily->distribution_store_code }}">Konfirmasi Expired</a></td>
                                                    @elseif($daily->status_expired == 'Hampir Expired' && $daily->expired_status == 'N')
                                                    <td><a class="btn-general" href="../#" data-toggle="modal"
                                                        data-target="#showUpdateExp{{ $daily->distribution_store_code }}">Konfirmasi Expired</a></td>
                                                    @elseif($daily->status_expired == 'Aman' && $daily->expired_status == 'N')
                                                       <td><i style="color:green;" class="fas fa-check-square"></i><span> Aman</span></td>
                                                    @else
                                                    <td><i style="color:green;" class="fas fa-check-square"></i><span> Konfirmasi Expired</span></td>
                                                    @endif

                                                     <td>
                                                       <table class="table table-bordered">
                                                        <tbody>
                                                              <tr>
                                                                <th>SKU Produk</th>
                                                                <td>{{ $daily->product }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Produk</th>
                                                                <td>{{ $daily->product_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Variant</th>
                                                                <td>{{ $daily->variant_name ?: '-' }}</td>
                                                            </tr>
                                                            

                                                            <tr>
                                                                <th>Produk Daily</th>
                                                                <td>@if($daily->product_daily == 'Y')
                                                                    <span>Ya</span>
                                                                    @elseif($daily->product_daily == 'N')
                                                                    <span>Tidak</span>
                                                                    @else
                                                                    <span>Belum</span>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                              <tr>
                                                                <th>Tanggal Expired</th>
                                                                <td>{{ \Carbon\carbon::parse($daily->expired_date)->format('d M Y') }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Status Expired</th>
                                                                <td>
                                                                    @if($daily->status_expired == 'Expired')
                                                                    <span class="text-danger">Expired</span>
                                                                    @elseif($daily->status_expired == 'Hampir Expired')
                                                                    <span class="text-info">Hampir Expired</span>
                                                                    @else
                                                                    <span class="text-success">Aman</span>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    </td>

                                                    <td>
                                                       <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Kode Daily</th>
                                                                <td>{{ $daily->daily_code }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Kode Distribusi</th>
                                                                <td>{{ $daily->distribution }}</td>
                                                            </tr>
                                                            

                                                            <tr>
                                                                <th>Kode Distribusi Store</th>
                                                                <td>{{ $daily->distribution_store_code }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Tanggal Diterima</th>
                                                                <td>{{ \Carbon\carbon::parse($daily->received_date)->format('d M Y h:i:s') }}</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    </td>

                                                     <td>
                                                       <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Quantity</th>
                                                                <td>{{ $daily->quantity }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Diterima</th>
                                                                <td>{{ $daily->received_quantity }}</td>
                                                            </tr>
                                                            

                                                            <tr>
                                                                <th>Total Reject</th>
                                                                <td>{{ $daily->reject_quantity }}</td>
                                                            </tr>

                                                        </tbody>
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
                                                <h3>Belum ada Kategori</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('category_create') }}">Tambah
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

    @foreach ($daily_products as $daily)
        <div wire:ignore class="modal fade" id="showUpdateExp{{ $daily->distribution_store_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $daily->distribution_store_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah data daily produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="form-delete" action="{{ route('update_expired_status_distribution', $daily->distribution_store_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                    <div class="modal-body">Apakah anda yakin Produk ini mengalami expired?
                        <br>
                        <br>
                        <div class="form-group">
                            <label for=""><strong>Kode Distribusi</strong></label>
                            <input class="form-control" name="distribution_store" type="text" value="{{ $daily->distribution_store_code }}" readonly>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="checkbox" required> Ya, Produk sudah Expired
                        </div>

                    </div>
                    <div class="modal-footer">

                            <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                class="btn-text">Menyimpan data</span>
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
