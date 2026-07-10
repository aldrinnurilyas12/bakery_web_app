<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Bisnis - Distribusi Produk</title>
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

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <table width="100%">
        <tr>
            <td>
                <h2>Laporan Distribusi Produk</h2>

                <p>
                    <strong>Store:</strong> {{ $store_name->store_name ?? '-' }} <br>
                    <strong>Tgl.cetak:</strong> {{ now() }} <br>
                    <strong>Dicetak oleh:</strong>
                    {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik . ' - ' . app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name }}
                </p>
            </td>

            <!-- Spacer agar teks benar-benar berada di tengah -->
            <td width="80"></td>


            <td align="right" width="80" valign="top">
                <img src="{{ public_path('assets/front_end/assets/logo/kencanabakery_logo2.png') }}" width="70">
            </td>


        </tr>
    </table>

    <div class="line"></div>

    <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} -
    {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
    <br>
    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Ref.Distribusi</th>
                <th>Kode Distribusi</th>
                <th>Produk</th>
                <th>Variant</th>
                <th>Quantity</th>
                <th>Total Diterima</th>
                <th>Total Reject</th>
                <th>Tgl Expired</th>
                <th>Approval oleh</th>
                <th>Status</th>
                <th>Store</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($distribution_product as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->distribution_store_code }}</td>
                    <td>{{ $row->distribution }}</td>
                    <td>{{ $row->product }}</td>
                    <td>{{ $row->variant ?: '-' }}</td>
                    <td>{{ $row->quantity }}</td>
                    <td>{{ $row->received_quantity }}</td>
                    <td>{{ $row->reject_quantity }}</td>
                    <td>{{ $row->expired_date }}</td>
                    <td>{{ $row->approval }}</td>
                    <td>{{ $row->status_name }}</td>
                    <td>{{ $row->store_name }}</td>
                    <td>{{ $row->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px 0;
        vertical-align: top;
        border: 1px solid black;
    }

    table,
    tr,
    td,
    th {
        border: none !important;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }
</style>

</html>
