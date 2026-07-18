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
                    $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);
                @endphp
                <div class="container-fluid px-4">
                    <br>

                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="title">
                                Master Data > Produk Waste > <strong>Produk Waste Detail</strong>
                            </div>
                        </div>

                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <a class="btn btn-primary" href="{{ '../product-waste-data' }}">Kembali</a>
                        </div>

                        <div class="card-body">
                            <div wire:poll.keep.alive.2s>

                                <div class="table-responsive custom-scroll">
                                    <table class="table table-bordered align-middle text-center custom-table">

                                        <!-- HEADER -->
                                        <thead class="table-light">
                                            <tr>
                                                <th class="sticky-col">Product</th>

                                                @foreach ($waste_category as $waste_type)
                                                    <th>{{ $waste_type->waste_type }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>

                                        <!-- BODY -->
                                        <tbody>
                                            @foreach ($products as $prd)
                                                <tr>
                                                    <!-- Nama Produk -->
                                                    <td class="sticky-col text-start fw-semibold bg-cream"
                                                        style="background: #bb0239;color:white;">
                                                        {{ $prd->product_name }}
                                                    </td>

                                                    <!-- Kolom Waste Category -->
                                                    @foreach ($waste_category as $waste_type)
                                                        <td
                                                            style="{{ ($prd->waste[$waste_type->waste_code] ?? 0) > 0 ? 'background-color: #28a745; color: white;' : '' }}">
                                                            {{ $prd->waste[$waste_type->waste_code] ?? 0 }}
                                                        </td>
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </main>


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
            @elseif(Session::has('failed_message'))
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


        </div>
    </div>
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
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }


    /* Scroll horizontal */
    .custom-scroll {
        overflow-x: auto;
    }

    /* Paksa tabel lebar */
    .custom-table {
        min-width: 1200px;
    }

    /* Sticky kolom pertama (product) */
    .sticky-col {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 2;
    }

    /* Header lebih rapi */
    thead th {
        white-space: nowrap;
    }

    /* Cell tidak pecah */
    td,
    th {
        white-space: nowrap;
    }
</style>

</html>
