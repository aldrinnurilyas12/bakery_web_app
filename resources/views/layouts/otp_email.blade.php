<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kode OTP</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

    <div
        style="max-width:400px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h4>Hai, {{ $data['email'] }}</h4>
        <p style="font-size:14px; color:#555;">
            Ini kode OTP Anda, jangan bagikan kepada siapa pun.
        </p>

        <div style="margin:20px 0; font-size:28px; letter-spacing:10px; font-weight:bold; color:#333;">
            <h6>{{ $data['otp'] }}</h6>
        </div>

        <p style="font-size:12px; color:#999;">
            Kode ini bersifat rahasia dan berlaku dalam 5 menit.
        </p>

        <footer>
            <p>Kencana Bakery &copy;2025</p>
        </footer>

    </div>

</body>

</html>
