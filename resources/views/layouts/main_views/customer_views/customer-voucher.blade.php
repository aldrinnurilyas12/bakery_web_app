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
                        <div class="route-back">
                            <a href="{{ route('profile-menu') }}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>E-Voucher </strong></h4>
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
                    <a class="card-category active" data-bs-toggle="tab" href="#tab-used" role="tab">
                        <p class="product-category-show">Terpakai</p>
                    </a>
                    <a class="card-category" data-bs-toggle="tab" href="#tab-not-used" role="tab">
                        <p class="product-category-show">Belum Terpakai</p>
                    </a>
                </div>

                <hr class="hr-menu">

                <div class="tab-content">
                    {{-- Tab all products --}}
                    <div class="tab-pane fade show active" id="tab-used" role="tabpanel">
                        <div style="width:100%;" class="container-products">
                            <div style="width:100%;" class="menu-list">
                                @if ($vouchers_used->isNotEmpty())
                                    @foreach ($vouchers_used as $voucher)
                                        <div class="card-reward">
                                            <div class="body-reward"
                                                style=" box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:20px;margin:10px;">
                                                <div style="flex-wrap:wrap; gap:10px;" class="image-content">
                                                    <div style="display:flex; flex-wrap: wrap; justify-content: space-between;"
                                                        class="image-display">
                                                        <img width="90" height="90"
                                                            src="{{ url('storage/' . $voucher->voucher_path) }}"
                                                            alt="">
                                                        <span>
                                                            @if ($voucher->voucher_used == 'Y')
                                                                <span class="badge badge-success">Terpakai </span>
                                                            @else
                                                                <span class="badge badge-secondary">Belum Terpakai
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="content-text">
                                                        <div style="width: 200px;" class="title-text">
                                                            <h5 style="font-size:15px;">{{ $voucher->voucher_name }}
                                                            </h5>
                                                            <p>
                                                            <h5 style="font-size:15px;color:#bb0239;">
                                                                {{ $voucher->customer_voucher_code }}
                                                            </h5>
                                                            </p>
                                                        </div>
                                                        <p
                                                            style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px;">
                                                            Discount:
                                                            <span style="color:black;">{{ $voucher->discount . '%' }}
                                                            </span> &nbsp;
                                                            <span>Kategori:
                                                                <span
                                                                    style="color:black;">{{ $voucher->voucher_type }}</span></span>
                                                        </p>
                                                        <div style="font-size: 14px; font-weight: 500;margin-bottom: 20px;"
                                                            class="date">
                                                            <small>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d M Y') }}</small>
                                                            <span>s.d</span>
                                                            <small>
                                                                {{ \Carbon\Carbon::parse($voucher->end_date)->format('d M Y') }}</small>
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

                    <div class="tab-pane fade" id="tab-not-used" role="tabpanel">
                        <div style="width:100%;" class="container-products">
                            <div style="width:100%;" class="menu-list">
                                @if ($vouchers->isNotEmpty())
                                    @foreach ($vouchers as $voucher)
                                        <div class="card-reward">
                                            <div class="body-reward"
                                                style=" box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:20px;margin:10px;">
                                                <div style="flex-wrap:wrap; gap:10px;" class="image-content">
                                                    <div style="display:flex; flex-wrap: wrap; justify-content: space-between;"
                                                        class="image-display">

                                                        @if ($voucher->end_date <= now())
                                                        @else
                                                            @if ($voucher->voucher_path)
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#showQrCode{{ $voucher->customer_voucher_code }}">
                                                                    <img width="90" height="90"
                                                                        src="{{ url('storage/' . $voucher->voucher_path) }}"
                                                                        alt="">
                                                                </a>
                                                            @endif
                                                        @endif
                                                        <div style="display: block;" class="info-voucher-date">
                                                            <span class="info-voucher">
                                                                @if ($voucher->end_date <= now())
                                                                    <span style="margin-bottom:10px;"
                                                                        class="badge badge-danger">Expired</span>
                                                                @else
                                                                    @if ($voucher->voucher_used == 'Y')
                                                                        <span class="badge badge-success">Terpakai
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Belum
                                                                            Terpakai
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="content-text">
                                                        <div style="width: 200px;" class="title-text">
                                                            <h5 style="font-size:15px;">{{ $voucher->voucher_name }}
                                                            </h5>

                                                            <h5 style="font-size:15px;color:#bb0239;">
                                                                {{ $voucher->customer_voucher_code }}
                                                            </h5>

                                                        </div>

                                                        <div style="display:flex;gap:10px;" class="flex-content-wrap">

                                                            @if ($voucher->discount)
                                                                @if ($voucher->discount == 100)
                                                                @else
                                                                    <span
                                                                        style="font-size: 13px;color:gray; font-weight: normal;">
                                                                        Discount:
                                                                        <span
                                                                            style="color:black;">{{ $voucher->discount . '%' }}
                                                                        </span>
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span
                                                                    style="font-size: 13px;color:gray; font-weight: normal;">
                                                                    Nominal:
                                                                    <span
                                                                        style="color:black;">{{ 'Rp.' . number_format($voucher->nominal) }}
                                                                    </span>
                                                                </span>
                                                            @endif

                                                            <div class="date">
                                                                @if ($voucher->voucher_type == 'birth_day')
                                                                    <span
                                                                        style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px; display: flex;gap:5px;">Tgl
                                                                        Expired:
                                                                        <span
                                                                            style="color:black;">{{ \Carbon\Carbon::parse($voucher->expired_date)->format('d M Y') }}</span>
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px; display: flex;gap:5px;">Tgl
                                                                        Berlaku:
                                                                        <span
                                                                            style="color:black;">{{ \Carbon\Carbon::parse($voucher->start_date)->format('d M Y') }}</span>
                                                                        <span>s.d</span>
                                                                        <span style="color:black;">
                                                                            {{ \Carbon\Carbon::parse($voucher->end_date)->format('d M Y') }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p style="text-align: center;margin:0 auto;">Tidak ada E-Voucher saat ini.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <br>
            <div class="modal fade" id="sk" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div style="display: flex; justify-content: space-between;" class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Syarat & Ketentuan E-Voucher</h5>
                            <button style="width: 10px; height:10px;justify-items: center;background:none;"
                                type="button" data-dismiss="modal" aria-label="Close">
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
                                <li>Voucher dapat berupa nominal belanja dan diskon potongan pada saat transaksi
                                    di Outlet
                                    resmi</li>
                                <li>Voucher yang sudah melewati masa Expired maka tidak dapat digunakan</li>

                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" style="background: #bb0239;border: none;"
                                data-dismiss="modal" aria-label="Close">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>


            @foreach ($vouchers as $voucher)
                <div wire:ignore class="modal fade" id="showQrCode{{ $voucher->customer_voucher_code }}"
                    tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel{{ $voucher->customer_voucher_code }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">

                            <div style="justify-content: center; display:flex;" class="modal-body">
                                <img width="250" height="250"
                                    src="{{ url('storage/' . $voucher->voucher_path) }}" alt="">
                            </div>
                            <div class="modal-footer">
                                <button id="btn-general" type="button" data-dismiss="modal"
                                    class="btn-general"><span>Tutup</span>
                                    <span class="spinner"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


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
