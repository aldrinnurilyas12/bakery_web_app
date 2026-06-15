<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bisnis - Produksi Produk</title>
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

<h2>Laporan Produksi Produk</h2>

<p>
    Periode: {{ $start_date }} - {{ $end_date }} <br>
    Tgl.cetak: {{ now() }} <br>
    Dicetak oleh: {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik . ' - ' .app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name }}
</p>

<h3>Produksi Produk</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Produksi</th>
            <th>Produk</th>
            <th>Total Target</th>
            <th>Total Aktual</th>
            <th>Total Reject</th>
            <th>Total Cost</th>
            <th>Total Budget</th>
            <th>HPP</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($production_product as $key => $row)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $row->production_code}}</td>
            <td>{{ $row->product_name }}</td>
            <td>{{ $row->qty_target_total }}</td>
            <td>{{ $row->actual_quantity }}</td>
            <td>{{ $row->reject_quantity }}</td>
            <td>{{ 'Rp.' . number_format($row->total_cost) ?: '-' }}</td>
            <td>{{ 'Rp.' . number_format($row->budget_total) ?: '-' }}</td>
            <td>{{ 'Rp.' . number_format($row->hpp) }}</td>
            <td>{{ $row->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>