<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Kebijakan Privasi</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/front_end/assets/logo/kencanabakery_logo2.png') }}">
</head>

<body>
    <div class="main-container">

        <div class="container">
            <div class="container-fluid px-4">
                <br>
                <br>
                <div style="display: flex; justify-content: space-between;align-items: center;" class="group-info">
                    <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                        @if (auth()->guard('customer')->user())
                            <div class="route-back">
                                <a href="{{ route('profile-menu') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        @else
                            <div class="route-back">
                                <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        @endif
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>Kebijakan Privasi </strong></h4>
                    </div>
                </div>
                <hr class="hr-menu">

                <div class="tab-content">
                    {{-- Tab all products --}}

                    <div style="width:100%;" class="container-products">
                        <div style="width:100%;" class="menu-list">
                            <div>

                                <p>
                                    Selamat datang di <strong>Kencana Bakery</strong>.
                                    <br>
                                    Kami menghargai privasi pengguna dan berkomitmen untuk menjaga keamanan
                                    informasi pribadi Anda saat menggunakan layanan website kami.

                                </p>

                                <br>
                                <hr class="hr-menu">
                                <h4>1. Informasi yang Kami Kumpulkan</h4>
                                <hr class="hr-menu">
                                <p>
                                    Kami dapat mengumpulkan informasi seperti:
                                </p>
                                <ul>
                                    <li>Nama lengkap</li>
                                    <li>Email dan nomor telepon</li>
                                    <li>Alamat pengiriman</li>
                                    <li>Informasi akun pengguna</li>
                                    <li>Data transaksi dan riwayat pesanan</li>
                                </ul>

                                <hr class="hr-menu">
                                <h4>2. Penggunaan Informasi</h4>
                                <hr class="hr-menu">
                                <p>
                                    Informasi yang dikumpulkan digunakan untuk:
                                </p>

                                <ul>
                                    <li>Mengelola akun pengguna</li>
                                    <li>Memproses pesanan</li>
                                    <li>Memberikan informasi produk dan promo</li>
                                    <li>Meningkatkan kualitas layanan website</li>
                                </ul>

                                <hr class="hr-menu">
                                <h4>3. Informasi Produk dan Promo</h4>
                                <hr class="hr-menu">
                                <p>
                                    Kami menyediakan informasi produk seperti nama, harga, deskripsi,
                                    stok, dan promo yang berlaku. Informasi dapat berubah sewaktu-waktu
                                    mengikuti kondisi layanan kami.
                                </p>

                                <hr class="hr-menu">
                                <h4>4. Keamanan Data</h4>
                                <hr class="hr-menu">
                                <p>
                                    Kami menerapkan langkah keamanan yang sesuai untuk melindungi data
                                    pengguna dari akses yang tidak sah, penyalahgunaan, atau kehilangan data.
                                </p>

                                <hr class="hr-menu">
                                <h4>5. Cookies</h4>
                                <hr class="hr-menu">
                                <p>
                                    Website kami menggunakan cookies untuk membantu menyediakan pengalaman
                                    penggunaan yang lebih baik, menyimpan preferensi pengguna, dan meningkatkan
                                    performa website.
                                </p>

                                <p>
                                    Pengguna dapat mengatur atau menonaktifkan cookies melalui pengaturan
                                    browser masing-masing.
                                </p>

                                <hr class="hr-menu">
                                <h4>6. Berbagi Data</h4>
                                <hr class="hr-menu">
                                <p>
                                    Kami tidak menjual data pribadi pengguna. Data hanya dapat dibagikan
                                    kepada pihak pendukung layanan seperti pembayaran, pengiriman, atau
                                    layanan teknologi yang diperlukan.
                                </p>

                                <hr class="hr-menu">
                                <h4>7. Hak Pengguna</h4>
                                <hr class="hr-menu">
                                <p>
                                    Pengguna dapat meminta perubahan, pembaruan, atau penghapusan data
                                    pribadi dengan menghubungi kami melalui kontak resmi.
                                </p>

                                <hr class="hr-menu">
                                <h4>8. Perubahan Kebijakan</h4>
                                <hr class="hr-menu">
                                <p>
                                    Kami dapat memperbarui Privacy Policy ini sewaktu-waktu sesuai dengan
                                    perubahan layanan dan kebijakan yang berlaku.
                                </p>

                                <hr class="hr-menu">
                                <h4>Kontak</h4>
                                <hr class="hr-menu">

                                <p>
                                    Jika memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi:
                                </p>

                                <p>
                                    Email: {{ config('mail.from.address') }}
                                </p>

                            </div>

                        </div>
                    </div>

                </div>



            </div>
            <br>
            @if (auth()->guard('customer')->check())
                @php
                    $customerAuth = auth()->guard('customer')->user();
                    $session = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()
                        ->customer_code;
                    $customer = DB::table('v_customers')->where('customer_code', $customerAuth->customer_code)->first();
                @endphp

                @if ($customer)
                    <div class="modal fade" id="openqr" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div style="display: flex; justify-content: space-between;" class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">QR CODE</h5>
                                    <button style="width: 10px; height:10px;justify-items: center;background:none;"
                                        type="button" data-dismiss="modal" aria-label="Close">
                                        <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body"
                                    style="height:max-content; overflow-y: auto; font-size: 14px; line-height: 1.6;display: flex; justify-content: center; text-align: center;">
                                    <div style="height: max-content;padding:5px;display:block; font-family: Cambria;"
                                        class="show-qrcode">
                                        <img width="300" height="300"
                                            src="{{ url('storage/' . $customer->qr_code) }}" alt="">
                                        <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi
                                            dan anda
                                            akan mendapatkan
                                            Point.
                                        </p>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button style="background: #bb0239;border: none;" data-dismiss="modal"
                                        class="btn btn-primary" aria-label="Close">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @include('layouts.main_views.components.bottom_nav')

        </div>



</body>


<style>
    .route-back {
        width: 30px;
        height: 30px;
        background: #bb0239;
        border-radius: 50%;

        justify-content: center;
        align-items: center;
    }

    .route-back a {
        color: #fff;
        font-size: 14px;
        text-decoration: none;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
</style>



<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
