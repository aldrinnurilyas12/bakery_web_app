<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Home</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
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

        {{-- @include('layouts.component_admin.navbar.maintenance_info') --}}
        <!-- CUSTOMER SEGMENT -->
        <div class="customer-segment">
            <div class="main-customer">
                <div class="img-logo">
                    <img src="{{ asset('assets\front_end\assets\logo\kencanabakery.png') }}" alt="Kencana Bakery Logo"
                        class="logo" />
                    <div class="notification-customer">
                        @if (auth()->guard('customer')->user())
                            <a href="{{ route('notification') }}" class="notification-link">
                                <i class="fa fa-bell"></i>
                                <span class="count-notif">
                                    {{ $notif_customer > 99 ? '99+' : $notif_customer }}
                                </span>
                            </a>
                        @else
                            <div style="display: flex; gap:10px;" class="info-content">
                                <a href="{{ route('outlet_list') }}" class="notification-link">
                                    <i class="fas fa-map"></i>
                                </a>
                                <a href="{{ route('privacy_policy') }}" class="notification-link">
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="grettings-customer">
                    @if (auth()->guard('customer')->user())
                        <p class="hello">Selamat datang,
                            <span
                                class="session-name">{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->name }}</span>
                        </p>
                    @else
                        <p class="hello">Selamat Datang </p>
                    @endif
                </div>


                <div class="center-element">
                    <div class="location-map">
                        <i class="fa fa-location-dot"></i>
                        <form class="filter-store" action="{{ route('fstore') }}" method="GET">
                            <select style="background: none;color:white; border:none;"
                                class="select-location form-control" name="store" id="">
                                @foreach ($store as $st)
                                    <option style="text-decoration: underline;" value="{{ $st->store_code }}"
                                        {{ request('store') == $st->store_code ? 'selected' : '' }}>
                                        {{ $st->store_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn-filter-store" type="submit">pilih</button>
                        </form>
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
            <!-- PROMO -->
            @if ($promos->isNotEmpty())
                <div class="container">
                    <div class="container-content-promo">
                        <div class="title-content d-flex mb-2">
                            <h1 class="title">Best Deals</h1>
                            <a style="color:#bb0239;text-decoration: underline;"
                                href="{{ route('promo-campaign') }}">lihat
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
                                                        <img style="width:100%;height:150px;"
                                                            src="{{ asset('storage/' . $promo->images) }}"
                                                            alt="">
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
            @endif

            {{-- section Products --}}

            <div style="margin-top: 30px;margin-bottom:10px;" class="container">
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

            {{-- TAB CONTENT PRODUCTS --}}

            <div class="tab-content">
                {{-- Tab all products --}}
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    <div class="container-products">
                        @if ($products->isNotEmpty())
                            <div class="grid-products">
                                @foreach ($products as $item)
                                    @php
                                        $img = DB::table('product_images')
                                            ->where('product_code', $item->product_code)
                                            ->first();

                                    @endphp
                                    <a class="direct-content" href="{{ route('product', $item->slug) }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="image-wrapper">
                                                    @if ($img)
                                                        <img class="products-img"
                                                            src="{{ asset('storage/' . $img->images) }}"
                                                            alt="">
                                                    @endif

                                                    <div class="image-overlay">
                                                        <div class="form-favorite">
                                                            <form action="{{ route('add_favorite') }}"
                                                                method="POST">
                                                                @csrf

                                                                {{-- UBAH INI PAKAI VARIANT CODE DAN PRODUCT_CODE --}}
                                                                <input hidden type="text" name="product"
                                                                    value="{{ $item->product_code }}">
                                                                <input hidden type="text" name="variant"
                                                                    value="{{ $item->variant_code }}">

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
                                            </div>

                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p style="text-align: center;margin:0 auto;">Produk tidak tersedia</p>
                        @endif
                    </div>

                </div>

                {{-- Tab Promo --}}
                <div class="tab-pane fade" id="tab-promo" role="tabpanel">
                    <div class="container-products">

                        @if ($products_promo->isNotEmpty())
                            <div class="grid-products">
                                @foreach ($products_promo as $item)
                                    @php
                                        $img = DB::table('product_images')
                                            ->where('product_code', $item->product_code)
                                            ->first();

                                    @endphp


                                    <a class="direct-content" href="{{ route('product', $item->slug) }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="image-wrapper">
                                                    @if ($img)
                                                        <img class="products-img"
                                                            src="{{ asset('storage/' . $img->images) }}"
                                                            alt="">
                                                    @endif

                                                    <div class="image-overlay">
                                                        <div class="form-favorite">
                                                            <form action="{{ route('add_favorite') }}"
                                                                method="POST">
                                                                @csrf
                                                                {{-- UBAH INI PAKAI VARIANT CODE DAN PRODUCT_CODE --}}
                                                                <input hidden type="text" name="product"
                                                                    value="{{ $item->product_code }}">
                                                                <input hidden type="text" name="variant"
                                                                    value="{{ $item->variant_code }}">

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
                                            </div>

                                        </div>
                                    </a>
                                @endforeach

                                @if ($promo_bundling)
                                    @foreach ($promo_bundling as $item)
                                        <a class="direct-content" href="{{ route('promo', $item->bundling_code) }}">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="image-wrapper">

                                                        <img class="products-img"
                                                            src="{{ asset('storage/' . $item->images) }}"
                                                            alt="">


                                                        <div class="image-overlay">
                                                            <span class="promo-badge">Promo</span>

                                                        </div>
                                                    </div>

                                                    <h2 class="product-name">{{ $item->bundling_name }}</h2>

                                                    <div class="product-price">
                                                        <div class="flex-price">
                                                            <p class="price" style="margin:0;">Rp
                                                                {{ number_format($item->price) }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="detail-product">
                                                        <p> Detail item: </p>

                                                        <div class="detail-info-product">

                                                            <ul>
                                                                @php
                                                                    $stockHabis = false;
                                                                @endphp
                                                                @foreach ($all_product as $prd)
                                                                    @if ($item->bundling_code == $prd->bundling_code)
                                                                        @if ($prd->stock_available <= 0)
                                                                            @php
                                                                                $stockHabis = true;
                                                                            @endphp
                                                                        @endif
                                                                        <li style="font-size:14px;">
                                                                            {{ $prd->product_name }}
                                                                            &nbsp; x{{ $prd->quantity }}</li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>

                                                        </div>

                                                        <div style="margin-bottom:10px;" class="info-stockempty">
                                                            @if ($stockHabis)
                                                                <span style="font-size: 13px;"
                                                                    class="text-danger">*ada
                                                                    produk
                                                                    habis</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <p style="text-align: center;margin:0 auto;">Promo tidak tersedia</p>
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

                                        <a class="direct-content" href="{{ route('product', $item->slug) }}">
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
                                                                    {{-- UBAH INI PAKAI VARIANT CODE DAN PRODUCT_CODE --}}
                                                                    <input hidden type="text" name="product"
                                                                        value="{{ $item->product_code }}">
                                                                    <input hidden type="text" name="variant"
                                                                        value="{{ $item->variant_code }}">

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
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p style="text-align: center;margin:0 auto;">Produk tidak tersedia</p>
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
            });
        </script>
    @elseif(Session::has('failed_message'))
        <script>
            Swal.fire({
                title: 'Gagal',
                text: "{{ Session::get('failed_message') }}",
                icon: 'error',
                timer: 2000,
            });
        </script>
    @elseif(Session::has('product_not_found'))
        <script>
            Swal.fire({
                text: "{{ Session::get('product_not_found') }}",
                icon: 'error',
                timer: 1000
            });
        </script>
    @endif

    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
