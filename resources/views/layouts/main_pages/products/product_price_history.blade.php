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
                        <hr>

                        <ul class="nav nav-tabs" id="expenseTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                    data-bs-target="#all" type="button">
                                    Harga Baru
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="old_price-tab" data-bs-toggle="tab"
                                    data-bs-target="#old_price" type="button">
                                    Harga Lama
                                </button>
                            </li>
                        </ul>

                        <hr>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
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
                                                                            @if ($history->price == null)
                                                                                <th>Harga Saat Ini</th>
                                                                            @else
                                                                                <th>Harga Terbaru</th>
                                                                            @endif

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            @if ($history->price)
                                                                                <td>
                                                                                    <table
                                                                                        style="font-size: 14px; color:black;"
                                                                                        class="table table-bordered"
                                                                                        id="dataTable" width="100%"
                                                                                        cellspacing="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th>Harga Normal
                                                                                                </th>
                                                                                                <td> {{ 'Rp.' . number_format($current_price->price) }}
                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <th>Diskon </th>
                                                                                                <td>
                                                                                                    @if ($history->discount == 0)
                                                                                                        -
                                                                                                    @else
                                                                                                        {{ $history->discount . '%' }}
                                                                                                    @endif

                                                                                                </td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <th>Harga setelah
                                                                                                    diskon
                                                                                                </th>
                                                                                                <td> {{ !empty($current_price->price_after_discount)
                                                                                                    ? 'Rp.' . number_format($current_price->price_after_discount)
                                                                                                    : '-' }}
                                                                                                </td>
                                                                                            </tr>


                                                                                            <tr>
                                                                                                <th>Tanggal harga
                                                                                                    efektif
                                                                                                </th>
                                                                                                <td>{{ \Carbon\Carbon::parse($current_price->price_effective_from)->format('d M Y') }}
                                                                                                </td>
                                                                                            </tr>


                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            @else
                                                                                <td>Tidak ada harga terbaru</td>
                                                                            @endif

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

                            {{-- Old Price --}}
                            <div class="tab-pane fade" id="old_price" role="tabpanel">
                                <div class="card-body">
                                    @if ($old_product_price)
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
                                                    @foreach ($old_product_price as $key => $history)
                                                        <tr>
                                                            <td><?php echo $no++; ?></td>

                                                            <td>
                                                                <table class="table table-bordered" id="dataTable"
                                                                    width="100%" cellspacing="0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Harga lama</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>

                                                                            {{-- Harga Lama --}}
                                                                            <td>
                                                                                <table
                                                                                    style="font-size: 14px; color:black;"
                                                                                    class="table table-bordered"
                                                                                    id="dataTable" width="100%"
                                                                                    cellspacing="0">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <th>Kode Harga </th>
                                                                                            <td> {{ $history->price_code }}
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <th>Harga Normal </th>
                                                                                            <td> {{ 'Rp.' . number_format($history->price) }}
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <th>Diskon</th>
                                                                                            <td>
                                                                                                @if ($history->discount == 0)
                                                                                                    -
                                                                                                @else
                                                                                                    {{ $history->discount . '%' }}
                                                                                                @endif

                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <th>Harga Diskon
                                                                                            </th>
                                                                                            <td> {{ !empty($history->price_after_discount) ? 'Rp.' . number_format($history->price_after_discount) : '-' }}
                                                                                            </td>
                                                                                        </tr>


                                                                                        <tr>
                                                                                            <th>Tanggal harga efektif
                                                                                            </th>
                                                                                            <td>{{ \Carbon\Carbon::parse($history->current_price_effective)->format('d M Y') }}
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>Status
                                                                                            </th>
                                                                                            <td>
                                                                                                @if ($history->status_name == 'Active')
                                                                                                    <span
                                                                                                        class="text-success">{{ $history->status_name }}</span>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="text-danger">{{ $history->status_name }}</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <th>Tanggal Buat
                                                                                            </th>
                                                                                            <td>{{ $history->created_at }}
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </td>


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
