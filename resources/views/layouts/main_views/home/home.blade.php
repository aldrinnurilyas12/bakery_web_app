<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Home</title>
    <link rel="stylesheet" href="{{ asset('assets\front_end\css\homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="main-container">
        <!-- CUSTOMER SEGMENT -->
        <div class="customer-segment">
            <div class="main-customer">
                <div class="img-logo">
                    <img src="{{ asset('assets\front_end\assets\logo\kencanabakery.png') }}" alt="Kencana Bakery Logo"
                        class="logo" />
                    <div class="notification-customer">
                        <a style="color: white;" href="{{ route('notification') }}">
                            <i class="fa fa-bell"></i>
                        </a>
                    </div>
                </div>


                <div class="grettings-customer">
                    @if (auth()->guard('customer')->user())
                        <p class="hello">Selamat datang,
                            <span
                                class="session-name">{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->name }}</span>
                        </p>
                    @else
                        <p class="hello">Selamat datang </p>
                    @endif
                </div>


                <div class="center-element">
                    <div class="location-map">
                        <i class="fa fa-location-dot"></i>
                        <select style="background: none;color:white; border:none;" class="select-location form-control"
                            name="" id="">
                            @foreach ($store as $st)
                                <option value="">{{ $st->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-segment">
                        <form action="{{ route('product-search') }}" method="GET">
                            <div class="input-search">
                                <i class="fa fa-search"></i>
                                <input name="search" class="form-control" placeholder="Cari produk disini..."
                                    type="search" autocomplete="off">
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid">

            @php
                if (auth()->guard('customer')->user()) {
                    $CUSTOMER_LOGIN_SESSION = app(
                        'App\Http\Controllers\Auth\AuthenticatedSessionController',
                    )->getCustomer()->customer_code;
                    $customer = DB::table('v_customers')->where('customer_code', $CUSTOMER_LOGIN_SESSION)->first();
                }
            @endphp

            <!-- PROMO -->
            <div class="container">
                <div class="container-content-promo">
                    <div class="title-content d-flex mb-2">
                        <h1 class="title">Best Deals</h1>
                        <a style="color:#bb0239;text-decoration: underline;" href="{{ route('promo-campaign') }}">lihat
                            semua</a>
                    </div>
                    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @forelse ($promos as $index => $promo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="d-flex justify-content-center">
                                        <div class="card-promo">
                                            <a style="text-decoration: none;"
                                                href="{{ route('promo-detail', $promo->promo_code) }}">
                                                <div class="card-body-promo">
                                                    <p style="color:#bb0239;">
                                                        {{ $promo->promo_code }}
                                                    </p>

                                                    <h5 style="color:black;" class="card-title">
                                                        {{ $promo->promo_name }}
                                                    </h5>

                                                    <p class="text-secondary">
                                                        {{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}
                                                        s.d
                                                        {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                                                    </p>

                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center mt-3">No Promo available.</div>
                            @endforelse
                        </div>

                        <button class="carousel-control-prev  custom-carousel-btn" type="button"
                            data-bs-target="#promoCarousel" data-bs-slide="prev"
                            style="background:gainsboro;width:30px;height:30px;border-radius:10px;">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next  custom-carousel-btn" type="button"
                            data-bs-target="#promoCarousel" data-bs-slide="next"
                            style="background:gainsboro;width:30px;height:30px;border-radius:10px;">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- section Products --}}

            <div class="container">
                <div class="title-content">
                    <h1 class="title">Produk kami</h1>
                </div>

                @if ($category_products->count())

                    <div class="grid" role="tablist">

                        <!-- TAB SEMUA -->
                        <a class="card-category active" data-bs-toggle="tab" href="#tab-all" role="tab">
                            <p class="product-category-show">Semua</p>
                        </a>
                        <a class="card-category" data-bs-toggle="tab" href="#tab-promo" role="tab">
                            <i class="fa fa-tag"></i>
                            <p class="product-category-show">Promo</p>
                        </a>

                        <!-- TAB CATEGORY -->
                        @foreach ($category_products as $ctg)
                            @php
                                $tabId = Str::slug($ctg->category_name, '-');
                            @endphp

                            <a class="card-category" data-bs-toggle="tab" href="#tab-{{ $tabId }}"
                                role="tab">
                                <i class="{{ $ctg->icon }}"></i>
                                <p class="product-category-show">
                                    {{ $ctg->category_name }}
                                </p>
                            </a>
                        @endforeach

                    </div>
                @endif
            </div>

            @php
                $image_product = DB::table('product_images as pi')
                    ->select('pi.product_code', 'pi.images')
                    ->leftJoin('v_daily_products as vp', 'pi.product_code', '=', 'vp.product_code')
                    ->get()
                    ->keyBy('product_code');
            @endphp

            {{-- ALL PRODUCTS --}}

            <div class="tab-content">
                {{-- Tab all products --}}
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    <div class="container-products">
                        @if ($products)
                            <div class="grid-products">
                                @foreach ($products as $item)
                                    @php
                                        $img = DB::table('product_images')
                                            ->where('product_code', $item->product_code)
                                            ->first();

                                    @endphp



                                    <div class="card">
                                        <div class="card-body">
                                            <div class="image-wrapper">
                                                @if ($img)
                                                    <img class="products-img"
                                                        src="{{ asset('storage/' . $img->images) }}" alt="">
                                                @endif

                                                <div class="image-overlay">
                                                    <div class="form-favorite">
                                                        <form action="{{ route('add_favorite') }}" method="POST">
                                                            @csrf

                                                            <input hidden type="text" name="daily_code"
                                                                value="{{ $item->daily_code }}">

                                                            <button type="submit"
                                                                style="background: none;border: none;"><i
                                                                    style="color:white; padding:6px; border-radius:50%; background:#ff034f74;"
                                                                    class="fa-regular fa-heart"></i></button>
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
                            <div class="center">Produk tidak tersedia</div>
                        @endif
                    </div>

                </div>

                {{-- Tab Promo --}}
                <div class="tab-pane fade" id="tab-promo" role="tabpanel">
                    <div class="container-products">
                        @if ($products_promo)
                            <div class="grid-products">
                                @foreach ($products_promo as $item)
                                    @php
                                        $img = DB::table('product_images')
                                            ->where('product_code', $item->product_code)
                                            ->first();

                                    @endphp



                                    <div class="card">
                                        <div class="card-body">
                                            <div class="image-wrapper">
                                                @if ($img)
                                                    <img class="products-img"
                                                        src="{{ asset('storage/' . $img->images) }}" alt="">
                                                @endif

                                                <div class="image-overlay">
                                                    <div class="form-favorite">
                                                        <form action="{{ route('add_favorite') }}" method="POST">
                                                            @csrf
                                                            <input hidden type="text" name="daily_code"
                                                                value="{{ $item->daily_code }}">

                                                            <button type="submit"
                                                                style="background: none;border: none;"><i
                                                                    style="color:white; padding:6px; border-radius:50%; background:#ff034f74;"
                                                                    class="fa-regular fa-heart"></i></button>
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
                            <div class="center">Produk tidak tersedia</div>
                        @endif
                    </div>
                </div>

                {{-- TAB PER CATEGORY --}}
                @foreach ($category_products as $ctg)
                    @php
                        $tabId = Str::slug($ctg->category_name, '-');
                        $filtered_products = $products->where('category', $ctg->category_name);
                    @endphp

                    <div class="tab-pane fade" id="tab-{{ $tabId }}" role="tabpanel">
                        <div class="container-products">

                            @if ($filtered_products->isNotEmpty())
                                <div class="grid-products">
                                    @foreach ($filtered_products as $item)
                                        @php
                                            $img = $image_product[$item->product_code]->images ?? null;
                                        @endphp

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="image-wrapper">
                                                    <img class="products-img"
                                                        src="{{ $img ? asset('storage/' . $img) : asset('img/no-image.png') }}"
                                                        alt="{{ $item->product }}">

                                                    <div class="image-overlay">
                                                        <div class="form-favorite">
                                                            <form action="{{ route('add_favorite') }}"
                                                                method="POST">
                                                                @csrf

                                                                <input hidden type="text" name="daily_code"
                                                                    value="{{ $item->daily_code }}">

                                                                <button type="submit"
                                                                    style="background: none;border: none;"><i
                                                                        style="color:white; padding:6px; border-radius:50%; background:#ff034f74;"
                                                                        class="fa-regular fa-heart"></i></button>
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
                                                                    {{ number_format($item->price_after_discount) }}
                                                                </p>
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
                                                    <a class="btn-detail-product"
                                                        href="{{ route('product', $item->product_code) }}">detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="center">Produk tidak tersedia</div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- END --}}

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


            <br>
            @include('layouts.main_views.components.bottom_nav')
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
    @elseif(Session::has('failed_message'))
        <script>
            Swal.fire({
                title: 'Gagal',
                text: "{{ Session::get('failed_message') }}",
                icon: 'error',
                timer: 4000,
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>


</html>
