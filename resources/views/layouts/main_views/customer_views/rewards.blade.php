<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Histori Rewards</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body>
    <div class="main-container">

        <div class="container">
            <div class="container-fluid">
                <br>
                <br>
                <div style="display: flex; gap:10px; justify-content: space-between;" class="title-content-head">
                    <div style="display: flex; gap:10px;" class="group-back">
                        <a style="color:black;" href="{{ route('profile-menu') }}">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <h4 style="font-size: 20px;"><strong>Histori Rewards</strong></h4>
                    </div>

                    <div style="display: flex; " class="group-info-link">
                        <a style="font-size: 13px; text-decoration: underline;color:#bb0239;" href="#"
                            data-toggle="modal" data-target="#sk">
                            Syarat & Ketentuan</a>
                    </div>
                </div>

                <hr class="hr-menu">
                <div class="grid" role="tablist">
                    <!-- TAB SEMUA -->
                    <a class="card-category active" data-bs-toggle="tab" href="#tab-claimed" role="tab">
                        <p class="product-category-show">Klaim</p>
                    </a>
                    <a class="card-category" data-bs-toggle="tab" href="#tab-unclaimed" role="tab">
                        <p class="product-category-show">Belum Klaim</p>
                    </a>
                </div>
                <hr class="hr-menu">
                <div class="tab-content">
                    {{-- Tab claimed --}}
                    <div class="tab-pane fade show active" id="tab-claimed" role="tabpanel">
                        <div class="container-products">
                            <div class="menu-list">
                                <div class="menu-list">
                                    @if ($rewards->isNotEmpty())
                                        @foreach ($rewards as $reward)
                                            <div style="display: flex; gap:20px;" class="image-content">
                                                <img width="90" height="90"
                                                    src="{{ url('storage/' . $reward->images) }}" alt="">

                                                <div style="display: block;" class="grand-total">
                                                    <div class="group-menu-date">
                                                        <p style="font-size: 1.25rem;color:rgb(0, 0, 0);"
                                                            class="text-date">
                                                            {{ $reward->rewards_name }}
                                                        </p>
                                                    </div>
                                                    <p style="font-size: 13px;">Kode :
                                                        {{ $reward->redeem_code }}</p>
                                                    <p style="font-size: 13px;margin:0;">Tgl Redeem :
                                                        {{ $reward->redeem_date }}</p>
                                                    @if ($reward->claimed_at)
                                                        <p style="font-size: 13px;margin:0;">Tgl Klaim :
                                                            {{ $reward->claimed_at }}</p>
                                                    @endif
                                                    <p>Status:
                                                        @if ($reward->status_name == 'Claimed')
                                                            <span class="text-success">
                                                                sudah klaim
                                                            </span>
                                                        @else
                                                            <span class="text-secondary">
                                                                belum klaim
                                                            </span>
                                                        @endif
                                                    </p>
                                                    <div style="font-size: 13px;" class="group-date">
                                                        <p>Jadwal Pengambilan</p>
                                                        <div style="display:flex; gap:10px;" class="flex-location">
                                                            <span>{{ \Carbon\Carbon::parse($reward->pickup_schedule)->format('Y-m-d') }}</span>
                                                            |
                                                            <span>{{ $reward->store_name }}</span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <hr class="hr-menu">
                                        @endforeach
                                    @else
                                        <p style="text-align: center;margin:0 auto;">Tidak ada rewards yang anda klaim.
                                        </p>
                                    @endif

                                </div>


                            </div>



                        </div>
                    </div>

                    {{-- tab unclaimed --}}

                    <div class="tab-pane fade" id="tab-unclaimed" role="tabpanel">
                        <div class="container-products">
                            <div class="menu-list">
                                <div class="menu-list">
                                    @if ($rewards->isNotEmpty())
                                        @foreach ($unclaimed_rewards as $reward)
                                            <div style="display: flex; gap:20px;" class="image-content">
                                                <img width="90" height="90"
                                                    src="{{ url('storage/' . $reward->images) }}" alt="">

                                                <div style="display: block;" class="grand-total">
                                                    <div class="group-menu-date">
                                                        <p style="font-size: 1.25rem;color:rgb(0, 0, 0);"
                                                            class="text-date">
                                                            {{ $reward->rewards_name }}
                                                        </p>
                                                    </div>
                                                    <p style="font-size: 13px;">Kode :
                                                        {{ $reward->redeem_code }}</p>
                                                    <p style="font-size: 13px;margin:0;">Tgl Redeem :
                                                        {{ $reward->redeem_date }}</p>
                                                    @if ($reward->claimed_at)
                                                        <p style="font-size: 13px;margin:0;">Tgl Klaim :
                                                            {{ $reward->claimed_at }}</p>
                                                    @endif
                                                    <p>Status:
                                                        @if ($reward->status_name == 'Claimed')
                                                            <span class="text-success">
                                                                sudah klaim
                                                            </span>
                                                        @else
                                                            <span class="text-secondary">
                                                                belum klaim
                                                            </span>
                                                        @endif
                                                    </p>
                                                    <div style="font-size: 13px;" class="group-date">
                                                        <p>Jadwal Pengambilan</p>
                                                        <div style="display:flex; gap:10px;" class="flex-location">
                                                            <span>{{ \Carbon\Carbon::parse($reward->pickup_schedule)->format('Y-m-d') }}</span>
                                                            |
                                                            <span>{{ $reward->store_name }}</span>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <hr class="hr-menu">
                                        @endforeach
                                    @else
                                        <p style="text-align: center;margin:0 auto;">Tidak ada rewards yang anda klaim.
                                        </p>
                                    @endif

                                </div>


                            </div>



                        </div>
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
                                        <p class="info-point">*Tunjukan QR Code ini kepada kasir saat
                                            transaksi dan anda
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

            <div class="modal fade" id="sk" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div style="display: flex; justify-content: space-between;" class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Syarat & Ketentuan</h5>
                            <button style="width: 10px; height:10px;justify-items: center;background:none;"
                                type="button" data-dismiss="modal" aria-label="Close">
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
                                <li>Penukaran Reward dapat diklaim jika point anda memenuhi point Reward
                                </li>
                                <li>Pengambilan/Penukaran Reward hanya bisa dilakukan di Outlet Resmi
                                    Kencana Bakery</li>
                                <li>Tidak dapat ditukarkan kedalam bentuk uang tunai dan semacamnya</li>
                                <li>Status Unclaimed artinya Reward belum diambil oleh pelanggan dan status
                                    Claimed artinya
                                    Reward sudah diambil/klaim</li>

                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" style="background: #bb0239;border: none;"
                                data-dismiss="modal" aria-label="Close">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>


            @include('layouts.main_views.components.bottom_nav')

        </div>
</body>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
