<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Favorite</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/front_end/assets/logo/kencanabakery_logo2.png') }}">
</head>

<body>

    <div class="main-container">
        <div class="container-fluid">
            <br>
            <div style="display: flex; gap:10px;align-items:center;padding-left: 10px;" class="title-content-head">
                <a style="color:black;" href="{{ route('home') }}">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h4 style="font-size: 20px;"><strong>Favorite</strong></h4>
            </div>


            <div class="menu-list">
                <hr style="margin: 15px;" class="hr-menu">
                <div class="container-products">
                    @if ($product_favorite->isNotEmpty())
                        <div class="grid-products-favorite">
                            @foreach ($product_favorite as $item)
                                @php
                                    $img = DB::table('product_images')
                                        ->where('product_code', $item->product_code)
                                        ->first();

                                @endphp
                                <div class="card-favorite">
                                    <div class="card-body">
                                        <div class="image-wrapper">
                                            @if ($img)
                                                <img class="products-img" src="{{ asset('storage/' . $img->images) }}"
                                                    alt="">
                                            @endif

                                            <div class="image-overlay">
                                                <div class="form-favorite">
                                                    {{-- FIX THIS --}}
                                                    <form action="{{ route('remove-favorite', $item->product_code) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input hidden type="text" name="product"
                                                            value="{{ $item->product_code }}">
                                                        <input hidden type="text" name="variant"
                                                            value="{{ $item->variant_code }}">
                                                        <button type="submit" style="background: none;border: none;"><i
                                                                style="color:white; padding:6px; border-radius:50%; background:#ff034f74;"
                                                                class="fa-solid fa-heart"></i></button>
                                                    </form>
                                                </div>
                                                @if ($item->discount || $item->variant_discount)
                                                    <span class="promo-badge">Promo</span>
                                                @endif
                                            </div>
                                        </div>

                                        <h2 class="product-name">{{ $item->product }}</h2>

                                        <div class="product-price">
                                            @if ($item->variant_price)
                                                @if ($item->variant_discount)
                                                    <div class="flex-price">
                                                        <p class="price" style="margin:0;">Rp
                                                            {{ number_format($item->variant_price_after_discount) }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="flex-price">
                                                        <p class="price" style="margin:0;">Rp
                                                            {{ number_format($item->variant_price) }}
                                                        </p>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($item->discount)
                                                    <div class="flex-price">
                                                        <p class="price" style="margin:0;">Rp
                                                            {{ number_format($item->price_after_discount) }}</p>
                                                    </div>
                                                @else
                                                    <div class="flex-price">
                                                        <p class="price" style="margin:0;">Rp
                                                            {{ number_format($item->price) }}</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="btn-detail">
                                            @if ($item->variant_code)
                                                <a class="btn-detail-product"
                                                    href="{{ route('product', $item->variant_code) }}">detail</a>
                                            @else
                                                <a class="btn-detail-product"
                                                    href="{{ route('product', $item->product_code) }}">detail</a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="center">Produk favorite anda belum ada</div>
                    @endif
                </div>

            </div>

            <br>

            @include('layouts.main_views.components.bottom_nav')


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
                                    <img width="300" height="300" src="{{ url('storage/' . $customer->qr_code) }}"
                                        alt="">
                                    <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi dan
                                        anda
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

        <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="display: flex; justify-content: space-between;" class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Keluar</h5>
                        <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                            data-dismiss="modal" aria-label="Close">
                            <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="font-size: 14px; line-height: 1.6;">
                        <p>Apakah anda yakin ingin keluar?</p>
                    </div>

                    <div class="modal-footer">
                        <form id="logout-form" action="{{ route('logout_account') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
