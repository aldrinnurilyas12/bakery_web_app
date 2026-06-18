<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bisnis - Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Laporan Transaksi</h2>

<p>
    Store: {{ $store_name->store_name ?? '-' }} <br>
    Periode: {{ $start_date }} - {{ $end_date }} <br>
    Tgl.cetak: {{ now() }} <br>
    Dicetak oleh: {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik . ' - ' .app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name }}
</p>

<h3>Transaction Sales</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Kode Transaksi</th>
            <th>Quantity</th>
            <th>Total Bayar</th>
            <th>Kembalian</th>
            <th>Grand Total</th>
            <th>Store</th>
            <th>Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaction as $key => $row)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $row->name ?: '-' }}</td>
            <td>{{ $row->transaction_code }}</td>
            <td>{{ $row->total_qty }}</td>
            <td>{{ 'Rp.' . number_format($row->total_amount) }}</td>
            <td>{{ 'Rp.' . number_format($row->payment_changes) }}</td>
            <td>{{ 'Rp.' . number_format($row->grand_total) }}</td>
            <td>{{ $row->store_name }}</td>
            <td>{{ $row->transaction_date }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>