<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Notifikasi</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">

        <div class="container">
            <div class="container-fluid">
                <br>
                <br>
                <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                    <div class="route-back">
                        <a href="{{ route('profile-menu') }}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                    <h4 style="font-size: 20px;margin-bottom:0;"><strong>Notifikasi</strong></h4>
                </div>

                <div class="menu-list">


                    @if ($notifications)
                        @foreach ($notifications as $notif)
                            @if ($notif->type == 'transaction')
                                <hr class="hr-menu">
                                <div class="notif">
                                    <div style="display: flex; gap:10px;align-items: center;margin-bottom: 10px;"
                                        class="iconsuccess">
                                        <i style="padding: 10px; background:#bb0239; color: white; border-radius: 50%;"
                                            class="fa fa-bell"></i>
                                        <p>Transaksi berhasil!</p>
                                    </div>

                                    <div style="display: flex; justify-content: space-between;align-items: center;"
                                        class="group-menu-date">
                                        <p class="text-invoice"></i> <a
                                                href="{{ route('invoice', $notif->transaction_code) }}">
                                                {{ $notif->transaction_code }}
                                                &nbsp; <i class="fas fa-external-link-alt"></i></a></p>
                                        <p>{{ $notif->created_at }}</p>

                                    </div>
                                </div>
                            @else
                                <hr class="hr-menu">
                                <div class="notif">
                                    <div style="display: flex; gap:10px;align-items: center;margin-bottom: 10px;"
                                        class="iconsuccess">
                                        <i style="padding: 10px; background:#bb0239; color: white; border-radius: 50%;"
                                            class="fa fa-gift"></i>
                                        <p>Klaim Reward Berhasil!</p>
                                    </div>

                                    <div style="display: flex; justify-content: space-between;align-items: center;"
                                        class="group-menu-date">
                                        <p class="text-invoice"></i> <a href="{{ route('rewards-history') }}">
                                                {{ $notif->transaction_code }}
                                                &nbsp; <i class="fas fa-external-link-alt"></i></a></p>
                                        <p>{{ $notif->created_at }}</p>

                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p style="text-align: center;margin:0 auto;">Tidak ada notifikasi.</p>
                    @endif

                    @if ($user_register)
                        <hr class="hr-menu">
                        <div class="notif">
                            <div style="display: flex; gap:10px;align-items: center;margin-bottom: 10px;"
                                class="iconsuccess">
                                <i style="padding: 10px; background:#bb0239; color: white; border-radius: 50%;"
                                    class="fa fa-warning"></i>
                                <p>Selamat datang di Kencana Bakery!</p>
                            </div>
                        </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (auth()->guard('customer')->check())
        @php
            $customerAuth = auth()->guard('customer')->user();
            $session = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
            $customer = DB::table('v_customers')->where('customer_code', $customerAuth->customer_code)->first();
        @endphp

        @if ($customer)
            <div class="modal fade" id="openqr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                                <img width="300" height="300" src="{{ url('storage/' . $customer->qr_code) }}"
                                    alt="">
                                <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi dan anda
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

    @if (Session::has('message_success'))
        <script>
            Swal.fire({
                title: 'Berhasil',
                text: "{{ Session::get('message_success') }}",
                icon: 'success',
                timer: 2000,
                confirmButtonText: 'OK'
            });
        </script>
    @elseif(Session::has('failed_message'))
        <script>
            Swal.fire({
                title: 'Gagal',
                text: "{{ Session::get('failed_message') }}",
                icon: 'error',
                timer: 4000,
                confirmButtonText: 'OK'
            });
        </script>
    @endif

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
