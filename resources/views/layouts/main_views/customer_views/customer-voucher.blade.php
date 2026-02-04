<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Voucher</title>
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
                        <a style="color:black;" href="{{ route('profile-menu') }}">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <h4 style="font-size: 20px;"><strong>E-Voucher </strong></h4>
                    </div>

                    <div style="display: flex; " class="group-info-link">
                        <a style="font-size: 13px; text-decoration: underline;color:#bb0239;" href="#"
                            data-toggle="modal" data-target="#sk">
                            Syarat & Ketentuan</a>
                    </div>
                </div>
                <hr class="hr-menu">

                <div class="menu-list">
                    @if ($vouchers->isNotEmpty())
                        @foreach ($vouchers as $voucher)
                            <div class="card-reward">
                                <div class="body-reward"
                                    style=" box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:20px;margin:10px;">
                                    <div style="display: flex; gap:10px;" class="image-content">
                                        <img width="90" height="90"
                                            src="{{ url('storage/' . $voucher->qr_code) }}" alt="">
                                        <div class="content-text">
                                            <div style="width: 200px;" class="title-text">
                                                <h5 style="font-size:15px;">{{ $voucher->voucher_name }}</h5>
                                                <p>
                                                <h5 style="font-size:15px;color:#bb0239;">{{ $voucher->voucher_code }}
                                                </h5>
                                                </p>
                                            </div>
                                            <p
                                                style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px;">
                                                Discount:
                                                <span style="color:black;">{{ $voucher->discount . '%' }} </span> &nbsp;
                                                <span>Kategori:
                                                    <span
                                                        style="color:black;">{{ $voucher->voucher_type }}</span></span>
                                                @if ($voucher->voucher_used)
                                                    <span>Status : <span class="text-success">Terpakai </span> </span>
                                                @else
                                                    <span>Status : Belum Terpakai </span>
                                                @endif
                                            </p>
                                            <div style="font-size: 14px; font-weight: 500;margin-bottom: 20px;"
                                                class="date">
                                                <small>{{ \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d') }}</small>
                                                <span>s.d</span>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d') }}</small>
                                            </div>
                                            {{-- @if (auth()->guard('customer')->user())
                                                <div class="btn-redeem-point">
                                                    <a style="color: white; background:#bb0239; padding:8px; border-radius:5px;text-decoration: none;font-size:12px;"
                                                        href="">Redeem Point</a>
                                                </div>
                                            @else
                                                <div class="btn-redeem-point">
                                                    <a style="color: white; background:#bb0239; padding:8px; border-radius:5px;text-decoration: none;font-size:12px;"
                                                        href="">Redeem Point</a>
                                                </div>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black"
                                        href="{{ route('rewards_update', $voucher->rewards_code) }}">Edit</a>

                                    @if ($voucher->status == 8)
                                        <a class="btn btn-success" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $voucher->rewards_code }}">Aktifkan
                                            Kembali
                                        </a>
                                    @else
                                        <a class="btn btn-primary" href="#" data-toggle="modal"
                                            data-target="#deleteModalRewards{{ $voucher->rewards_code }}">Nonaktif
                                        </a>
                                    @endif
                                </div> --}}
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center;margin:0 auto;">Tidak ada E-Voucher saat ini.</p>
                    @endif

                </div>
            </div>

        </div>
        <br>
        <div class="modal fade" id="sk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: flex; justify-content: space-between;" class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Syarat & Ketentuan E-Voucher</h5>
                        <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                            data-dismiss="modal" aria-label="Close">
                            <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body"
                        style="max-height: 400px; overflow-y: auto; font-size: 14px; line-height: 1.6;">
                        <h6><strong>Penggunaan E-Voucher</strong></h6>
                        <p>
                            Penggunaan E-Voucher dapat memenuhi ketentuan dan persyaratan sebagai berikut:
                        </p>
                        <ul>
                            <li>Penggunaan E-Voucher ditunjukan ke kasir saat transaksi</li>
                            <li>E-Voucher hanya bisa digunakan 1x saja</li>
                            <li>E-Voucher memiliki masa periode waktu yang berlaku</li>
                            <li>Tidak dapat ditukarkan kedalam bentuk uang tunai dan semacamnya</li>
                            <li>Voucher dapat berupa nominal belanja dan diskon potongan pada saat transaksi di Outlet
                                resmi</li>

                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" style="background: #bb0239;border: none;" data-dismiss="modal"
                            aria-label="Close">Tutup</button>
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
                                    <img width="300" height="300"
                                        src="{{ url('storage/' . $customer->qr_code) }}" alt="">
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



</body>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
