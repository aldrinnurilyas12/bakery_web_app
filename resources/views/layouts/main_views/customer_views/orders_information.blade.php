<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Informasi Pemesanan</title>
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
            <div class="container-fluid">
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
                                <a href="{{ route('info-menu') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        @endif
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>Informasi Pemesanan </strong></h4>
                    </div>
                </div>
                <hr class="hr-menu">

                <div class="tab-content">
                    {{-- Tab all products --}}

                    <div style="width:100%;" class="container-products">
                        <div style="width:100%;" class="menu-list">


                            <div class="alert alert-light border rounded-3 mb-4">
                                <strong>Terima kasih telah memilih bakery kami!</strong>
                                <p class="mb-0 mt-2 text-muted">
                                    Untuk memastikan proses pemesanan dan pengambilan
                                    berjalan dengan nyaman, mohon perhatikan kebijakan
                                    berikut.
                                </p>
                            </div>

                            {{-- 1. Pemesanan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>1. Pemesanan</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Pemesanan dapat dilakukan melalui channel
                                        online yang telah disediakan.
                                    </li>
                                    <li>
                                        Pelanggan wajib mencantumkan nama, nomor
                                        kontak, produk yang dipesan, jumlah pesanan,
                                        serta tanggal dan waktu pickup.
                                    </li>
                                    <li>
                                        Pesanan dianggap
                                        <strong>terkonfirmasi setelah pembayaran
                                            diterima</strong> atau setelah mendapatkan
                                        konfirmasi dari pihak bakery.
                                    </li>
                                    <li>
                                        Ketersediaan produk mengikuti stok dan
                                        kapasitas produksi pada hari pemesanan.
                                    </li>
                                </ul>
                            </section>

                            {{-- 2. Waktu Pemesanan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>2. Waktu Pemesanan</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Untuk produk ready stock, pesanan dapat
                                        dilakukan sesuai ketersediaan pada hari
                                        tersebut.
                                    </li>
                                    <li>
                                        Untuk produk pre-order atau pesanan dalam
                                        jumlah banyak, pelanggan disarankan melakukan
                                        pemesanan minimal <strong>H-1</strong>.
                                    </li>
                                    <li>
                                        Pesanan khusus atau custom membutuhkan waktu
                                        persiapan tambahan dan akan diinformasikan
                                        oleh tim bakery.
                                    </li>
                                </ul>
                            </section>

                            {{-- 3. Pickup --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>3. Pickup / Pengambilan Pesanan</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Pesanan dapat diambil sesuai
                                        <strong>tanggal dan waktu pickup</strong>
                                        yang telah dipilih.
                                    </li>
                                    <li>
                                        Mohon datang sesuai jadwal agar produk tetap
                                        dalam kondisi terbaik.
                                    </li>
                                    <li>
                                        Pelanggan dapat menunjukkan bukti order atau
                                        order confirmation saat melakukan pengambilan.
                                    </li>
                                    <li>
                                        Apabila datang lebih awal, pesanan mungkin
                                        masih dalam proses persiapan.
                                    </li>
                                </ul>
                            </section>

                            {{-- 4. Keterlambatan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>4. Keterlambatan Pickup</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Apabila pelanggan terlambat mengambil pesanan,
                                        mohon segera menghubungi bakery.
                                    </li>
                                    <li>
                                        Keterlambatan pickup dapat memengaruhi kualitas
                                        produk, terutama produk yang membutuhkan
                                        penyimpanan pada suhu tertentu.
                                    </li>
                                    <li>
                                        Bakery tidak bertanggung jawab atas perubahan
                                        kualitas produk akibat keterlambatan
                                        pengambilan oleh pelanggan.
                                    </li>
                                </ul>
                            </section>

                            {{-- 5. Perubahan & Pembatalan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>5. Perubahan atau Pembatalan Pesanan</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Perubahan pesanan dapat dilakukan selama
                                        pesanan belum masuk tahap produksi.
                                    </li>
                                    <li>
                                        Pembatalan dan refund mengikuti jenis produk
                                        serta status produksi pesanan.
                                    </li>
                                    <li>
                                        Pesanan yang sudah masuk proses produksi
                                        umumnya <strong>tidak dapat dibatalkan atau
                                            diubah</strong>.
                                    </li>
                                </ul>
                            </section>

                            {{-- 6. Pemeriksaan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>6. Pemeriksaan Pesanan</h4>
                                <hr class="hr-menu">
                                <ul>
                                    <li>
                                        Pelanggan diharapkan memeriksa pesanan sebelum
                                        meninggalkan area pickup.
                                    </li>
                                    <li>
                                        Apabila terdapat kekurangan atau ketidaksesuaian
                                        pesanan, harap segera menginformasikannya kepada
                                        staff bakery.
                                    </li>
                                    <li>
                                        Keluhan akan ditangani berdasarkan bukti order
                                        dan kondisi produk saat diterima.
                                    </li>
                                </ul>
                            </section>

                            {{-- 7. Catatan --}}
                            <section class="mb-4">
                                <hr class="hr-menu">
                                <h4>7. Catatan Penting</h4>
                                <hr class="hr-menu">
                                <p>
                                    Dengan melakukan pemesanan online, pelanggan
                                    dianggap telah membaca dan menyetujui kebijakan
                                    pemesanan serta pickup yang berlaku.
                                </p>
                                <br>
                                <div class="p-4 bg-light rounded-4 text-center">
                                    <div class="fw-semibold">
                                        Order Online
                                        <span class="mx-2">→</span>
                                        Konfirmasi
                                        <span class="mx-2">→</span>
                                        Pembayaran
                                        <span class="mx-2">→</span>
                                        Siap Diambil
                                        <span class="mx-2">→</span>
                                        Pickup
                                    </div>
                                </div>
                            </section>

                            <div class="text-center pt-3 border-top">
                                <p class="text-muted mb-0">
                                    Terima kasih atas pengertian dan kerja samanya.
                                    <br>
                                    Kami berharap pesanan Anda dapat diterima dalam
                                    kondisi terbaik! 🥐🍰
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
