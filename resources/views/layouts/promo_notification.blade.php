<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Promo Terbaru</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

    <div
        style="max-width:400px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h4>Hai, {{ $data['name'] }}</h4>
        <p style="font-size:14px; color:#555;">
            Hari ini ada informarsi Promo terbaru dari kami
        </p>

        <div style="margin:20px 0; font-size:18px; font-weight:bold; color:#333;">
            <h4>{{ $data['promo_name'] }}</h4>
        </div>

        <div class="promo-date" style="margin:20px 0; font-size:14px; color:#333;">
            <label for=""><strong>Tanggal berlaku promo: </strong></label><br>
            <span>{{ \Carbon\carbon::parse($data['start_date'])->format('d M Y') }} s/d
                {{ \Carbon\carbon::parse($data['end_date'])->format('d M Y') }}</span>
        </div>
        <p style="text-align: center;">Silahkan kunjungi website kami untuk informasi lebih detail</p>

        <p style="font-size:12px; color:#999;">
            Nikmati & tunggu promo-promo lainnya dari kami, Silahkan Kunjungi Outlet kami!
        </p>

        <footer>
            <p>Kencana Bakery &copy; {{ now()->year }}</p>
        </footer>

    </div>

</body>

</html>
