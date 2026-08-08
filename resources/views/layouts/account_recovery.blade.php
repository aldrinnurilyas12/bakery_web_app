<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Account Recovery - Kencana Bakery Services</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

    <div
        style="max-width:400px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

        <h3>Permintaan Pemulihan Akun</h3>

        <br>

        <h4>Hai, {{ $data['email'] }}</h4>
        <p style="font-size:14px; color:#555;">
            Silahkan aktivasi kembali akun anda
        </p>

        <div style="margin:20px 0; font-size:28px; font-weight:bold; color:#333;">
            <div class="form-verification">

                <a href="{{ route('recovery_account_verification', $data['token_link_recovery_account']) }}"
                    style="padding:10px 20px; background:#d62828; color:#ffffff;
                    text-decoration:none; border-radius:6px; font-size:14px;">
                    Pulihkan Akun
                </a>


            </div>
        </div>

        <p style="font-size:12px; color:#999;">
            Link ini hanya aktif 5 menit dan bersifat rahasia, mohon jangan bagikan link ini kepada siapapun.
        </p>

        <footer>
            <p>Kencana Bakery &copy; {{ now()->year }}</p>
        </footer>

    </div>

</body>

</html>
