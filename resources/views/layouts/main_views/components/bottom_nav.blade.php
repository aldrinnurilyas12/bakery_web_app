<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets\front_end\css\homepage.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>

<body>
    <div class="navbar-wrapper">
        <nav class="navbar fixed-bottom d-lg" style="padding:5px;">
            <div class="container-fluid d-flex justify-content-around align-items-center position-relative"
                style="background:white;">

                <!-- Home -->
                <a href="{{ route('home') }}" class="text-decoration-none text-secondary text-center">
                    <i class="fa fa-home fa-lg"></i><br>
                    <small style="color:black;" class="icon-text">Home</small>
                </a>



                <!-- Favorite -->
                <a href="{{ route('favorite') }}" class="text-decoration-none text-secondary text-center">
                    <i class="fa fa-heart"></i><br>
                    <small style="color:black;" class="icon-text">Favorit</small>
                </a>

                @if (auth()->guard('customer')->user())
                    {{-- <a href="{{ url('/history') }}" class="text-decoration-none text-secondary text-center">
                        <i class="fa fa-history fa-lg"></i><br>
                        <small class="icon-text">Pesanan</small>
                    </a>

                    <!-- Cart -->
                    <a href="{{ url('/barcode') }}" class="text-decoration-none text-secondary text-center">
                        <div>
                            <i class="fa fa-shopping-cart"></i><br>
                            <small class="icon-text">Keranjang</small>
                        </div>
                    </a> --}}

                    <a href="#" data-toggle="modal" data-target="#openqr"
                        class="text-decoration-none text-secondary text-center">
                        <div>
                            <i style="background: #bb0239; padding:6px; border-radius: 4px;color:white;"
                                class="fa fa-qrcode"></i><br>
                            <small style="color:black;" class="icon-text">QR Code</small>
                        </div>
                    </a>
                @endif

                <a href="{{ route('rewards-catalogue') }}" class="text-decoration-none text-secondary text-center">
                    <i class="fa fa-gift"></i><br>
                    <small style="color:black;" class="icon-text">Rewards</small>
                </a>

                <!-- Profile -->
                @if (auth()->guard('customer')->user())
                    <a href="{{ route('profile-menu') }}" class="text-decoration-none text-secondary text-center">
                        <i class="fa fa-list"></i><br>
                        <small style="color:black;" class="icon-text">Menu</small>
                    </a>
                @else
                    <a href="{{ url('/login_app') }}" class="text-decoration-none text-secondary text-center">
                        <i class="fa fa-user"></i><br>
                        <small style="color:black;" class="icon-text">Login</small>
                    </a>
                @endif

            </div>
        </nav>
    </div>


    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>
</body>

<style>
    /* Tombol tengah barcode */
    .barcode-btn {
        width: 56px;
        height: 56px;
        margin-top: -28px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        z-index: 1;
    }

    .barcode-btn:hover {
        background-color: #bb0239;
    }

    .icon-text {
        font-size: 12px;
    }
</style>

</html>
