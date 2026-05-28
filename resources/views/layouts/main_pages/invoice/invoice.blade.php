<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Invoice</title>
    <!-- jQuery CDN -->
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">

</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="container mb-5 mt-3">
                <div class="row d-flex align-items-baseline">
                    <div class="col-xl-9">
                        <div style="display: block;" class="group-store">
                            <p class="fw-bold"> Invoice :
                                <span class="text-primary">{{ $invoice->transaction_code }}</span>
                            </p>

                            <p class="fw-bold">Store/Outlet :
                                <span style="font-weight:normal;">{{ $invoice->store_name }}</span>
                            </p>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="container">


                    <div class="row">
                        @if ($invoice->name && $invoice->email)
                            <div class="col-xl-8">
                                <ul class="list-unstyled">
                                    <li><span class="fw-bold">ID Pelanggan :</span>
                                        <span>{{ $invoice->customer_code }}</span>
                                    </li>
                                    <li><span class="fw-bold">Pelanggan :</span> <span>{{ $invoice->name }}</span>
                                    </li>
                                    <li><span class="fw-bold">Email :</span> <span>{{ $invoice->email }}</span>
                                    </li>

                                </ul>
                            </div>
                        @else
                            <div class="col-xl-8">
                                <ul class="list-unstyled">
                                    <li><span class="fw-bold">Customer :</span> <span>Non Member</span>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        <div class="col-xl-4">
                            <ul class="list-unstyled">
                                <li> <span class="fw-bold">Tanggal
                                        Transaksi: </span>{{ $invoice->transaction_date }}</li>
                                @if ($invoice->status == 'Completed')
                                    <li> <span class="me-1 fw-bold">Status:</span><span
                                            class="badge bg-success text-white">
                                            Sukses</span></li>
                                @else
                                    <li> <span class="me-1 fw-bold">Status:</span><span
                                            class="badge bg-danger text-white">
                                            Gagal</span></li>
                                @endif

                                @if ($invoice->voucher_code_used)
                                    <li> <span class="me-1 fw-bold">Kode Promo:</span><span
                                            class="badge bg-success text-white">{{ $invoice->voucher_code_used }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div style="overflow: auto;" class="scroll-table">
                        <div class="justify-content-center">
                            <table class="table table-striped table-borderless">
                                <thead style="background-color:#84B0CA ;" class="text-white">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Banyak</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    ?>
                                    @foreach ($invoices as $inv)
                                        <tr>
                                            <td>
                                                <?php echo $no++; ?>
                                            </td>
                                            <td>{{ $inv->product_name }}</td>
                                            <td>{{ $inv->quantity_per_product }}</td>
                                            <td>
                                                @if ($inv->variant_price)
                                                    {{ 'Rp.' . number_format($inv->variant_price) }}
                                                @else
                                                    {{ 'Rp.' . number_format($inv->price) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($inv->variant_price)
                                                    {{ 'Rp.' . number_format($inv->variant_price * $inv->quantity_per_product) }}
                                                @else
                                                    {{ 'Rp.' . number_format($inv->price * $inv->quantity_per_product) }}
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                        </div>
                        <div class="col-xl-3">
                            <ul style="color: black;" class="list-unstyled">
                                @php
                                    $total_qty = DB::table('transactions_detail')
                                        ->where('transaction_code', $invoice->transaction_code)
                                        ->sum('quantity_per_product');

                                @endphp


                                <li class="text-muted ms-3"><span class="text-black me-4">Total Item:</span>
                                    <br>{{ $total_qty }} Item
                                </li>

                                @if ($invoice->voucher_code_used)
                                    @if ($invoice->discount)
                                        <li class="text-muted ms-3"><span class="text-black me-4">Potongan:</span>
                                            <br><span class="text-danger">{{ $invoice->discount }} % </span>
                                        </li>
                                    @else
                                        <li class="text-muted ms-3"><span class="text-black me-4">Potongan:</span>
                                            <br><span
                                                class="text-danger">{{ 'Rp.' . number_format($invoice->nominal) }}</span>
                                        </li>
                                    @endif
                                @endif
                                @if ($invoice->total_amount)
                                    <li class="text-muted ms-3"> <span class="text-black me-4">Subtotal:</span>
                                        <br>{{ 'Rp.' . number_format($invoice->subtotal_transaction) }}
                                        </span>
                                    </li>
                                    <li class="text-muted ms-3"><span class="text-black me-4">Total Amount:</span>
                                        <br>{{ 'Rp.' . number_format($invoice->total_amount) }}
                                    </li>

                                    @if ($invoice->payment_changes == 0)
                                        <li class="text-muted ms-3"><span class="text-black me-4">Payment
                                                Changes: -</span>
                                            <br>
                                        </li>
                                    @else
                                        <li class="text-muted ms-3"><span class="text-black me-4">Payment
                                                Changes:</span>
                                            <br>{{ 'Rp.' . number_format($invoice->payment_changes) }}
                                        </li>
                                    @endif
                                @else
                                    <li class="text-muted ms-3"> <span class="text-black me-4">Subtotal:</span>
                                        <br>{{ 'Rp.' . number_format($invoice->subtotal_transaction) }}
                                        </span>
                                    </li>
                                @endif
                                <hr>
                                {{-- <li class="text-muted ms-3"><span class="text-black me-4">Bayar:</span> <br>{{"Rp." . number_format($invoice->amount)}}</li>
                <li class="text-muted ms-3"><span class="text-black me-4">Kembalian:</span> <br>{{"Rp." . number_format($invoice->payment_changes)}}</li> --}}
                                <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Pay Method:</span>
                                    <br>
                                    @if ($invoice->payment_type)
                                        {{ $invoice->payment_type }}
                                </li>
                            @else
                                <p>-</p>
                                @endif
                            </ul>
                            <p class="text-black float-start"><span class="text-black me-3"> Grand Total</span><span
                                    style="font-size: 25px; font-weight:bold;"><br>
                                    <span>{{ 'Rp.' . number_format($invoice->grand_total) }}</span>
                                </span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="col-xl-10 no-print">
                        <div class="row">
                            <div class="col-xl-10">
                                <div style="display: flex; gap:10px;" class="btn-btn-invoice">
                                    <a class="btn btn-primary" href="{{ route('transaction.index') }}">Kembali</a>
                                    <button onclick="window.print()" type="button" data-mdb-button-init
                                        data-mdb-ripple-init class="btn btn-primary text-capitalize"
                                        style="background-color:#ac0017; border:none;">
                                        <i class="fa fa-print"></i> Cetak Invoice</button>

                                    <a class="btn btn-primary text-capitalize"
                                        style="background-color:#ac0017; border:none;"
                                        href="{{ url('/invoice/' . $invoice->transaction_code . '/print') }}"
                                        target="_blank">
                                        <i class="fa fa-file"></i>
                                        PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
    }

    .header {
        margin-bottom: 20px;
    }

    .title {
        font-size: 18px;
        font-weight: bold;
    }

    .text-primary {
        color: #0d6efd;
    }

    .section {
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th {
        background: #84B0CA;
        color: white;
        padding: 8px;
        text-align: left;
    }

    table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .right {
        text-align: right;
    }

    .bold {
        font-weight: bold;
    }


    .summary {
        margin-top: 20px;
        width: 100%;
    }

    .summary td {
        border: none;
        padding: 4px 0;
    }

    .grand-total {
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
    }


    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

</html>
