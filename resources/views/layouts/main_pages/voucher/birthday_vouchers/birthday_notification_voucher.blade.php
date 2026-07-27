<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Selamat Ulang Tahun</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

    <div
        style="max-width:400px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h4>Selamat Ulang Tahun, {{ $data['name'] }} </h4>
        <p style="font-size:14px; color:#555;">
            Dengan komitmen anda yang selalu belanja di Store Kami, maka anda telah mendapatkan E-Voucher yang bisa anda
            gunakan saat Transaksi di Store Kami.
        </p>

        <div style="margin:20px 0; font-size:28px; font-weight:bold; color:#333;">
            <h6>{{ $data['voucher'] }}</h6>
        </div>

        <p style="font-size:12px; color:#999;">
            E-Voucher ini hanya berlaku dalam 25 hari, Silahkan Login untuk melihat detail E-Voucher</p>


        <footer>
            <p>Kencana Bakery &copy; {{ now()->year }}</p>
        </footer>

    </div>

</body>

</html>
