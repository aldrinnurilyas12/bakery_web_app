<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>

<body>

    <table width="100%">
        <tr>
            <td width="80" valign="top">
                <img src="{{ public_path('assets/front_end/assets/logo/kencanabakery_logo2.png') }}" width="70">
            </td>

            <td align="center">
                <strong style="font-size:16px;">Kencana Bakery</strong><br>
                Jl. Senayan<br>
                089758948050<br>
                www.kencanabakery.co.id
            </td>

            <!-- Spacer agar teks benar-benar berada di tengah -->
            <td width="80"></td>
        </tr>
    </table>


    <div class="line"></div>

    <table>
        <tr>
            <td align="left">
                No.Invoice: {{ $invoice->transaction_code }}
            </td>
            <td align="right">
                <span class="success-message">Transaksi Berhasil</span>
            </td>
        </tr>
        <tr>
            <td>Tanggal: {{ \Carbon\carbon::parse($invoice->transaction_date)->format('d M Y') }}</td>
        </tr>

        @if ($invoice->name)
            <tr>
                <td>Pelanggan: {{ $invoice->name }}</td>
            </tr>
        @else
            <tr>
                <td>Pelanggan: Non member</td>
            </tr>
        @endif


    </table>

    <div class="line"></div>

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

    <table>
        <thead>
            <tr>
                <th style="width:15%; text-align:center;">Jumlah</th>
                <th style="width:60%; text-align:left;">Nama Item</th>
                <th style="width:25%; text-align:right;">Harga</th>
            </tr>
        </thead>

        <tbody>

            {{-- Bundling --}}
            @if ($bundling)
                <tr>
                    <td class="qty" style="text-align:center;">
                        {{ $bundling->total_qty_bundle }}
                    </td>

                    <td class="product">
                        {{ $bundling->bundling_name }}

                        @if ($product_bundling->count())
                            <ul style="margin:5px 0 0 18px;padding:0;">
                                @foreach ($product_bundling as $pb)
                                    <li>{{ $pb->product_name }} × {{ $pb->quantity_per_product }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>

                    <td class="price" style="text-align:right;">
                        {{ 'Rp. ' . number_format($bundling->bundling_price, 0, ',', '.') }}
                    </td>
                </tr>
            @endif

            {{-- Produk Non Bundling --}}
            @foreach ($nonBundling as $inv)
                <tr>
                    <td class="qty" style="text-align:center;">
                        {{ $inv->quantity_per_product }}
                    </td>

                    <td class="product">
                        {{ $inv->product_name }}
                    </td>

                    <td class="price" style="text-align:right;">
                        {{ 'Rp. ' . number_format($inv->price, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <div class="line"></div>

    <table class="summary">

        <tr>
            <td>Total Item</td>
            <td class="text-right">{{ $total_qty }}</td>
        </tr>
        @if ($invoice->voucher_code_used)
            <tr>
                <td>Kode E-Voucher</td>
                <td class="text-right">{{ $invoice->voucher_code_used }}</td>
            </tr>

            <tr>
                <td>Potongan</td>
                @if ($invoice->discount)
                    <td class="text-right"><span class="failed-message">{{ '-' . $invoice->discount . '%' }}</span>
                    </td>
                @elseif($invoice->nominal)
                    <td>Potongan</td>
                    <td class="text-right"><span
                            class="failed-message">{{ '-' . 'Rp.' . number_format($invoice->nominal) }}</span></td>
                @else
                    <td class="text-right">-</td>
                @endif
            </tr>
        @endif
        @if ($invoice->payment_type == 'Cash/Tunai')
            <tr>
                <td>Pembayaran</td>
                <td class="text-right">{{ $invoice->payment_type }}</td>
            </tr>

            <tr>
                <td>Jumlah Bayar</td>
                <td class="text-right">{{ 'Rp.' . number_format($invoice->total_amount) }}</td>
            </tr>

            <tr>
                <td>Kembalian</td>
                <td class="text-right">{{ 'Rp.' . number_format($invoice->payment_changes) }}</td>
            </tr>
        @else
            <tr>
                <td>Pembayaran</td>
                <td class="text-right">{{ $invoice->payment_type }}</td>
            </tr>
        @endif
        <tr>
            <td><strong>Sub Total</strong></td>
            <td class="text-right"><strong>{{ 'Rp.' . number_format($invoice->subtotal) }}</strong></td>
        </tr>
        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="text-right"><strong>{{ 'Rp.' . number_format($invoice->grand_total) }}</strong></td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        <strong>Terima kasih telah berbelanja di toko kami.</strong><br>
        Nikmati promo lainnya dari kami
    </div>

</body>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #000;
        margin: 0;
        padding: 15px;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px 0;
        vertical-align: top;
    }

    .flex-content {
        display: flex;
        align-items: center;
        position: relative;
    }

    .logo {
        width: 80px;
    }

    span.success-message {
        padding: 4px;
        border-radius: 4px;
        border: 1px solid green;
        color: green;
    }

    span.failed-message {
        color: rgb(255, 0, 0);
    }

    .text-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
    }

    .qty {
        width: 12%;
    }

    .product {
        width: 58%;
    }

    .price {
        width: 30%;
        text-align: right;
    }

    .summary td {
        padding: 3px 0;
    }

    .footer {
        margin-top: 20px;
        text-align: center;
    }
</style>

</html>
