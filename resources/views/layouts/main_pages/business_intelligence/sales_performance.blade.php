<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Sales Performance</title>
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
                                Business Intelligence > <strong>Performa Penjualan Produk</strong>
                            </div>
                        </div>
                        @if ($products_sales->isNotEmpty())
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th></th>
                                                <th>Products Sales Performance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                                $grouped = $products_sales->groupBy(function ($item) {
                                                    return $item->product_code . '-' . $item->variant_code;
                                                });

                                            @endphp

                                            @foreach ($grouped as $key => $group)
                                                @php
                                                    $first = $group->first();

                                                    $product_images = DB::table('product_images')
                                                        ->where('product_code', $first->product_code)
                                                        ->first();

                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <div style="display:block;" class="span">
                                                            @if ($product_images)
                                                                <img width="100" height="100"
                                                                    src="{{ 'storage/' . $product_images->images }}"
                                                                    alt="">
                                                            @endif
                                                            <br>
                                                            <br>
                                                            <span
                                                                style="font-weight: 800;">{{ $first->product_name }}</span>
                                                            <br>
                                                            <span
                                                                style="font-weight: 400;">{{ $first->variant_category ?? '-' }}</span>

                                                        </div>
                                                    </td>

                                                    <td>
                                                        <table class="table table-bordered" style="font-size: 13px;">
                                                            <thead>
                                                                <tr>
                                                                    @if ($first->price)
                                                                        <th>Harga</th>
                                                                    @endif

                                                                    @if ($first->variant_price)
                                                                        <th>Harga Variant</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    @if ($first->price)
                                                                        <td>{{ $first->price ? 'Rp.' . number_format($first->price) : '-' }}
                                                                        </td>
                                                                    @endif

                                                                    @if ($first->variant_price)
                                                                        <td>{{ $first->variant_price ? 'Rp.' . number_format($first->variant_price) : '-' }}
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>



                                                    <td>
                                                        <table class="table table-bordered" style="font-size: 13px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Store</th>
                                                                    <th>Total Produk</th>
                                                                    <th>Total Terjual</th>
                                                                    <th>Total Cost</th>
                                                                    <th>Revenue</th>
                                                                    <th>Profit</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($group as $item)
                                                                    <tr>
                                                                        <td>{{ $item->store ?? '-' }}</td>
                                                                        <td>{{ $item->total_product ?? '-' }}</td>
                                                                        <td>{{ $item->total_sold ?? 0 }}</td>
                                                                        <td>{{ $item->total_cost ? 'Rp.' . number_format($item->total_cost) : '0' }}
                                                                        </td>
                                                                        <td>{{ $item->revenue ? 'Rp.' . number_format($item->revenue) : '0' }}
                                                                        </td>
                                                                        <td>{{ $item->profit ? 'Rp.' . number_format($item->profit) : '0' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                class="empty-transaction">

                                <div style="display: flex;" class="empty-content">
                                    <div style="display: flex; gap:20px;margin:auto;" class="alert-info">
                                        <img width="70" height="70"
                                            src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                        <div style="display: block;" class="text-content">
                                            <h3>Belum ada data Sales Performance</h3>
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
