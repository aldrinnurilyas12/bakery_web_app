<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Pesanan anda</title>
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
    @php
        $auth = auth()->guard('customer')->user();
    @endphp
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
                                <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        @endif
                        <h4 style="font-size: 20px;margin-bottom:0;"><strong>Pesanan anda</strong></h4>
                    </div>
                </div>
                <hr class="hr-menu">

                <div class="tab-content">
                    {{-- Tab all products --}}

                    <div style="width:100%;" class="container-products">
                        <div style="width:100%;" class="menu-list">


                            @if ($auth)
                                <p style="text-align: center;margin:0 auto;">Layanan ini belum tersedia</p>
                                {{-- <div style="display: block;width:100%;" class="form-redeem">
                                    @csrf
                                    <div class="form-group">
                                        <label for=""><strong>Pilih Store</strong></label>
                                        <select class="form-control" name="store" id="code_store">
                                            <option value="">=== Pilih Store ===</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->store_code }}">
                                                    {{ $store->store_name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('store')" class="text-danger" />
                                    </div>
                                    <div class="form-group">
                                        <label for=""><strong>Waktu pengambilan</strong></label>

                                        <input class="form-control" type="time" min="08:00" max="20:00"
                                            name="pickup_schedule">
                                        <small class="text-danger">*waktu operasional outlet (08:00 am s.d 20:00
                                            pm)</small>
                                        <x-input-error :messages="$errors->get('pickup_schedule')" class="text-danger" />

                                    </div>

                                </div> --}}
                            @else
                                <p style="text-align: center;margin:0 auto;">Login dahulu</p>
                            @endif

                        </div>
                    </div>

                </div>



            </div>
            <br>







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
