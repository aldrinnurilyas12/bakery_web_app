<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Reports</title>
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
                                Business Intelligence > <strong>Laporan Bisnis</strong>
                            </div>
                        </div>


                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="filter-reports">
                                <form action="{{ route('generate_reports') }}" method="GET">
                                    <div class="date"
                                        style="display: flex; flex-wrap: wrap; justify-content: center; gap:20px;">
                                        <div class="start-date">
                                            <label for=""><strong>Tanggal awal</strong></label>
                                            <input class="form-control" type="date"
                                                value="{{ old('start_date', request('start_date')) }}" name="start_date"
                                                required>
                                        </div>
                                        <div class="start-date">
                                            <label for=""><strong>Tanggal akhir</strong></label>
                                            <input class="form-control" type="date"
                                                value="{{ old('end_date', request('end_date')) }}" name="end_date"
                                                required>
                                        </div>

                                        <div class="store">
                                            <label for=""><strong>Store</strong></label>
                                            <select class="form-control" name="store" id="" required>
                                                <option value="">=== Pilih Outlet ===</option>
                                                @foreach ($store as $st)
                                                    <option value="{{ $st->store_code }}"
                                                        {{ old('store', request('store')) == $st->store_code ? 'selected' : '' }}>
                                                        {{ $st->store_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div style="align-self: end;" class="btn-choose">
                                            <button type="submit" class="btn btn-primary">Pilih</button>
                                        </div>

                                        <div style="align-self: end;" class="btn-choose">
                                            <a href="{{ route('business_reports') }}" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div style="display:block;" class="title">
                                @if (request('start_date'))
                                    Tanggal: {{ request('start_date') . ' s/d ' . request('end_date') }}
                                    <br>

                                    Store: {{ $stores->store_name }}
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Transaksi</th>
                                            <th>Produk Daily</th>
                                            <th>Produksi Produk</th>
                                            <th>Distribusi Produk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                @if (request('start_date'))
                                                    @if ($transaction->isNotEmpty())
                                                        <a style="width: 150px;" class="btn-general"
                                                            href="{{ route('download_transaction_report', [
                                                                'start_date' => request('start_date'),
                                                                'end_date' => request('end_date'),
                                                                'store' => request('store'),
                                                            ]) }}"><i
                                                                class="fa fa-download"></i> Laporan </a>
                                                    @else
                                                        Tidak ada data
                                                    @endif
                                                @else
                                                    <span>data tidak ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (request('start_date'))
                                                    @if ($products_daily->isNotEmpty())
                                                        <a style="width: 150px;" class="btn-general"
                                                            href="{{ route('download_products_daily_report', [
                                                                'start_date' => request('start_date'),
                                                                'end_date' => request('end_date'),
                                                                'store' => request('store'),
                                                            ]) }}"><i
                                                                class="fa fa-download"></i> Laporan </a>
                                                    @else
                                                        Tidak ada data
                                                    @endif
                                                @else
                                                    <span>data tidak ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (request('start_date'))
                                                    @if ($production_products->isNotEmpty())
                                                        <a style="width: 150px;" class="btn-general"
                                                            href="{{ route('download_production_product_report', [
                                                                'start_date' => request('start_date'),
                                                                'end_date' => request('end_date'),
                                                                'store' => request('store'),
                                                            ]) }}"><i
                                                                class="fa fa-download"></i> Laporan </a>
                                                    @else
                                                        Tidak ada data
                                                    @endif
                                                @else
                                                    <span>data tidak ada</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (request('start_date'))
                                                    @if ($distribution_products->isNotEmpty())
                                                        <a style="width: 150px;" class="btn-general"
                                                            href="{{ route('download_distribution_report', [
                                                                'start_date' => request('start_date'),
                                                                'end_date' => request('end_date'),
                                                                'store' => request('store'),
                                                            ]) }}"><i
                                                                class="fa fa-download"></i> Laporan </a>
                                                    @else
                                                        Tidak ada data
                                                    @endif
                                                @else
                                                    <span>data tidak ada</span>
                                                @endif
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
