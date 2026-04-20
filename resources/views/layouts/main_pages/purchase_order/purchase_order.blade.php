<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Purchasing Order</title>
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
                    $purchase_detail = DB::table('v_purchase_order')->get();
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Master Data / <a href="{{ route('master_category.index') }}">Purchasing Order</a>
                            </div>
                            @if ($purchase_order->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('po_create') }}">Tambah
                                            Purchase Order</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($purchase_order->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Aksi</th>
                                                <th>Kode PO</th>
                                                <th>Supplier</th>
                                                <th>Tanggal PO</th>
                                                <th>Status</th>
                                                <th>Total Amount</th>
                                                <th>Pengiriman</th>
                                                <th>Tanggal Pengiriman</th>
                                                <th>Dibuat pada</th>
                                                <th>Dibuat oleh</th>
                                                <th>Diubah pada</th>
                                                <th>Diubah oleh</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>

                                            @foreach ($purchase_order as $po)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Bukti Bayar</th>
                                                                <th>Detail Item </th>
                                                            </tr>

                                                            <tr>
                                                                <td> <a href="#" data-toggle="modal"
                                                                        data-target="#showModal{{ $po->purchase_code }}"><i
                                                                            class="fas fa-eye"></i></a></td>
                                                                <td> <a href="#" data-toggle="modal"
                                                                        data-target="#showDetail{{ $po->purchase_code }}"><i
                                                                            class="fas fa-list"></i></a></td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                    <td style="font-weight: bold;">{{ $po->purchase_code }}</td>
                                                    <td>{{ $po->supplier_name }}</td>
                                                    <td>{{ $po->purchase_date }}</td>
                                                    <td>{{ $po->status_name }}</td>
                                                    <td>{{ 'Rp.' . number_format($po->total_amount) }}</td>
                                                    <td>
                                                        @if ($po->delivery == 'Y')
                                                            <span>Pengiriman</span>
                                                        @else
                                                            <span>Tidak</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $po->expected_delivery_date ?: '-' }}</td>
                                                    <td>{{ $po->created_at }}</td>
                                                    <td>{{ $po->name }}</td>
                                                    <td>{{ $po->updated_at }}</td>
                                                    <td>{{ $po->name }}</td>
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
                                                <h3>Belum ada Purchasing Order (PO)</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary" href="{{ route('po_create') }}">Tambah
                                                        Purchase Order</a>
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

    @foreach ($purchase_order as $po)
        <div wire:ignore class="modal fade" id="showModal{{ $po->purchase_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $po->purchase_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran #{{ $po->purchase_code }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img style="width: 100%; height:100%;" src="{{ 'storage/' . $po->payment_invoice }}"
                            alt="">
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    @endforeach



    @foreach ($purchase_order as $pd)
        <div wire:ignore class="modal fade" id="showDetail{{ $pd->purchase_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $pd->purchase_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Item #{{ $po->purchase_code }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    ?>
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($purchase_detail as $pds)
                                        @if ($pd->purchase_code == $pds->purchase_code)
                                            @php
                                                $grandTotal += $pds->subtotal;
                                            @endphp
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td>{{ $pds->item }}</td>
                                                <td>{{ $pds->item_name ?: '-' }}</td>
                                                <td>{{ $pds->quantity }}</td>
                                                <td>{{ 'Rp.' . number_format($pds->price) }}</td>
                                                <td>{{ 'Rp.' . number_format($pds->subtotal) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div style="width: 100%; text-align: right; font-weight: bold;">
                            Grand Total: {{ 'Rp. ' . number_format($grandTotal) }}
                        </div>
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
