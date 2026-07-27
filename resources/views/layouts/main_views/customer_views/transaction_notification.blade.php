<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Transaksi berhasil</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

    <div
        style="max-width:400px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h2 style="color:#d62828; margin-bottom:10px;">
            Terima Kasih Telah Berbelanja
        </h2>

        <h4>Hai, {{ $data['name'] }}</h4>

        <p style="font-size:14px; color:#555; line-height:1.6;">
            Pesanan Anda telah berhasil <br>
            Berikut detail transaksi Anda:
        </p>

        <div
            style="background:#f8f8f8; padding:15px; border-radius:8px; text-align:left; margin:20px 0; font-size:14px; color:#333;">

            <p style="margin:8px 0;">
                <strong>No. Pesanan:</strong> #{{ $data['transaction_code'] }}
            </p>

            <p style="margin:8px 0;">
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($data['transaction_date'])->format('d M Y') }}
            </p>

            <p style="margin:8px 0;">
                <strong>Total Belanja:</strong>
                Rp {{ number_format($data['grand_total'], 0, ',', '.') }}
            </p>

            <p style="margin:8px 0;">
                <strong>Status:</strong>
                <i style="color:green;" class="fas fa-check-square"></i> Berhasil
            </p>

        </div>

        <a href="{{ route('invoice', $data['transaction_code']) }}"
            style="display:inline-block; padding:12px 24px; background:#d62828; color:#ffffff;
        text-decoration:none; border-radius:6px; font-size:14px; font-weight:bold;">
            Lihat Detail Pesanan
        </a>

        <p style="margin-top:25px; font-size:13px; color:#777;">
            Terima kasih telah berbelanja di <strong>Kencana Bakery</strong>.
        </p>

        <footer style="margin-top:30px;">
            <p style="font-size:12px; color:#999;">
                Kencana Bakery &copy; {{ date('Y') }}
            </p>
        </footer>

    </div>


</body>

</html>
