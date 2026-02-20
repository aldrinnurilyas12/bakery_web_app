<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Profile</title>
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
    <div class="main-container">
        @php
            $code = request()->route('code');

            // coba anggap sebagai product_code dulu
            $productCode = DB::table('product_images')->where('product_code', $code)->value('product_code');

            // kalau tidak ketemu, berarti variant_code
            if (!$productCode) {
                $productCode = DB::table('v_daily_products')->where('variant', $code)->value('product_code');
            }

            $image_product = null;

            if ($productCode) {
                $image_product = DB::table('product_images')->where('product_code', $productCode)->first();
            }
        @endphp
        <div class="product-image">
            <img src="{{ url('storage/' . $image_product->images) }}" alt="Product Image">
        </div>
        <div class="container-fluid">

            <div class="container">
                <div class="product-card">


                    <div class="container-product-info">
                        <div style="display: flex; justify-content:space-between;" class="info-detail">
                            <div style="display: flex; justify-content:space-between; gap:10px;font-size: 14px;"
                                class="group-like">
                                <p style="margin-bottom: 10px;" class="text-secondary">{{ $product->category }}</p>

                                @if ($product->total_like == 0)
                                @else
                                    &middot;
                                    <p>{{ $product->total_like }} orang suka ini</p>
                                @endif
                            </div>

                            <div class="form-favorite">
                                <form action="{{ route('add_favorite') }}" method="POST">
                                    @csrf
                                    @if ($product->variant)
                                        <input hidden type="text" name="variant_code"
                                            value="{{ $product->variant }}">
                                        <input hidden type="text" name="product_code"
                                            value="{{ $product->product_code }}">
                                    @else
                                        <input hidden type="text" name="product_code"
                                            value="{{ $product->product_code }}">
                                    @endif

                                    <button type="submit" style="background: none;border: none;"><i
                                            style="color:white; padding:6px; border-radius:50%; background:#ff034f74;"
                                            class="fa-regular fa-heart"></i></button>
                                </form>
                            </div>
                        </div>
                        <h4 style="margin-bottom: 4px;">{{ $product->product }}</h4>
                        @if ($product->variant_type)
                            <p style="margin-bottom: 5px;font-size: 15px;">Ukuran: {{ $product->variant_type }}</p>
                        @endif
                        @if ($product->variant_price)
                            @if ($product->variant_discount)
                                <div class="flex-price-item">
                                    <p class="price">Rp {{ number_format($product->variant_price_after_discount) }}
                                    </p>
                                    <p class="text-danger">-{{ $product->variant_discount }}%</p>
                                    <p style="text-decoration:line-through;" class="text-secondary">
                                        {{ 'Rp.' . number_format($product->variant_price) }}</p>
                                </div>
                            @else
                                <p class="price">Rp {{ number_format($product->variant_price) }}</p>
                            @endif
                        @else
                            @if ($product->discount)
                                <div class="flex-price-item">
                                    <p class="price">Rp {{ number_format($product->price_after_discount) }}</p>
                                    <p class="text-danger">-{{ $product->discount }}%</p>
                                    <p style="text-decoration:line-through;" class="text-secondary">
                                        {{ 'Rp.' . number_format($product->price) }}</p>
                                </div>
                            @else
                                <p class="price">Rp {{ number_format($product->price) }}</p>
                            @endif
                        @endif


                        <div class="description">
                            {{ $product->description }}
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

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
