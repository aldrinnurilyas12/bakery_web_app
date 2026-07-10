<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Katalog Rewards</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/front_end/assets/logo/kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">

        <div class="container-fluid">
            <div class="container">
                <br>
                <div style="display: flex; gap:10px;justify-content: space-between;align-items: center;"
                    class="title-content-head">
                    <div style="display: flex; gap:10px;align-items: center;" class="group-back">
                        <div class="route-back">
                            <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>Katalog Rewards</strong></h4>
                    </div>

                    <div style="display: flex; " class="group-info-link">
                        <a style="font-size: 13px; text-decoration: underline;color:#bb0239;" href="#"
                            data-toggle="modal" data-target="#sk">
                            Syarat & Ketentuan</a>
                    </div>
                </div>
                <div class="menu-list">
                    <hr class="hr-menu">
                    @if ($rewards->isNotEmpty())
                        @foreach ($rewards as $reward)
                            <div class="card-reward">
                                <div class="body-reward"
                                    style="width:max-content; box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:10px;margin:10px;">
                                    <div style="display: flex; gap:10px;" class="image-content">
                                        <img width="90" height="120" src="{{ url('storage/' . $reward->images) }}"
                                            alt="">
                                        <div class="content-text">
                                            <div style="width: 200px;" class="title-text">
                                                <h5 style="font-size:20px;">{{ $reward->rewards_name }}</h5>
                                            </div>
                                            <p
                                                style="font-size: 1rem;color:rgb(0, 0, 0); font-weight: normal;margin-bottom:5px;">
                                                Point:
                                                {{ $reward->point }} &nbsp; <span>Kuota:
                                                    {{ $reward->total_stock ?: 'habis' }}</span>
                                            </p>
                                            <div class="date">
                                                <small>{{ \Carbon\Carbon::parse($reward->start_date)->format('Y-m-d') }}</small>
                                                <span>s.d</span>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($reward->end_date)->format('Y-m-d') }}</small>
                                            </div>

                                            @if ($reward->total_stock == null || $reward->total_stock == 0)
                                                <div class="btn-redeem-point">
                                                    <a style="color:white;" class="btn btn-secondary">Kuota habis</a>
                                                </div>
                                            @else
                                                <div class="btn-redeem-point">
                                                    <a style="background:#bb0239;color:white; border:none;width:100%;"
                                                        class="btn btn-primary"
                                                        href="{{ route('reward-detail', $reward->rewards_code) }}">Redeem
                                                        &nbsp; <i class="fa-solid fa-chevron-right"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black"
                                        href="{{ route('rewards_update', $reward->rewards_code) }}">Edit</a>

                                    @if ($reward->status == 8)
                                        <a class="btn btn-success" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $reward->rewards_code }}">Aktifkan
                                            Kembali
                                        </a>
                                    @else
                                        <a class="btn btn-primary" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $reward->rewards_code }}">Nonaktif
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

        <div class="modal fade" id="sk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: flex; justify-content: space-between;" class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Syarat & Ketentuan</h5>
                        <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                            data-dismiss="modal" aria-label="Close">
                            <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body"
                        style="max-height: 400px; overflow-y: auto; font-size: 14px; line-height: 1.6;">
                        <h6><strong>Penukaran Rewards</strong></h6>
                        <p>
                            Penukaran Rewards dapat memenuhi ketentuan dan persyaratan sebagai berikut:
                        </p>
                        <ul>
                            <li>Penukaran Reward dapat diklaim jika point anda memenuhi point Reward</li>
                            <li>Pengambilan/Penukaran Reward hanya bisa dilakukan di Outlet Resmi Kencana Bakery</li>
                            <li>Tidak dapat ditukarkan kedalam bentuk uang tunai dan semacamnya</li>

                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" style="background: #bb0239;border: none;"
                            data-dismiss="modal" aria-label="Close">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
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
    @elseif (Session::has('failed_message'))
        <script>
            Swal.fire({
                text: "{{ Session::get('failed_message') }}",
                icon: 'error',
                timer: 2000,
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
