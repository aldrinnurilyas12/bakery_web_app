<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Transaksi</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Transaksi / <a href="{{ route('transaction.index') }}">Transaksi</a>
                            </div>

                            <div style="display: flex;gap:10px;" class="flex-content">

                                @if ($main_transaction->isNotEmpty())
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('transaction_create') }}">Tambah
                                            Transaksi</a>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <hr>
                        <div class="card-header">
                            <div class="title">
                                <div class="filter-data">
                                    <label for=""><strong>Filter Transaksi</strong></label>
                                    <br>
                                    <div style="display: flex; gap:10px;" class="d-flex-container-filter">
                                        <form action="{{ route('filter_transaction') }}" method="GET">
                                            <div style="display:flex;gap:20px;" class="d-flex-content">
                                                <div class="filter-date">
                                                    <select style="width:max-content;" name="filter_transaction"
                                                        id="" class="form-control">
                                                        <option value="">=== Pilih Data ===</option>
                                                        <option value="today"
                                                            {{ request('filter_transaction') == 'today' ? 'selected' : '' }}>
                                                            Hari ini</option>
                                                        <option value="week"
                                                            {{ request('filter_transaction') == 'week' ? 'selected' : '' }}>
                                                            Minggu ini</option>
                                                        <option value="month"
                                                            {{ request('filter_transaction') == 'month' ? 'selected' : '' }}>
                                                            Bulan ini</option>
                                                        <option value="year"
                                                            {{ request('filter_transaction') == 'year' ? 'selected' : '' }}>
                                                            Tahun ini</option>
                                                    </select>
                                                </div>

                                                @if (in_array(app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_id, [1, 2]))
                                                    <div class="filter-store">
                                                        <select class="form-control" name="store" id="">
                                                            <option value="">=== Pilih Store ===</option>
                                                            @foreach ($stores as $st)
                                                                <option value="{{ $st->store_code }}"
                                                                    {{ request('store') == $st->store_code ? 'selected' : '' }}>
                                                                    {{ $st->store_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                <button class="btn btn-primary">Pilih</button>
                                            </div>
                                        </form>




                                        <div class="excel-file">
                                            <form action="{{ route('export_transaction_excel') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="filter_transaction"
                                                    value="{{ request('filter_transaction') }}">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-file-excel"></i>
                                                    &nbsp; Excel
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($main_transaction->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Detail Item</th>
                                                <th>Invoice</th>
                                                <th>Tanggal</th>
                                                <th>Quantity</th>
                                                <th>Payment</th>
                                                <th>Total Bayar</th>
                                                <th>Kembalian</th>
                                                <th>Grand Total</th>
                                                <th>Pelanggan</th>
                                                <th>Jenis Transaksi</th>
                                                <th>Store</th>
                                                <th>Kasir</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($main_transaction as $transaction)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <div style="display: flex;gap:10px;" class="btn-action">

                                                            {{-- <a href="{{ route('category_update', $transaction->transaction_code) }}"><i
                                                                    class="fas fa-edit"></i></a> --}}
                                                            @if (in_array($transaction->transaction_code, $transaction_with_items))
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#showItems{{ $transaction->transaction_code }}"><i
                                                                        class="fas fa-eye"></i></a>
                                                            @else
                                                            @endif

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="text-black"
                                                            href="{{ route('invoice_detail', $transaction->transaction_code) }}">
                                                            {{ $transaction->transaction_code }}</a>
                                                    </td>
                                                    <td>{{ $transaction->transaction_date }}</td>
                                                    <td>{{ $transaction->total_qty }}</td>
                                                    <td>{{ $transaction->payment_type }}</td>
                                                    <td>
                                                        @if ($transaction->payment_type == 'Cash/Tunai')
                                                            {{ 'Rp. ' . number_format($transaction->total_amount) }}
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($transaction->payment_type == 'Cash/Tunai')
                                                            {{ 'Rp. ' . number_format($transaction->payment_changes) }}
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ 'Rp. ' . number_format($transaction->grand_total) }}</td>
                                                    <td>
                                                        @if ($transaction->customer)
                                                            {{ $transaction->customer . ' - ' . $transaction->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($transaction->transaction_type)
                                                            {{ $transaction->transaction_type }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($transaction->store_name)
                                                            {{ $transaction->store_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $transaction->casheer }}</td>
                                                    <td>{{ $transaction->created_at }}</td>
                                                    <td>{{ $transaction->updated_at }}</td>
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
                                            <div style="display: block;align-content: center;" class="text-content">
                                                <h3>Belum ada transaksi</h3>
                                                @if (!$user_permission_forbidden)
                                                    <p class="text-secondary">Buat transaksi pertama anda</p>
                                                    <a class="btn btn-primary" href="{{ 'transaction_create' }}">Buat
                                                        Transaksi</a>
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

    {{-- modal show detail --}}
    @foreach ($main_transaction as $transaction)
        <div class="modal fade" id="showItems{{ $transaction->transaction_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $transaction->transaction_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="position: sticky;" class="modal-header">
                        <h5 style="font-size: 13px;width:500px;" class="modal-title"
                            id="exampleModalLabel{{ $transaction->transaction_code }}">Invoice
                            #{{ $transaction->transaction_code }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div style="overflow-y: scroll;height:300px;" class="container-content">
                        <div style="padding: 20px; display:flex;flex-wrap:wrap; gap:20px;font-size:13px;"
                            class="content-card">
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        ?>
                                        @foreach ($show_items as $items)
                                            @if ($transaction->transaction_code == $items->transaction_code)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>{{ $items->product_code }}</td>
                                                    <td>{{ $items->product_name }}</td>
                                                    <td>{{ $items->quantity_per_product }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>
    <script src="{{ asset('bootstrap/js/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('bootstrap/js/js/bootstrap.bundle.js')}}"></script>
<script src="{{ asset('bootstrap/js/js/bootstrap.min.js')}}"></script> --}}

</body>

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

    .container-transaction {
        display: flex;
        gap: 20px;
        justify-content: space-between;
        padding: 20px;
    }

    .container-orders {
        display: flex;
        gap: 20px;
    }
</style>

</html>
