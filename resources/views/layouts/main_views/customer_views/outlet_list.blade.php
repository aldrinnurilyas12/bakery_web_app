<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Outlet Kami</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/front_end/assets/logo/kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>Outlet Kami </strong></h4>
                    </div>
                </div>
                <hr class="hr-menu">

                <div class="tab-content">
                    {{-- Tab all products --}}

                    <div style="width:100%;" class="container-products">
                        <div style="width:100%;" class="menu-list">
                            @if ($store->isNotEmpty())
                                @foreach ($store as $outlet)
                                    <div class="card-reward">
                                        <div class="body-reward"
                                            style=" box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;padding:20px; border-radius:20px;margin:10px;">
                                            <div style="flex-wrap:wrap; gap:10px;" class="image-content">
                                                <div style="display:flex; flex-wrap: wrap; justify-content: space-between;"
                                                    class="image-display">
                                                    {{-- <img width="90" height="90"
                                                        src="{{ url('storage/' . $outlet->voucher_path) }}"
                                                        alt=""> --}}
                                                </div>
                                                <div class="content-text">
                                                    <div style="width: 200px;" class="title-text">
                                                        <h5 style="font-size:15px;font-weight: bold;">
                                                            {{ $outlet->store_name }}
                                                        </h5>
                                                    </div>
                                                    <p>
                                                        <span
                                                            style="color:black;font-size: 14px;">{{ $outlet->location }}
                                                        </span>
                                                    </p>
                                                    <br>
                                                    <div class="btn-maps">
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#showMap{{ $outlet->id }}"
                                                            class="btn-general">Lihat lokasi</a>
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
            <br>


            @foreach ($store as $outlet)
                <div wire:ignore class="modal fade" id="showMap{{ $outlet->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel{{ $outlet->id }}" aria-hidden="true">

                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Lokasi {{ $outlet->store_name }}
                                </h5>

                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>


                            <div class="modal-body">

                                <div id="map{{ $outlet->id }}" style="height:400px; width:100%;">
                                </div>

                                <div class="mt-3">
                                    <strong>Alamat:</strong>
                                    <p>{{ $outlet->location }}</p>
                                </div>

                            </div>


                            <div class="modal-footer">

                                <button id="btn-general" type="button" data-dismiss="modal" class="btn-general">

                                    <span>Tutup</span>
                                    <span class="spinner"></span>

                                </button>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        @foreach ($store as $outlet)

            let map{{ $outlet->id }};

            $('#showMap{{ $outlet->id }}').on('shown.bs.modal', function() {


                let lat = {{ $outlet->latitude }};
                let lng = {{ $outlet->longitude }};


                if (!map{{ $outlet->id }}) {


                    map{{ $outlet->id }} = L.map(
                        'map{{ $outlet->id }}'
                    ).setView(
                        [lat, lng],
                        16
                    );


                    L.tileLayer(
                        'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }
                    ).addTo(
                        map{{ $outlet->id }}
                    );


                    L.marker(
                            [lat, lng]
                        )
                        .addTo(
                            map{{ $outlet->id }}
                        )
                        .bindPopup(
                            `
                    <b>{{ $outlet->store_name }}</b>
                    <br>
                    {{ $outlet->location }}
                    `
                        )
                        .openPopup();


                } else {

                    // refresh ukuran map saat modal dibuka
                    setTimeout(function() {

                        map{{ $outlet->id }}.invalidateSize();

                    }, 300);

                }


            });
        @endforeach

    });
</script>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
