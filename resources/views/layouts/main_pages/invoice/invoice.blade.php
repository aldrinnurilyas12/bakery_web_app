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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="card">
        <div class="card-body">
            <div class="container mb-5 mt-3">
                <div class="row d-flex align-items-baseline">
                    <div class="col-xl-9">
                        <p class="fw-bold">Invoice :
                            <span class="text-primary">{{ $invoice->transaction_code }}</span>
                        </p>
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
                                <li><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span class="fw-bold">Tanggal
                                        Transaksi: </span>{{ $invoice->transaction_date }}</li>
                                @if ($invoice->status == 'Completed')
                                    <li><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                            class="me-1 fw-bold">Status:</span><span
                                            class="badge bg-success text-white">
                                            Sukses</span></li>
                                @else
                                    <li><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                            class="me-1 fw-bold">Status:</span><span class="badge bg-danger text-white">
                                            Gagal</span></li>
                                @endif

                                @if ($invoice->promo_code)
                                    <li><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                            class="me-1 fw-bold">Kode Promo:</span><span
                                            class="badge bg-success text-white">{{ $invoice->promo_code }}</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="row my-2 mx-1 justify-content-center">
                        <table class="table table-striped table-borderless">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Banyak</th>
                                    <th scope="col">Harga</th>
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
                                        <td>{{ 'Rp.' . number_format($inv->price) }}</td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                        </div>
                        <div class="col-xl-3">
                            <ul style="color: black;" class="list-unstyled">
                                <li class="text-muted ms-3"><span class="text-black me-4">Qty:</span>
                                    <br>{{ $invoice->quantity }} Item
                                </li>
                                @if ($invoice->promo_code)
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
                                    <li class="text-muted ms-3"><span class="text-black me-4">Total Amount:</span>
                                        <br>{{ 'Rp.' . number_format($invoice->grand_total) }}
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
                                    <td>{{ 'Rp.' . number_format($invoice->grand_total) }}</td>
                                </span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-10">
                            <div style="display: flex; gap:10px;" class="btn-btn-invoice">
                                <a class="btn btn-primary" href="{{ route('transaction.index') }}">Kembali</a>
                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-primary text-capitalize"
                                    style="background-color:#1abc8b; border:none;">
                                    <i class="fas fa-file"></i>Cetak Invoice</button>
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

</html>
