<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Katalog Promo</title>
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

        <div class="container-fluid">
            <div class="container">
                <br>
                <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                    <div class="route-back">
                        <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>

                    </div>
                    <h4 style="font-size: 20px;margin-bottom: 0;"><strong>Katalog Promo</strong></h4>
                </div>
                <div class="menu-list">
                    <hr class="hr-menu">
                    @if ($promo_campaign->isNotEmpty())
                        @foreach ($promo_campaign as $promo)
                            <div class="card-reward">
                                <div class="body-reward"
                                    style="width:max-content; box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:10px;margin:10px;">
                                    <div style="display: flex; gap:10px;margin-bottom: 10px;" class="image-content">
                                        <img width="90" height="120" src="{{ url('storage/' . $promo->images) }}"
                                            alt="">
                                        <div class="content-text">
                                            <div style="width: 200px;" class="title-text">
                                                <h5 style="font-size:20px;">{{ $promo->promo_name }}</h5>
                                            </div>
                                            <div class="date">
                                                <span style="font-size: 13px;">Periode promo:</span>
                                                <br>
                                                <small>{{ \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') }}</small>
                                                <span>s.d</span>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-redeem-point">
                                        <a style="background:#bb0239;color:white; border:none;width:100%;"
                                            href="{{ route('promo-detail', $promo->promo_code) }}"
                                            class="btn btn-primary">Lihat promo</a>
                                    </div>
                                </div>
                                {{-- <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black"
                                        href="{{ route('rewards_update', $promo->rewards_code) }}">Edit</a>

                                    @if ($promo->status == 8)
                                        <a class="btn btn-success" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $promo->rewards_code }}">Aktifkan
                                            Kembali
                                        </a>
                                    @else
                                        <a class="btn btn-primary" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $promo->rewards_code }}">Nonaktif
                                        </a>
                                    @endif
                                </div> --}}
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center;margin:0 auto;">Tidak ada Rewards saat ini.</p>
                    @endif

                </div>
            </div>


        </div>

        @if (auth()->guard('customer')->check())
            @php
                $customerAuth = auth()->guard('customer')->user();
                $session = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()
                    ->customer_code;
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

        <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: flex; justify-content: space-between;" class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Keluar</h5>
                        <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                            data-dismiss="modal" aria-label="Close">
                            <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="font-size: 14px; line-height: 1.6;">
                        <p>Apakah anda yakin ingin keluar?</p>
                    </div>

                    <div class="modal-footer">
                        <form id="logout-form" action="{{ route('logout_account') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
