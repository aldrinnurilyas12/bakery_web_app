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
                    @php
                        $total_qty = DB::table('transactions_detail')
                            ->where('transaction_code', $invoice->transaction_code)
                            ->sum('quantity_per_product');

                        $bundling = DB::table('transactions_bundling as tb')
                            ->join('promo_bundling as pb', 'tb.bundling', '=', 'pb.bundling_code')
                            ->select(
                                'pb.bundling_name',
                                'pb.price as bundling_price',
                                'pb.images',
                                DB::raw("
            (SELECT COUNT(*)
                FROM transactions_detail td
                WHERE td.transaction_code = '{$invoice->transaction_code}'
                  AND td.promo_bundling IS NOT NULL)
as total_qty_bundle"),
                            )
                            ->where('tb.transaction', $invoice->transaction_code)
                            ->groupBy('pb.bundling_name', 'pb.price', 'pb.images')
                            ->first();

                        $product_bundling = collect();

                        if ($bundling) {
                            $product_bundling = DB::table('transactions_bundling as tb')
                                ->join('promo_bundling as pb', 'tb.bundling', '=', 'pb.bundling_code')
                                ->join('promo_bundling_detail as pd', 'pb.bundling_code', '=', 'pd.bundling_code')
                                ->join('products as p', 'pd.product', '=', 'p.product_code')
                                ->join('transactions_detail as td', function ($join) use ($invoice) {
                                    $join
                                        ->on('td.promo_bundling', '=', 'pb.bundling_code')
                                        ->on('td.product', '=', 'pd.product')
                                        ->where('td.transaction_code', '=', $invoice->transaction_code);
                                })
                                ->select(
                                    'pb.bundling_name',
                                    'pb.bundling_code',
                                    'pb.price as bundling_price',
                                    'pd.product',
                                    'pd.quantity as qty_bundling',
                                    'td.quantity_per_product',
                                    'p.product_name',
                                )
                                ->distinct()
                                ->where('tb.transaction', $invoice->transaction_code)
                                ->get();
                        }

                        /*
|--------------------------------------------------------------------------
| Produk Non Bundling
|--------------------------------------------------------------------------
*/
                        $nonBundling = DB::table('transactions_detail as td')
                            ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
                            ->where('td.transaction_code', $invoice->transaction_code)
                            ->whereNull('td.promo_bundling')
                            ->get();
                    @endphp

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

                                <li> <span class="me-1 fw-bold">Total items:</span><span>{{ $total_qty }}
                                        Item</span></li>
                                <li> <span class="me-1 fw-bold">Metode
                                        pembayaran:</span><span>{{ $invoice->payment_type ?: '-' }} </span></li>

                                @if ($invoice->voucher_code_used)
                                    <li> <span class="me-1 fw-bold">Kode Promo:</span><span
                                            class="badge bg-success text-white">{{ $invoice->voucher_code_used }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <p style="font-size: 14px;"><span class="me-1 fw-bold">Rincian Items</span></p>
                    <hr>
                    <div style="overflow-y: auto;height:400px;" class="scroll-table">
                        <div class="justify-content-center">

                            <div style="display:block">


                                {{-- Card Bundling (hanya sekali) --}}
                                @if ($bundling)
                                    <div class="card bg-light text-black mb-4">
                                        <div class="card-body"
                                            style="display:flex;align-items:center;gap:10px;font-weight:bold;">

                                            @if ($bundling->images)
                                                <img width="70" height="120"
                                                    src="{{ url('storage/' . $bundling->images) }}">
                                            @endif

                                            <div class="content-text">
                                                <span class="promo-badge">Promo
                                                    Bundling</span>

                                                <h5 style="font-size:15px;margin-top: 10px;">
                                                    {{ $bundling->bundling_name }}</h5>

                                                <div class="price-info">
                                                    <p>Rp. {{ number_format($bundling->bundling_price) }}</p>
                                                </div>

                                                <p style="margin-bottom: 0;">Rincian item:</p>

                                                <ul class="mb-0">
                                                    @if ($product_bundling->count())
                                                        @foreach ($product_bundling as $pb)
                                                            <li>{{ $pb->product_name }} ×
                                                                {{ $pb->quantity_per_product }}</li>
                                                        @endforeach

                                                    @endif
                                                </ul>

                                            </div>

                                        </div>
                                    </div>
                                @endif

                                {{-- Produk Non Bundling --}}
                                @foreach ($invoices as $inv)
                                    @if ($inv->promo_bundling == null)
                                        @php
                                            $product_image = DB::table('product_images')
                                                ->select('images', 'product_code')
                                                ->where('product_code', $inv->product)
                                                ->first();
                                        @endphp

                                        <div class="card bg-light text-black mb-4">
                                            <div class="card-body"
                                                style="display:flex;align-items:center;gap:10px;font-weight:bold;">

                                                <div style="display:flex;gap:10px">

                                                    @if ($product_image)
                                                        <img width="70" height="120"
                                                            src="{{ url('storage/' . $product_image->images) }}">
                                                    @endif

                                                    <div class="content-text">
                                                        <h5 style="font-size:15px">{{ $inv->product_name }}</h5>

                                                        <p>
                                                            <small>{{ $inv->quantity_per_product }} x</small>
                                                        </p>

                                                        <div class="price-info">
                                                            <p>Rp. {{ number_format($inv->price) }}</p>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                        </div>
                        <div class="col-xl-3">
                            <ul style="color: black;" class="list-unstyled">

                                @if ($invoice->voucher_code_used)
                                    @if ($invoice->discount)
                                        <li><span class="text-black me-4">Potongan:</span>
                                            <br><span class="text-danger">{{ $invoice->discount }} % </span>
                                        </li>
                                    @else
                                        <li><span class="text-black me-4">Potongan:</span>
                                            <br><span
                                                class="text-danger">{{ 'Rp.' . number_format($invoice->nominal) }}</span>
                                        </li>
                                    @endif
                                @endif
                                @if ($invoice->total_amount)

                                    <div style="display: flex; gap:6px;" class="flex-amount">
                                        <li><span class="me-1 fw-bold">Subtotal:</span>
                                            <br>{{ 'Rp.' . number_format($invoice->subtotal) }}
                                        </li>
                                        <span>|</span>
                                        <li><span class="me-1 fw-bold">Amount:</span>
                                            <br>{{ 'Rp.' . number_format($invoice->total_amount) }}
                                        </li>
                                        <span>|</span>
                                        @if ($invoice->payment_changes == 0)
                                            <li><span class="me-1 fw-bold">Payment
                                                    Changes: -</span>
                                                <br>
                                            </li>
                                        @else
                                            <li><span class="me-1 fw-bold">Payment
                                                    Changes:</span>
                                                <br>{{ 'Rp.' . number_format($invoice->payment_changes) }}
                                            </li>
                                        @endif
                                    </div>

                                @endif

                            </ul>
                            <p class="text-black float-start"><span class="me-1 fw-bold"> Grand Total</span><span
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
                                    <a class="btn btn-primary" href="{{ route('transaction_create') }}">Kembali</a>
                                    {{-- <button onclick="window.print()" type="button" data-mdb-button-init
                                        data-mdb-ripple-init class="btn btn-primary text-capitalize"
                                        style="background-color:#ac0017; border:none;">
                                        <i class="fa fa-print"></i> Cetak Invoice</button> --}}

                                    <a class="btn btn-primary text-capitalize"
                                        style="background-color:#ac0017; border:none;"
                                        href="{{ url('/invoice_pdf_download/' . $invoice->transaction_code) }}"
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

    .promo-badge {
        background: #bb0239;
        color: #fff;
        font-size: 0.6875rem;
        font-weight: bold;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;

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
