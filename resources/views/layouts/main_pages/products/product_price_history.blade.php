<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Histori Harga Produk</title>
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
                                Master Data > <strong> Histori Harga Produk </strong>
                            </div>
                        </div>
                        <div style="display: flex; gap:20px;" class="card-header">
                            <div class="back-btn">
                                <a class="btn btn-primary" href="{{ route('products_data') }}">Kembali</a>
                            </div>
                        </div>
                        @if ($product_price->isNotEmpty())
                            <div class="card-header">
                                <div class="form-group">
                                    Produk: <span
                                        style="font-weight: bold;">{{ $product_price->first()->product_name }}</span>
                                </div>
                                <div class="form-group">
                                    Variant: <span
                                        style="font-weight: bold;">{{ $product_price->first()->name ?: '-' }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="card-body">
                            @if ($product_price->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Detail Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($product_price as $key => $history)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <table class="table table-bordered" id="dataTable"
                                                            width="100%" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Harga terbaru</th>
                                                                    <th>Diskon terbaru</th>
                                                                    <th>Harga setelah diskon terbaru</th>
                                                                    <th>Tanggal harga efektif terbaru</th>
                                                                    <th>Harga lama</th>
                                                                    <th>Diskon lama</th>
                                                                    <th>Harga setelah diskon lama</th>
                                                                    <th>Tanggal harga efektif lama</th>
                                                                    <th>Status</th>
                                                                    <th>Created at</th>
                                                                    <th>Updated at</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td> {{ 'Rp.' . number_format($history->price_after) }}
                                                                    </td>
                                                                    <td> {{ $history->discount_after ?: '-' }}
                                                                    </td>
                                                                    <td> {{ !empty($history->price_after_discount_after)
                                                                        ? 'Rp.' . number_format($history->price_after_discount_after)
                                                                        : '-' }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($history->business_effective_date_new)->format('Y-m-d') }}
                                                                    </td>

                                                                    <td> {{ 'Rp.' . number_format($history->price_before) }}
                                                                    </td>
                                                                    <td> {{ $history->discount_before ?: '-' }}
                                                                    </td>
                                                                    <td> {{ !empty($history->price_after_discount_before)
                                                                        ? 'Rp.' . number_format($history->price_after_discount_before)
                                                                        : '-' }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($history->business_effective_date_old)->format('Y-m-d') }}
                                                                    </td>
                                                                    <td>{{ $history->status_name }}</td>
                                                                    <td>{{ $history->created_at }}</td>
                                                                    <td>{{ $history->updated_at }}</td>
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
                                                <h3>Belum ada history harga untuk produk ini</h3>
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
