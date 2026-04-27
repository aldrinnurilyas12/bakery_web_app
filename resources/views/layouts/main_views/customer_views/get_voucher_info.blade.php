<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Voucher Info</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif;">

    <div
        style="max-width:420px; margin:40px auto; background:#ffffff; border-radius:12px; 
                box-shadow:0 4px 12px rgba(0,0,0,0.08); overflow:hidden;">

        <!-- Header -->
        <div style="background:#d62828; padding:20px; text-align:center; color:#ffffff;">
            <h2 style="margin:0;">Kencana Bakery</h2>
        </div>

        <!-- Content -->
        <div style="padding:30px; text-align:center;">
            <h3 style="margin-bottom:10px;">Hai, {{ $data['name'] }}</h3>

            <p style="font-size:14px; color:#555; margin-bottom:20px;">
                Selamat! Anda telah mendapatkan <strong>E-Voucher</strong> dari kami 🎉
            </p>

            <!-- Voucher Box -->
            <div
                style="background:#f9fafb; border:1px dashed #d62828; padding:15px; border-radius:8px; margin-bottom:20px;">
                <h4 style="margin:0; color:#d62828;">{{ $data['voucher_name'] }}</h4>
            </div>

            <!-- Button -->
            <a href="{{ route('login_app') }}"
                style="display:inline-block; padding:10px 20px; background:#d62828; color:#ffffff; 
                      text-decoration:none; border-radius:6px; font-size:14px;">
                Lihat Voucher
            </a>

            <p style="font-size:12px; color:#777; margin-top:20px;">
                Silakan login untuk melihat detail voucher Anda.
            </p>

            <p style="font-size:12px; color:#777;">
                Terima kasih telah bertransaksi di outlet kami
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#f4f4f4; padding:15px; text-align:center;">
            <p style="font-size:12px; color:#999; margin:0;">
                © 2025 Kencana Bakery. All rights reserved.
            </p>
        </div>

    </div>

</body>

</html>
