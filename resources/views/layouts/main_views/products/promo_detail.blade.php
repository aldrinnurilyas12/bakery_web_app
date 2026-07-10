<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Profile</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">
        @php
            $code = request()->route('code');

            // coba anggap sebagai product_code dulu
            $productCode = DB::table('product_images')->where('product_code', $code)->value('product_code');

            // kalau tidak ketemu, berarti variant_code
            if (!$productCode) {
                $productCode = DB::table('v_daily_products')->where('variant_code', $code)->value('product_code');
            }

            $image_product = null;

            if ($productCode) {
                $image_product = DB::table('product_images')->where('product_code', $productCode)->first();
            }
        @endphp
        <div class="product-image">
            <div class="route-back">
                <a href="{{ route('home') }}"><i class="fa fa-arrow-left"></i></a>
            </div>
            <img src="{{ url('storage/' . $promo_bundling->images) }}" alt="Product Image">
        </div>
        <div class="container-fluid">

            <div class="container">
                <div class="product-card">

                    <div class="container-product-info">
                        <div style="display: flex; justify-content: space-between;" class="flex-info-promo">
                            <h4 style="margin-bottom: 4px;">{{ $promo_bundling->bundling_name }}</h4>
                             <span class="promo-badge">Promo Bundling</span>
                        </div>
                        <p class="price">Rp {{ number_format($promo_bundling->price) }}</p>

                         <p> Detail item: </p>
                        <div class="info-product-detail">
                             <ul>

                                  @foreach ($all_product as $prd )
                                      @if($promo_bundling->bundling_code == $prd->bundling_code)
                                      <li style="font-size:14px;">{{ $prd->product_name }} &nbsp; x{{ $prd->quantity }}</li>
                                      @endif
                                   @endforeach
                            </ul>
                                                              
                        </div>

                        <div style="margin-bottom: 30px;" class="description">
                            {{ $promo_bundling->description ?: 'Tidak ada deskripsi' }}
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
                title: 'Berhasil',
                text: "{{ Session::get('message_success') }}",
                icon: 'success',
                timer: 1000,
                confirmButtonText: 'OK'
            });
        </script>
    @endif

</body>
<style>
    .route-back{
    width:30px;
    height:30px;
    background:#bb0239;
    border-radius:50%;
    position:absolute;
    margin: 10px 10px 0 10px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.route-back a{
    color:#fff;
    font-size:14px;
    text-decoration:none;
    display:flex;
    justify-content:center;
    align-items:center;
    width:100%;
    height:100%;
}
</style>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
