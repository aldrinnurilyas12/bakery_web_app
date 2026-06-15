<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bisnis - Products Daily</title>
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

<h2>Laporan Produk Daily</h2>

<p>
    Store: {{ $products_daily->first()->store_name ?? '-' }} <br>
    Periode: {{ $start_date }} - {{ $end_date }} <br>
    Tgl.cetak: {{ now() }} <br>
    Dicetak oleh: {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik . ' - ' .app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name }}
</p>

<h3>Produk Daily Sales</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Terjual</th>
            <th>Pendapatan</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products_daily as $key => $row)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $row->product_code}}</td>
            <td>{{ $row->product }}</td>
            <td>{{ $row->category }}</td>
            <td>@if($row->variant_code)
                {{ 'Rp.' . number_format($row->variant_price_after_discount) }}
                @else
                {{ 'Rp.' . number_format($row->price_after_discount) }}
                @endif
            </td>
            <td>{{ $row->stock_available ?: '-' }}</td>
            <td>{{ $row->total_sold ?: '-' }}</td>
            <td>@if($row->variant_code)
                {{ 'Rp.' . number_format($row->variant_price_after_discount * $row->total_sold) }}
                @else
                {{ 'Rp.' . number_format($row->price_after_discount * $row->total_sold) }}
                @endif</td>
            <td>{{ $row->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>