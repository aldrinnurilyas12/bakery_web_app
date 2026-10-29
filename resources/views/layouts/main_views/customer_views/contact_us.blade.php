<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Hubungi Kami</title>
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
    @php
        $main_menu = DB::table('main_menu')->where('location', 'main_web')->get();
        $submenu = DB::table('submenu')->where('show_menu', 'Y')->get();

    @endphp
    <div class="main-container">

        <div class="container-fluid">
            <div class="container">
                <br>
                <br>
                <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                    <div class="route-back">
                        <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                    <h4 style="font-size: 20px;margin-bottom: 0;"><strong>Hubungi Kami</strong></h4>
                </div>

                <hr class="hr-menu">


                <strong>Hubungi Kami</strong>

                <div class="menu-list">
                    <div style="margin-top: 20px;" class="group-menu">
                        <div style="display: flex; gap:10px;align-items: center;" class="group-icon-list">
                            <i class="fa-brands fa-whatsapp"></i>
                            <a class="menu" href="{{ route('outlet_list') }}" class="text-decoration-none">
                                <a style="color: black;" href="https://wa.me/6289678939389">
                                    <p>+62
                                        896-789-39389</p>
                                </a>
                            </a>
                        </div>
                    </div>

                    <hr class="hr-menu">

                    <div class="group-menu">
                        <div style="display: flex; gap:10px;align-items: center;" class="group-icon-list">
                            <i class="fa fa-envelope"></i>
                            <a class="menu" href="{{ route('privacy_policy') }}" class="text-decoration-none">
                                <a style="color: black;" href="mailto:kencanabakery_support@kencanabakery.co.id">
                                    <p>kencanabakery_support@kencanabakery.co.id</p>
                                </a>
                            </a>
                        </div>
                    </div>

                    <hr class="hr-menu">

                    <strong>Sosial Media</strong>


                    <div style="margin-top: 20px;" class="group-menu">
                        <div style="display: flex; gap:10px;align-items: center;" class="group-icon-list">
                            <i class="fa-brands fa-instagram"></i>
                            <a class="menu" href="{{ route('outlet_list') }}" class="text-decoration-none">
                                <a style="color: black;" href="https://www.instagram.com/kencanabakerycentral/">
                                    <p>Kencana
                                        Bakery</p>
                                </a>
                            </a>
                        </div>
                    </div>

                    <hr class="hr-menu">

                    <strong>Kantor Pusat</strong>


                    <div style="margin-top: 20px;" class="group-menu">
                        <div style="display: flex; gap:10px;align-items: center;" class="group-icon-list">
                            <i class="fa fa-location"></i>

                            <div style="display: block;" class="content-block">
                                <p><strong>PT Kencana Bakery Boga Sejahtera Tbk</strong></p>

                                <small>Jl Jenderal Sudirman Kav 182 No.190,RT.5/RW.3, Senayan, Kec. Kby. Baru, Kota
                                    Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12190, Indonesia.</small>
                                <br>
                                <small>Hubungi: 021 7330048</small>
                            </div>

                        </div>
                    </div>



                </div>



            </div>




            @include('layouts.main_views.components.bottom_nav')

        </div>


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
