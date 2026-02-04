<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Reward detail</title>
    <link rel="stylesheet" href="{{ asset('assets\front_end\css\homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    @php

        $auth = auth()->guard('customer')->user();
        if ($auth) {
            $customer_point = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->point;
        }
    @endphp
    <div class="main-container">
        <div class="product-image">
            <img src="{{ url('storage/' . $reward->images) }}" alt="Product Image">
        </div>
        <div class="container-fluid">
            <div class="container">
                <div class="product-card">

                    <div class="container-product-info">
                        <div style="display: flex; justify-content:space-between;" class="info-detail">
                            <div style="display: flex; justify-content:space-between; gap:10px;font-size: 14px;"
                                class="group-like">
                                <p style="margin-bottom: 10px;">Point: {{ $reward->point }}</p>
                                &middot;
                                <p>Kuota: {{ $reward->quota ?: 'Habis' }}</p>
                            </div>
                        </div>
                        <h4>{{ $reward->rewards_name }}</h4>
                        <div style="margin-bottom: 1.5em;" class="date">
                            <p style="margin-bottom: 0;">Tanggal berlaku:</p>
                            {{ \Carbon\Carbon::parse($reward->start_date)->format('Y-m-d') }} s.d
                            {{ \Carbon\Carbon::parse($reward->end_date)->format('Y-m-d') }}
                        </div>



                        @if ($auth)
                            <hr class="hr-menu">
                            <div class="redeem">
                                <div style="text-align: center;" class="group-point-customer">
                                    <p style="margin: 0;">Point Anda</p>
                                    <p style="color:#bb0239;margin:0;">
                                        {{ $customer_point }}
                                    </p>

                                    @if ($reward->point > $customer_point)
                                        <small class="text-danger">*Point anda tidak cukup</small>
                                    @endif
                                </div>
                                @if ($reward->point > $customer_point)
                                    <a class="btn btn-secondary" href="">Redeem</a>
                                @else
                                    @if ($reward->quota == null || $reward->quota == 0)
                                        <div class="btn-redeem-point">
                                            <a style="color:white;" class="btn btn-secondary">Kuota habis</a>
                                        </div>
                                    @else
                                        <div class="form-redeem">
                                            <form action="{{ route('redeem-reward') }}" method="POST">
                                                @csrf
                                                <input name="point" type="text" value="{{ $reward->point }}"
                                                    hidden>
                                                <input name="reward_code" type="text"
                                                    value="{{ $reward->rewards_code }}" hidden>
                                                <button type="submit" class="btn-redeem"
                                                    style="background: #bb0239;color:white; border-radius:6px;padding:10px;text-decoration: none;border:none;"
                                                    href="">Redeem</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div style="display: flex; justify-content: center;" class="btn-login-auth">
                                <div class="btn-login">
                                    <a class="btn-redeem"
                                        style="background: #bb0239;color:white; border-radius:6px;padding:10px;text-decoration: none;border:none;"
                                        href="{{ route('login_app') }}">Login untuk klaim reward</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <br>
    </div>





    @include('layouts.main_views.components.bottom_nav')

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
                                <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi dan anda akan
                                    mendapatkan
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

    @if (Session::has('message_success'))
        <script>
            Swal.fire({
                text: "{{ Session::get('message_success') }}",
                icon: 'success',
                timer: 1000,
                confirmButtonText: 'OK'
            });
        </script>
    @elseif(Session::has('failed_message'))
        <script>
            Swal.fire({
                text: "{{ Session::get('failed_message') }}",
                icon: 'error',
                timer: 4000,
                confirmButtonText: 'OK'
            });
        </script>
    @endif

</body>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
