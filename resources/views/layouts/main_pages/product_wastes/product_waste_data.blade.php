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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                Master Data / <a href="{{ route('production_products') }}">Produk Waste</a>
                            </div>

                            <div style="display:flex;gap:20px;" class="btn">
                                <a class="btn btn-warning" href="{{ route('product-wastes') }}">Lihat
                                    Data</a>
                                <a class="btn btn-primary" href="{{ route('product-waste-create') }}">Tambah
                                    data</a>
                            </div>


                        </div>

                        <ul class="nav nav-tabs" id="expenseTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="production-tab" data-bs-toggle="tab"
                                    data-bs-target="#production" type="button">
                                    Produksi Produk
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="distribution-tab" data-bs-toggle="tab"
                                    data-bs-target="#distribution" type="button">
                                    Distribusi Produk
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily"
                                    type="button">
                                    Products Daily
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- Tab all products --}}
                            <div class="tab-pane fade show active" id="production" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">
                                        <div class="card-body">
                                            @if ($product_waste_production->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                @if (!$user_permission_forbidden)
                                                                    <th>Aksi</th>
                                                                @endif
                                                                <th>Kode Waste</th>
                                                                <th>Kode Produksi</th>
                                                                <th>Reason</th>
                                                                <th>Tanggal</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($product_waste_production as $key)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    @if (!$user_permission_forbidden)
                                                                        <td>
                                                                            <table style="font-size: 14px; color:black;"
                                                                                class="table table-bordered"
                                                                                id="dataTable" width="100%"
                                                                                cellspacing="0">

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <th>Attachment</th>
                                                                                    @endif
                                                                                    <th>Detail </th>
                                                                                </tr>

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <td> <a href="#"
                                                                                                data-toggle="modal"
                                                                                                data-target="#showModal{{ $key->waste_code }}"><i
                                                                                                    class="fas fa-eye"></i></a>
                                                                                        </td>
                                                                                    @endif
                                                                                    <td> <a
                                                                                            href="{{ route('product-waste-detail', $key->waste_code) }}"><i
                                                                                                class="fas fa-list"></i></a>
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                    <td>{{ $key->waste_code }}</td>
                                                                    <td> {{ $key->production_code }} </td>
                                                                    <td>{{ $key->reason ?: '-' }}</td>
                                                                    <td>{{ $key->waste_date }}</td>
                                                                    <td>{{ $key->created_at }}</td>
                                                                    <td>{{ $key->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;align-self: center;"
                                                                class="text-content">
                                                                <h3>Belum ada data</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane fade" id="distribution" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">
                                        <div class="card-body">
                                            @if ($product_waste_distribution->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                @if (!$user_permission_forbidden)
                                                                    <th>Aksi</th>
                                                                @endif
                                                                <th>Kode Waste</th>
                                                                <th>Kode Distribusi</th>
                                                                <th>Reason</th>
                                                                <th>Tanggal</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($product_waste_distribution as $key)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    @if (!$user_permission_forbidden)
                                                                        <td>
                                                                            <table
                                                                                style="font-size: 14px; color:black;"
                                                                                class="table table-bordered"
                                                                                id="dataTable" width="100%"
                                                                                cellspacing="0">

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <th>Attachment</th>
                                                                                    @endif
                                                                                    <th>Detail </th>
                                                                                </tr>

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <td> <a href="#"
                                                                                                data-toggle="modal"
                                                                                                data-target="#showModal{{ $key->waste_code }}"><i
                                                                                                    class="fas fa-eye"></i></a>
                                                                                        </td>
                                                                                    @endif
                                                                                    <td> <a
                                                                                            href="{{ route('product-waste-detail', $key->waste_code) }}"><i
                                                                                                class="fas fa-list"></i></a>
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                    <td>{{ $key->waste_code }}</td>
                                                                    <td> {{ $key->distribution }} </td>
                                                                    <td>{{ $key->reason ?: '-' }}</td>
                                                                    <td>{{ $key->waste_date }}</td>
                                                                    <td>{{ $key->created_at }}</td>
                                                                    <td>{{ $key->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;align-self: center;"
                                                                class="text-content">
                                                                <h3>Belum ada data</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="daily" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">
                                        <div class="card-body">
                                            @if ($product_waste_product_daily->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                @if (!$user_permission_forbidden)
                                                                    <th>Aksi</th>
                                                                @endif
                                                                <th>Kode Waste</th>
                                                                <th>Kode Daily Produk</th>
                                                                <th>Reason</th>
                                                                <th>Tanggal</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($product_waste_product_daily as $key)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    @if (!$user_permission_forbidden)
                                                                        <td>
                                                                            <table
                                                                                style="font-size: 14px; color:black;"
                                                                                class="table table-bordered"
                                                                                id="dataTable" width="100%"
                                                                                cellspacing="0">

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <th>Attachment</th>
                                                                                    @endif
                                                                                    <th>Detail </th>
                                                                                </tr>

                                                                                <tr>
                                                                                    @if ($key->attachment_files)
                                                                                        <td> <a href="#"
                                                                                                data-toggle="modal"
                                                                                                data-target="#showModal{{ $key->waste_code }}"><i
                                                                                                    class="fas fa-eye"></i></a>
                                                                                        </td>
                                                                                    @endif
                                                                                    <td> <a
                                                                                            href="{{ route('product-waste-detail', $key->waste_code) }}"><i
                                                                                                class="fas fa-list"></i></a>
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                    <td>{{ $key->waste_code }}</td>
                                                                    <td> {{ $key->product_daily }} </td>
                                                                    <td>{{ $key->reason ?: '-' }}</td>
                                                                    <td>{{ $key->waste_date }}</td>
                                                                    <td>{{ $key->created_at }}</td>
                                                                    <td>{{ $key->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;align-self: center;"
                                                                class="text-content">
                                                                <h3>Belum ada data</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
            </main>
        </div>
    </div>


    @foreach ($product_waste_production as $prd)
        <div wire:ignore class="modal fade" id="showModal{{ $prd->waste_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $prd->waste_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attachment File</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="img">
                            <img src="{{ 'storage/' . $prd->attachment_files }}" alt="">
                        </div>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($product_waste_distribution as $prd)
        <div wire:ignore class="modal fade" id="showModal{{ $prd->waste_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $prd->waste_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attachment File</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="img">
                            <img src="{{ 'storage/' . $prd->attachment_files }}" alt="">
                        </div>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($product_waste_product_daily as $prd)
        <div wire:ignore class="modal fade" id="showModal{{ $prd->waste_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $prd->waste_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attachment File</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="img">
                            <img src="{{ 'storage/' . $prd->attachment_files }}" alt="">
                        </div>

                    </div>
                    <div class="modal-footer">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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


    /* Container */
    .container {
        max-width: 900px;
    }

    /* Tabs wrapper */
    .nav-tabs {
        border-bottom: none;
        gap: 10px;
        padding: 25px;
    }

    /* Tab button */
    .nav-tabs .nav-link {
        border: none;
        color: #555;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Hover effect */


    /* Active tab */
    .nav-tabs .nav-link.active {
        background: #bb0239;
        color: #fff;

    }

    /* Tab content box */
    .tab-content {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Form styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #bb0239;
        box-shadow: none;
    }

    /* Button */
    .btn-primary {
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 500;
    }

    /* Title */
    h3 {
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>

</html>
