<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Buat Transaksi</title>
    <link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/transaction_create.css') }}">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <script src="https://cdn.jsdelivr.net/gh/schmich/instascan-builds/instascan.min.js"></script>
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    {{-- @include('layouts.component_admin.sidebar.sidebar') --}}
    <div id="layoutSidenav">
        <div style="width: 100%; padding: 20px;margin-top: 30px;" id="adada">
            <main>
                <br>
                <div class="container">
                    <div class="information-detail-casheer">
                        <div style="display: block;" class="back-btn-title">
                            <div class="btn-back">
                                <a class="btn btn-primary" style="text-decoration:none;"
                                    href="{{ route('transaction.index') }}"><i class="fa fa-arrow-left"></i>&nbsp;
                                    Kembali</a>
                            </div>
                            <br>
                            <h4><strong>Transaksi</strong></h4>
                        </div>
                        <div style="display:block; font-size: 13px;" class="casheer-info">
                            <div class="casheer">
                                <i class="fa fa-user"></i> <span>{{ $casheer }} </span>
                            </div>
                            <div style="display:flex;gap:10px; justify-content: space-between;" class="group-store">
                                <div class="time">
                                    <i class="fa fa-clock"></i> <span
                                        class="text-secondary">{{ now()->format('Y-m-d') }}</span>
                                </div>

                                <div class="store">
                                    <i class="fa fa-shop"></i> <span
                                        class="text-secondary">{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name }}</span>
                                </div>
                            </div>
                            <br>
                            <div style="display:flex; gap:20px; justify-content: space-between;"
                                class="transaction-history">
                                <a class="text-primary" href="{{ route('transaction.index') }}"><i
                                        class="fa fa-list"></i>&nbsp;Riwayat
                                    Transaksi</a>

                                <span>|</span>

                                <a class="text-primary" href="{{ route('dailyproducts_data') }}"><i
                                        class="fa fa-list"></i>&nbsp;Produk Daily</a>
                            </div>
                        </div>
                    </div>
                    <hr>

                    {{-- @php
                        $voucherQuotaUsedTotal = DB::table('transactions_voucher as vu')
                            ->leftJoin('voucher as v', 'v.voucher_code', '=', 'vu.voucher_code')
                            ->where('vu.voucher_code', 'VOUCHER5709f86f')
                            ->where('vu.voucher_used', 'Y')
                            ->count('vu.voucher_code');
                        dd($voucherQuotaUsedTotal);
                    @endphp --}}

                    @if ($category_data)
                        <div class="main-container-content">

                            <div class="container-content">
                                <div class="tab-content" id="tab-content">
                                    <div style="width: 90%;" class="filter-content">
                                        <div class="category-scroll">
                                            <ul class="nav nav-tabs" id="nav-scroll" role="tablist" style="gap: 10px;">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="tab-all-tab" data-bs-toggle="tab"
                                                        href="#tab-all" role="tab" aria-controls="tab-all"
                                                        aria-selected="true">Semua</a>
                                                </li>

                                                <li class="nav-item" role="presentation">
                                                    <a style="color: #bb0239;" class="nav-link" id="tab-bundling-tab"
                                                        data-bs-toggle="tab" href="#tab-bundling" role="tab"
                                                        aria-controls="tab-bundling" aria-selected="false">Promo
                                                    </a>
                                                </li>
                                                @foreach ($category_data as $ctg)
                                                    <li class="nav-item" role="presentation">
                                                        <a style="color: #bb0239;" class="nav-link"
                                                            id="tab-{{ $ctg->category_name }}-tab" data-bs-toggle="tab"
                                                            href="#tab-{{ $ctg->category_name }}" role="tab"
                                                            aria-controls="tab-{{ $ctg->category_name }}"
                                                            aria-selected="false">
                                                            {{ $ctg->category_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    </div>

                                    <hr>

                                    <div class="tab-pane active" id="tab-all" role="tabpanel"
                                        aria-labelledby="tab-all">
                                        @if ($all_products->isNotEmpty())
                                            <div class="card-body">
                                                <div class="tab-pane fade show active" id="tab-all" role="tabpanel"
                                                    aria-labelledby="tab-all-tab">
                                                    <div class="card-body">
                                                        <div class="content-product-show">
                                                            <div class="products-card"
                                                                style="display: flex; flex-wrap: wrap; gap: 20px;">
                                                                @foreach ($all_products as $product)
                                                                    @php
                                                                        $products_images = DB::table('product_images')
                                                                            ->where(
                                                                                'product_code',
                                                                                $product->product_code,
                                                                            )
                                                                            ->first();
                                                                    @endphp
                                                                    <div style="position: left;height:max-content;"
                                                                        class="card" style="width: 200px;">
                                                                        @if ($product->product_code == $products_images->product_code)
                                                                            <img class="card-img"
                                                                                src="{{ asset('storage/' . $products_images->images) }}"
                                                                                alt="">
                                                                            @if ($product->discount)
                                                                                <span
                                                                                    style="background:#bb0239; color:white;border-radius: 2px;font-size: 15px;padding:5px;position: absolute;align-self: self-end;font-weight: bold;"
                                                                                    class="discount">{{ '-' . $product->discount . '%' }}</span>
                                                                            @endif
                                                                        @else
                                                                        @endif
                                                                        <p class="product-name">
                                                                            <strong>{{ $product->product }}</strong>
                                                                        </p>
                                                                        @if ($product->category)
                                                                            <div style="display: flex; gap:6px;"
                                                                                class="category-class">
                                                                                <small
                                                                                    class="text-secondary">{{ $product->category }}</small>
                                                                                @if ($product->variant_type)
                                                                                    &dot;
                                                                                    <small
                                                                                        class="text-info">{{ $product->variant_type }}</small>
                                                                                @endif
                                                                            </div>
                                                                        @else
                                                                        @endif

                                                                        @if ($product->variant_code == null)
                                                                            @if ($product->price_after_discount)
                                                                                <div class="price">
                                                                                    <p>{{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                                    </p>
                                                                                </div>
                                                                            @else
                                                                                <div class="price">
                                                                                    <p>{{ 'Rp.' . number_format($product->price) }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif
                                                                            <div id="availableStock" class="stok">
                                                                                <p>Stok:
                                                                                    <span>{{ $product->stock_available }}</span>
                                                                                </p>
                                                                                {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                            </div>
                                                                        @else
                                                                            @if ($product->variant_price_after_discount)
                                                                                <div class="price">
                                                                                    <p>{{ 'Rp.' . number_format($product->variant_price_after_discount) }}
                                                                                    </p>
                                                                                    <small
                                                                                        class="discount">{{ '-' . $product->variant_discount . '%' }}</small>
                                                                                </div>
                                                                            @else
                                                                                <div class="price">
                                                                                    <p>{{ 'Rp.' . number_format($product->variant_price) }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif
                                                                            <div id="availableStock" class="stok">
                                                                                <p>Stok:
                                                                                    <span>{{ $product->stock_available }}</span>
                                                                                </p>
                                                                                {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                            </div>
                                                                        @endif

                                                                        <div class="btn-add-cart">
                                                                            <form class="form-general"
                                                                                action="{{ route('cart_add') }}"
                                                                                method="POST">
                                                                                @csrf

                                                                                @if ($product->product_code == $products_images->product_code)
                                                                                    <input type="text"
                                                                                        name="product_image"
                                                                                        value="{{ $products_images->images }}"
                                                                                        hidden>
                                                                                @endif
                                                                                <input type="hidden" name="product"
                                                                                    value="{{ $product->product_code }}">
                                                                                <input type="hidden" name="variant"
                                                                                    value="{{ $product->variant_code }}">
                                                                                <input type="hidden"
                                                                                    name="variant_type"
                                                                                    value="{{ $product->variant_type }}">
                                                                                <input type="hidden"
                                                                                    name="product_name"
                                                                                    value="{{ $product->product }}">
                                                                                <input type="hidden"
                                                                                    name="stock_available"
                                                                                    value="{{ $product->stock_available }}">

                                                                                @if ($product->variant_code)
                                                                                    @if ($product->variant_discount)
                                                                                        <input type="hidden"
                                                                                            name="price"
                                                                                            value="{{ $product->variant_price_after_discount }}">
                                                                                    @else
                                                                                        <input type="hidden"
                                                                                            name="price"
                                                                                            value="{{ $product->variant_price }}">
                                                                                    @endif
                                                                                @else
                                                                                    @if ($product->discount)
                                                                                        <input type="hidden"
                                                                                            name="price"
                                                                                            value="{{ $product->price_after_discount }}">
                                                                                    @else
                                                                                        <input type="hidden"
                                                                                            name="price"
                                                                                            value="{{ $product->price }}">
                                                                                    @endif
                                                                                @endif
                                                                                @if ($product->stock_available)
                                                                                    <button class="btn-general"
                                                                                        type="submit"><span
                                                                                            class="btn-text">Tambah</span>
                                                                                        <span
                                                                                            class="spinner"></span></button>
                                                                                @else
                                                                                    <button style="width:100%;"
                                                                                        class="btn btn-secondary"
                                                                                        type="button">Kosong</button>
                                                                                @endif
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div style="height: 50vh; display:flex; justify-content:center;border:1px solid gray;border-radius:10px;"
                                                class="empty-transaction">

                                                <div style="display: flex;" class="empty-content">
                                                    <div style="display: flex; gap:20px;margin:auto;">
                                                        <img width="70" height="70"
                                                            src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                            alt="">
                                                        <div style="display: block;align-self: center;"
                                                            class="text-content">
                                                            <h3>Produk belum ada</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>



                                    {{-- TAB PROMO BUNDLING --}}


                                    <div class="tab-pane" id="tab-bundling" role="tabpanel"
                                        aria-labelledby="tab-bundling-tab">
                                        @if ($promo_bundling->isNotEmpty())
                                            <div class="card-body">
                                                <div class="content-product-show">
                                                    <div class="products-card"
                                                        style="display: flex; flex-wrap: wrap; gap: 20px;">
                                                        @foreach ($promo_bundling as $product)
                                                            <div style="position: left;height:max-content;width: 200px;"
                                                                class="card">
                                                                <div style="position: absolute;background:#bb0239;color:white;padding:2px;border-radius: 3px;font-weight: bold;"
                                                                    class="category-class">
                                                                    <p>Paket Bundling</p>
                                                                </div>

                                                                <img style="width:100%;" class="card-img"
                                                                    src="{{ asset('storage/' . $product->images) }}"
                                                                    alt="">
                                                                <p class="product-name">
                                                                    <strong>{{ $product->bundling_name }}</strong>
                                                                </p>

                                                                @if ($product->price)
                                                                    <div class="price">
                                                                        <p>{{ 'Rp.' . number_format($product->price) }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                                <div style="display:flex; gap:10px;"
                                                                    id="availableStock" class="stok">
                                                                    <p>Stok:
                                                                        <span>{{ $product->quantity }}</span>
                                                                    </p>
                                                                    <span>|</span>
                                                                    <p>Sisa:
                                                                        @if ($product->total_available == 0)
                                                                            <span class="text-danger">habis</span>
                                                                        @else
                                                                            <span>{{ $product->total_available }}</span>
                                                                        @endif
                                                                    </p>
                                                                </div>

                                                                <div style="display:flex; gap:3px;font-size: 12px;"
                                                                    class="period-time">
                                                                    <p> {{ \Carbon\carbon::parse($product->start_date)->format('d-m-Y') }}
                                                                    </p>
                                                                    <p>s.d</p>
                                                                    <p> {{ \Carbon\carbon::parse($product->end_date)->format('d-m-Y') }}
                                                                    </p>
                                                                </div>

                                                                <div class="detail-product">
                                                                    <p> Detail item: </p>

                                                                    <div class="detail-info-product">

                                                                        @php
                                                                            $stockHabis = false;
                                                                            $produkHabis = [];
                                                                        @endphp


                                                                        <table class="table table-bordered">
                                                                            <thead style="font-size: 12px;">
                                                                                <th>Item</th>
                                                                                <th>Qty</th>
                                                                            </thead>

                                                                            <tbody style="font-size: 12px;">
                                                                                @foreach ($product_bundling_detail as $prd)
                                                                                    @if ($product->bundling_code == $prd->bundling_code)
                                                                                        @if ($prd->stock_available <= 0)
                                                                                            @php
                                                                                                $stockHabis = true;
                                                                                                $produkHabis[] =
                                                                                                    $prd->product_name;
                                                                                            @endphp
                                                                                        @endif
                                                                                        <tr>
                                                                                            <td>{{ $prd->product_name }}
                                                                                            </td>
                                                                                            <td>{{ $prd->quantity }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div style="margin-bottom:10px;"
                                                                        class="info-stockempty">
                                                                        @if ($stockHabis)
                                                                            @php
                                                                                $no = 1;
                                                                            @endphp
                                                                            <div style="font-size: 13px;display:block;"
                                                                                class="alert alert-danger">
                                                                                <span>Produk habis:</span>
                                                                                <div class="product-empty-info">
                                                                                    @foreach ($produkHabis as $produk)
                                                                                        <li> {{ $no++ }}.
                                                                                            {{ $produk }}</li>
                                                                                    @endforeach
                                                                                </div>

                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                                <div class="btn-add-cart">
                                                                    <form class="form-general"
                                                                        action="{{ route('cart_add') }}"
                                                                        method="POST">
                                                                        @csrf

                                                                        <input type="text" name="product_image"
                                                                            value="{{ $product->images }}" hidden>

                                                                        <input type="hidden" name="bundling"
                                                                            value="{{ $product->bundling_code }}">
                                                                        <input name="bundling_name" type="hidden"
                                                                            value="{{ $product->bundling_name }}">

                                                                        <input type="hidden" name="stock_available"
                                                                            value="{{ $product->quantity }}">

                                                                        <input type="hidden" name="price"
                                                                            value="{{ $product->price }}">

                                                                        {{-- <input type="text" name=""> --}}

                                                                        @if ($product->quantity)
                                                                            @if ($stockHabis)
                                                                                <button style="width:100%;"
                                                                                    class="btn btn-secondary"
                                                                                    type="button">Kosong</button>
                                                                            @else
                                                                                <button class="btn-general"
                                                                                    type="submit"><span
                                                                                        class="btn-text">Tambah</span>
                                                                                    <span
                                                                                        class="spinner"></span></button>
                                                                            @endif
                                                                        @else
                                                                            <button style="width:100%;"
                                                                                class="btn btn-secondary"
                                                                                type="button">Kosong</button>
                                                                        @endif
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div style="height: 50vh; display:flex; justify-content:center;border:1px solid gray;border-radius:10px;"
                                                class="empty-transaction">

                                                <div style="display: flex;" class="empty-content">
                                                    <div style="display: flex; gap:20px;margin:auto;">
                                                        <img width="70" height="70"
                                                            src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                            alt="">
                                                        <div style="display: block;align-self: center;"
                                                            class="text-content">
                                                            <h3>Promo Bundling belum ada</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>



                                    {{-- TAB PER CATEGORY --}}


                                    @foreach ($category_data as $ctg)
                                        @php
                                            $filtered_products = $all_products->where('category', $ctg->category_name);
                                        @endphp
                                        <div class="tab-pane fade" id="tab-{{ $ctg->category_name }}"
                                            role="tabpanel" aria-labelledby="tab-{{ $ctg->category_name }}-tab">
                                            @if ($filtered_products->isNotEmpty())
                                                <div class="card-body">
                                                    <div class="products-card"
                                                        style="display: flex; flex-wrap: wrap; gap: 20px;">

                                                        @foreach ($filtered_products as $product)
                                                            @php
                                                                $products_images = DB::table('product_images')
                                                                    ->where('product_code', $product->product_code)
                                                                    ->first();
                                                            @endphp
                                                            <div style="position: left;height:max-content;"
                                                                class="card" style="width: 200px;">
                                                                @if ($product->product_code == $products_images->product_code)
                                                                    <img class="card-img"
                                                                        src="{{ asset('storage/' . $products_images->images) }}"
                                                                        alt="">
                                                                @else
                                                                @endif
                                                                <p><strong>{{ $product->product }}</strong>
                                                                </p>

                                                                @if ($product->category)
                                                                    <div style="display: flex; gap:6px;"
                                                                        class="category-class">
                                                                        <small
                                                                            class="category">{{ $product->category }}</small>
                                                                        @if ($product->variant_type)
                                                                            &dot;
                                                                            <small
                                                                                class="text-info">{{ $product->variant_type }}</small>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                @endif

                                                                @if ($product->variant_code == null)
                                                                    @if ($product->price_after_discount)
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                            </p>
                                                                            <small
                                                                                class="discount">{{ '-' . $product->discount . '%' }}</small>
                                                                        </div>
                                                                    @else
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->price) }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div id="availableStock" class="stok">
                                                                        <p>Stok:
                                                                            <span>
                                                                                {{ $product->stock_available }}</span>
                                                                        </p>
                                                                        {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                    </div>
                                                                @else
                                                                    @if ($product->variant_price_after_discount)
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->variant_price_after_discount) }}
                                                                            </p>
                                                                            <small
                                                                                class="discount">{{ '-' . $product->variant_discount . '%' }}</small>
                                                                        </div>
                                                                    @else
                                                                        <div class="price">
                                                                            <p>{{ 'Rp.' . number_format($product->variant_price) }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div id="availableStock" class="stok">
                                                                        <p>Stok:
                                                                            <span>{{ $product->stock_available }}</span>
                                                                        </p>
                                                                        {{-- <p>Terjual:
                                                                            {{ $product->sold }}
                                                                        </p> --}}
                                                                    </div>
                                                                @endif



                                                                <div class="btn-add-cart">
                                                                    <form class="form-general"
                                                                        action="{{ route('cart_add') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @if ($product->product_code == $products_images->product_code)
                                                                            <input type="text" name="product_image"
                                                                                value="{{ $products_images->images }}"
                                                                                hidden>
                                                                        @endif
                                                                        <input type="hidden" name="product"
                                                                            value="{{ $product->product_code }}">
                                                                        <input type="hidden" name="variant"
                                                                            value="{{ $product->variant_code }}">
                                                                        <input type="hidden" name="variant_type"
                                                                            value="{{ $product->variant_type }}">
                                                                        <input type="hidden" name="product_name"
                                                                            value="{{ $product->product }}">
                                                                        <input type="hidden" name="stock_available"
                                                                            value="{{ $product->stock_available }}">

                                                                        @if ($product->variant_code)
                                                                            @if ($product->discount)
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->variant_price_after_discount }}">
                                                                            @else
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->variant_price }}">
                                                                            @endif
                                                                        @else
                                                                            @if ($product->discount)
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price_after_discount }}">
                                                                            @else
                                                                                <input type="hidden" name="price"
                                                                                    value="{{ $product->price }}">
                                                                            @endif
                                                                        @endif
                                                                        @if ($product->stock_available)
                                                                            <button class="btn-general"
                                                                                type="submit"><span
                                                                                    class="btn-text">Tambah</span>
                                                                                <span class="spinner"></span></button>
                                                                        @else
                                                                            <button style="width:100%;"
                                                                                class="btn btn-secondary"
                                                                                type="button">Kosong</button>
                                                                        @endif
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center;border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;align-self: center;"
                                                                class="text-content">
                                                                <h3>Produk belum ada</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div class="pagination">
                                        {{ $all_products->links() }}
                                    </div>


                                </div>
                            </div>


                            {{-- transaction-card --}}
                            <div class="transaction-card">
                                <div class="title-action-close">
                                    <h6><strong>Keranjang Belanja</strong></h6>
                                    <div style="display: flex; gap:30px;" class="btn-action">

                                        @if ($cart_value)
                                            <a style="color:black;" href="#" data-toggle="modal"
                                                data-target="#showDeleteCart"><i class="fa fa-trash"
                                                    aria-hidden="true"></i></a>
                                        @endif
                                        <a style="color:black;" id="closeBtn" href="#">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>

                                <hr>

                                <div wire:ignore class="modal fade" id="showDeleteCart" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Bersihkan Keranjang
                                                    Belanja
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">Apakah anda yakin ingin membersihkan keranjang
                                                belanja ?</div>
                                            <div class="modal-footer">
                                                <form id="formGeneral" action="{{ route('clear_cart') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button class="btn-general" id="btnGeneral" type="submit"
                                                        class="btn btn-primary"><span class="btn-text">Bersihkan
                                                            Keranjang</span>
                                                        <span class="spinner"></span></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Daftar barang di keranjang -->
                                <form class="form-transaction" action="{{ route('transaction.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="cart-items">
                                        @if ($cart_value)
                                            @foreach ($cart_value as $cart)
                                                @php
                                                    $isBundling = !empty($cart['bundling']);
                                                    $code = !empty($cart['bundling'])
                                                        ? $cart['bundling']
                                                        : $cart['product'];

                                                @endphp
                                                <div class="cart-item">
                                                    <div style="justify-content: space-between;"
                                                        class="container-content-product">

                                                        <!-- Product Details -->
                                                        <div class="sub-container-product"
                                                            style="margin-bottom: 10px;">
                                                            @if ($isBundling)
                                                                <div style="display:flex; justify-content: space-between;"
                                                                    class="flex-container">

                                                                    <div style="display:flex; gap:20px;"
                                                                        class="one-group-content">

                                                                        <div class="content-image">
                                                                            <img width="60" height="60"
                                                                                src="{{ asset('storage/' . $cart['product_image']) }}"
                                                                                alt="">
                                                                        </div>

                                                                        <div class="one-group">
                                                                            <span style="background: #bb0239;"
                                                                                class="badge">Bundling</span>
                                                                            <p style="margin-bottom: 0;"
                                                                                class="item-name">
                                                                                {{ $cart['bundling_name'] }}</p>

                                                                            <!-- Product Price and Quantity -->
                                                                            <div class="flex-content"
                                                                                style="display: flex; justify-content: space-between;">
                                                                                <p class="item-price">
                                                                                    {{ 'Rp.' . number_format($cart['price']) }}
                                                                                </p>
                                                                            </div>

                                                                            <div class="detail-product">
                                                                                @foreach ($product_bundling_detail as $promo_products)
                                                                                    @if ($cart['bundling'] == $promo_products->bundling_code)
                                                                                        <ul>
                                                                                            <input name="product[]"
                                                                                                type="hidden"
                                                                                                value="{{ $promo_products->product_code }}">
                                                                                            <input hidden
                                                                                                class="add_qty_bundling"
                                                                                                name="bundle_product_qty[]"
                                                                                                value="{{ $promo_products->quantity }}"
                                                                                                data-base-qty="{{ $promo_products->quantity }}">
                                                                                            <input hidden
                                                                                                type="text"
                                                                                                name="bundling_code[]"
                                                                                                value="{{ $promo_products->bundling_code }}">
                                                                                            <input hidden
                                                                                                type="text"
                                                                                                name="product_price[]"
                                                                                                value="{{ $promo_products->product_price }}">
                                                                                        </ul>
                                                                                    @endif
                                                                                @endforeach
                                                                                <input name="bundling" type="text"
                                                                                    value="{{ $cart['bundling'] }}"
                                                                                    hidden>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    {{-- add qty Bundling --}}
                                                                    <div style="display: flex; gap:10px;"
                                                                        class="btn-delete-product">

                                                                        <button type="button" class="text-danger"
                                                                            style="background:none;border:none;"
                                                                            onclick="event.preventDefault();document.getElementById('delete-{{ $code }}').submit();">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>

                                                                        <div class="product-item"
                                                                            data-stock="{{ $cart['stock_available'] }}">
                                                                            <div style="display: none;"
                                                                                class="stok">
                                                                                <p>Stok:
                                                                                    <span
                                                                                        class="available-stock">{{ $cart['stock_available'] }}</span>
                                                                                </p>
                                                                            </div>
                                                                            <!-- Quantity Control -->
                                                                            <small class="error-msg"
                                                                                style="color:red; display:none;font-size: 12px;"></small>
                                                                            <div class="quantity-container">
                                                                                <button type="button"
                                                                                    class="decrease-bundle">-</button>
                                                                                <input name="bundle_qty[]"
                                                                                    value="1" min="1"
                                                                                    type="number"
                                                                                    class="item-quantity">
                                                                                <button type="button"
                                                                                    class="increase-bundle">+</button>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div style="display:flex;justify-content: space-between;"
                                                                    class="one-group-content">


                                                                    <div style="display: flex; gap:20px;"
                                                                        class="group-content">
                                                                        <div class="content-image">
                                                                            <img width="60" height="60"
                                                                                src="{{ asset('storage/' . $cart['product_image']) }}"
                                                                                alt="">
                                                                        </div>
                                                                        <div class="one-group">


                                                                            <p style="margin-bottom: 0;"
                                                                                class="item-name">
                                                                                {{ $cart['product_name'] }}</p>
                                                                            <input name="product[]" type="hidden"
                                                                                value="{{ $cart['product'] }}">
                                                                            <input name="variant[]" type="hidden"
                                                                                value="{{ $cart['variant'] }}">
                                                                            <input type="hidden"
                                                                                name="product_price[]"
                                                                                value="{{ $cart['price'] }}">

                                                                            <small class="text-info"
                                                                                style="margin-bottom: 0;"
                                                                                class="item-price">
                                                                                {{ $cart['variant_type'] }}
                                                                            </small>

                                                                            <div class="flex-content"
                                                                                style="display: flex; justify-content: space-between;">
                                                                                <p class="item-price">
                                                                                    {{ 'Rp.' . number_format($cart['price']) }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div style="display:flex; justify-content: space-between;margin-bottom: 15px;"
                                                                        class="flex-container">

                                                                        <div style="display: flex; gap:10px;"
                                                                            class="btn-delete-product">

                                                                            <button type="button" class="text-danger"
                                                                                style="background:none;border:none;"
                                                                                onclick="event.preventDefault();document.getElementById('delete-{{ $code }}').submit();">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>

                                                                            <div class="product-item"
                                                                                data-stock="{{ $cart['stock_available'] }}">
                                                                                <div style="display: none;"
                                                                                    class="stok">
                                                                                    <p>Stok:
                                                                                        <span
                                                                                            class="available-stock">{{ $cart['stock_available'] }}</span>
                                                                                    </p>
                                                                                </div>
                                                                                <!-- Quantity Control -->
                                                                                <small class="error-msg"
                                                                                    style="color:red; display:none;font-size: 12px;"></small>
                                                                                <div class="quantity-container">
                                                                                    <button type="button"
                                                                                        class="decrease">-</button>
                                                                                    <input
                                                                                        name="quantity_per_product[]"
                                                                                        value="1" min="1"
                                                                                        type="number"
                                                                                        class="item-quantity">
                                                                                    <button type="button"
                                                                                        class="increase">+</button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Quantity and Delete Section -->


                                                    <hr class="hr-cart">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center-cart">
                                                <h6>Kosong</h6>
                                            </div>

                                        @endif
                                    </div>
                                    @if ($cart_value)
                                        <div class="category-scroll">
                                            <div class="customer-information">

                                                <!-- TAB CONTENT -->
                                                <div class="tab-content mt-3">
                                                    <small>*Harap isi jika pelanggan bagian dari Membership</small>
                                                    <div class="card-body">

                                                        <div class="customer-input">
                                                            <label><strong>Nama Pelanggan, Kode Pelanggan atau No.HP
                                                                    (Member Only)</strong></label>
                                                            <input id="search-customer" class="form-control"
                                                                type="text" autocomplete="off"
                                                                placeholder="Masukan Nama pelanggan, kode pelanggan atau no hp">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="showCustomer" class="show-customer">
                                                </div>
                                                <hr>
                                                <div style="display: flex;gap:10px;" class="button-qrcode">
                                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                                        data-target="#openQrCustomer"><i class="fa fa-qrcode"></i>
                                                        Open QR</a>
                                                    <button id="btn-remove-customer-code" class="btn btn-danger"
                                                        type="button"><i class="fa fa-trash"></i> Bersihkan
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <div id="form-voucher">
                                            <label for=""><strong>Masukan Kode Voucher</strong></label>
                                            <small>*Hapus kode voucher jika ingin hapus E-Voucher</small>
                                            <br>
                                            <input type="text" name="customer" readonly hidden>
                                            <input style="margin:0;" type="text" class="form-control"
                                                name="promo_code" placeholder="Masukan kode disini..."
                                                value="{{ old('promo_code') }}" id="promo_code_input">

                                            <input hidden style="margin:0;" type="text" class="form-control"
                                                name="code_voucher" placeholder="Masukan kode disini..."
                                                value="{{ old('code_voucher') }}" id="show-code-voucher">
                                            <br>

                                            <div class="btn-submit">
                                                <button id="btn-submit-check-result"
                                                    style="background-color: #212529;" class="btn btn-dark"
                                                    type="button">Pakai
                                                </button>
                                                <a class="btn btn-primary" href="#" data-toggle="modal"
                                                    data-target="#openQrVoucher"><i class="fa fa-qrcode"></i> Open
                                                    QR</a>

                                                <button id="btn-remove-voucher" class="btn btn-danger"
                                                    type="button"><i class="fa fa-trash"></i> Hapus Voucher
                                                </button>
                                            </div>
                                        </div>
                                        <hr style="border: none; border-top: 3px dashed black;">
                                        <div class="payment-method">
                                            <label for=""><strong>Metode Bayar</strong></label>
                                            <div class="open-pay-method">
                                                <select class="form-control" name="payment_type" id="paymentType"
                                                    required>
                                                    <option value="">=== Pilih Metode Bayar ===</option>
                                                    @foreach ($payment_type as $pay)
                                                        <option value="{{ $pay->id }}">
                                                            {{ $pay->payment_category }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <hr>
                                        <div id="showPaymentAmount" class="payment-amount">
                                            <div style="margin-bottom: 10px;" class="amount">
                                                <label for=""><strong>Bayar</strong></label>
                                                <small>*Hanya berlaku untuk jenis pembayaran Cash/Tunai</small>
                                                <input name="total_amount" id="amount" class="form-control"
                                                    type="number" autocomplete="off">
                                                <div id="amount-error" style="color:red; margin-top:5px;"></div>
                                            </div>

                                            <div class="payment-changes">
                                                <label for=""><strong>Kembalian</strong></label>
                                                <input id="paychange" name="payment_changes" class="form-control"
                                                    type="number" readonly>
                                            </div>
                                        </div>

                                        <hr>
                                        <p><strong>Informasi Pembayaran</strong></p>

                                        <!-- Subtotal dan Total -->
                                        <div class="totals">

                                            <hr>
                                            <div class="content-total">
                                                <span class="title-total">Bayar: </span>
                                                <span id="display-paychange"
                                                    class="paychange">{{ 'Rp.' }}</span>
                                            </div>
                                            <div class="content-total">
                                                <span class="title-total">Kembalian: </span>
                                                <span id="display-change"
                                                    class="paychange">{{ 'Rp.' }}</span>
                                            </div>
                                            <hr>
                                            <div class="content-total">
                                                <span class="title-total">Total items : </span>
                                                <span id="total-quantity">0</span>
                                                <input value="0" type="text" id="total-quantity-result"
                                                    hidden>

                                            </div>

                                            <div class="content-total">
                                                <span class="title-total">E-Voucher :</span>

                                                <div id="show-nominal" class="form-group">
                                                    <div class="info-first"></div>
                                                </div>
                                            </div>

                                            <div class="content-total">
                                                <span class="title-total">Potongan :</span>

                                                <div id="show-voucher-code" class="form-group">
                                                    <div class="info-second"></div>
                                                </div>
                                            </div>

                                            <div class="content-total">
                                                <span class="title-total">Sub Total: </span>
                                                <span class="sub-total" id="subtotal">
                                                </span>
                                                <input value="0" type="text" name="subtotal"
                                                    id="total-input-subtotal" hidden>
                                            </div>


                                            <div class="content-total">
                                                <span class="title-total">Grand Total: </span>
                                                <span class="subtotal" id="grandtotal">
                                                </span>
                                                <input value="0" type="text" name="grand_total"
                                                    id="total-input" hidden>
                                            </div>
                                        </div>

                                        <br>

                                        @if ($cart_value)
                                            <button type="submit" id="submitBtn" class="btn-general"><span
                                                    class="btn-text">Buat
                                                    Pesanan</span>
                                                <span class="spinner"></span></button>
                                        @else
                                            <button style="width: 100%;" id="submitBtn" type="button"
                                                class="btn btn-secondary">Buat
                                                Pesanan</button>
                                        @endif
                                </form>
                            @else
                    @endif


                    @foreach ($cart_value as $cart)
                        @php
                            $code = !empty($cart['bundling']) ? $cart['bundling'] : $cart['product'];
                        @endphp
                        <form class="form-delete" id="delete-{{ $code }}"
                            action="{{ route('delete_item_cart', $code) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>

                {{-- close dialog transaction card --}}
                <div class="close-dialog">
                    <h6><strong>Keranjang Belanja</strong></h6>
                    <a style="color: black;" id="btnShow" href="#">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </a>
                </div>


        </div>
        @endif
    </div>
    </main>


    <div style="z-index: 999999;" class="modal fade" id="openQrCustomer" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-qrcode"></i> QR Pelanggan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video id="preview-qrcodecustomer" width="300" height="300" autoplay></video>
                </div>
                <div class="modal-footer">
                    <button id="btn-delete-general" type="button" data-dismiss="modal"
                        class="btn-general-delete"><span class="btn-text">Tutup</span>
                        <span class="spinner"></span></button>
                </div>
            </div>
        </div>
    </div>

    {{-- OPEN QR for Vouchers --}}

    <div style="z-index: 999999;" class="modal fade" id="openQrVoucher" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-qrcode"></i> QR E-Voucher</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video id="preview-qrvoucher" width="300" height="300" autoplay playsinline></video>
                </div>
                <div class="modal-footer">
                    <button id="btn-delete-general" type="button" data-dismiss="modal"
                        class="btn-general-delete"><span class="btn-text">Tutup</span>
                        <span class="spinner"></span></button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<script src="{{ asset('assets/front_end/js/main/transaction.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>


{{-- Script in Main POS Transaction --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatCurrency(amount) {
            return "Rp. " + amount.toLocaleString('id-ID');
        }

        const bundlingQtyInputs = document.querySelectorAll('.add_qty_bundling');
        const quantityInputs = document.querySelectorAll('.item-quantity');
        const increaseButtons = document.querySelectorAll('.increase');
        const decreaseButtons = document.querySelectorAll('.decrease');
        const increaseButtonsBundle = document.querySelectorAll('.increase-bundle');
        const decreaseButtonsBundle = document.querySelectorAll('.decrease-bundle');
        const priceElements = document.querySelectorAll('.item-price');

        const totalQuantitySpan = document.getElementById('total-quantity');
        const qtyResult = document.getElementById('total-quantity-result');

        const grandTotalSpan = document.getElementById('grandtotal');
        const subTotalSpan = document.getElementById('subtotal');
        const totalResult = document.getElementById('total-input');
        const totalResultSubtotal = document.getElementById('total-input-subtotal');

        const amountInput = document.getElementById('amount');
        const displayPayChange = document.getElementById('display-paychange');
        const displayChange = document.getElementById('display-change');
        const payChangeInput = document.getElementById('paychange');
        let voucher = {
            type: null,
            value: 0
        }; // Global voucher object

        // Function to get item price
        function getPrice(index) {
            return parseFloat(priceElements[index].textContent.replace(/\D/g, '')) || 0;
        }

        // Update quantity total
        function updateTotalQuantity() {
            let totalQty = 0;
            quantityInputs.forEach(input => {
                totalQty += parseInt(input.value) || 0;
            });
            totalQuantitySpan.textContent = totalQty;
            qtyResult.value = totalQty;
        }

        // Update Grand Total calculation
        function updateGrandTotal() {
            let total = 0;

            // Calculate total based on quantity and price
            quantityInputs.forEach((input, index) => {
                total += (parseInt(input.value) || 0) * getPrice(index);
            });

            // Simpan subtotal
            totalResultSubtotal.value = total;

            // Tampilkan subtotal
            subTotalSpan.innerText = formatCurrency(total);

            let discount = 0;
            if (voucher.type === 'percent') {
                discount = total * (voucher.value / 100);
            } else if (voucher.type === 'nominal') {
                discount = voucher.value;
            }



            let grandTotal = Math.max(0, total - discount);

            // Update the display
            grandTotalSpan.innerText = formatCurrency(grandTotal);
            totalResult.value = grandTotal;

            updateChange(); // Update change display
        }

        // Update change calculation
        function updateChange() {
            const paid = parseFloat(amountInput.value.replace(/\D/g, '')) || 0;
            const total = parseFloat(totalResult.value) || 0;
            const change = paid - total;

            displayPayChange.textContent = formatCurrency(paid);
            displayChange.textContent = change >= 0 ? formatCurrency(change) : "Rp. 0";
            payChangeInput.value = change >= 0 ? change : 0;
        }

        const items = document.querySelectorAll('.product-item');

        quantityInputs.forEach((input, index) => {

            let item = input.closest('.product-item');
            let stock = parseInt(item.getAttribute('data-stock')) || 0;
            let errorEl = item.querySelector('.error-msg');

            function validate_stock() {
                let qty = parseInt(input.value) || 0;

                if (qty > stock) {
                    errorEl.style.display = "block";
                    errorEl.innerText = "Qty melebihi stok (" + stock + ")";
                    input.style.border = "2px solid red";
                } else if (qty < 1) {
                    errorEl.style.display = "block";
                    errorEl.innerText = "Minimal 1";
                    input.style.border = "2px solid red";
                } else {
                    errorEl.style.display = "none";
                    input.style.border = "";
                }

                checkAllValidity();
            }

            input.addEventListener('input', () => {
                if (parseInt(input.value) < 1 || isNaN(input.value)) {
                    input.value = 1;
                }

                validate_stock();
                updateTotalQuantity();
                updateGrandTotal();
            });

        });

        function checkAllValidity() {

            let isInvalid = false;

            document.querySelectorAll('.product-item').forEach(function(item) {

                let input = item.querySelector('.item-quantity');
                let stock = parseInt(item.getAttribute('data-stock')) || 0;
                let qty = parseInt(input.value) || 0;

                if (qty > stock || qty < 1) {
                    isInvalid = true;
                }
            });

            let btn = document.getElementById('submitBtn');

            if (btn) {
                if (isInvalid) {
                    btn.classList.remove('btn-general');
                    btn.classList.add('btn', 'btn-general-secondary');
                    btn.disabled = true;
                } else {
                    btn.classList.remove('btn', 'btn-general-secondary');
                    btn.classList.add('btn-general');
                    btn.disabled = false;
                }
            }
        }

        // 🔥 trigger awal
        checkAllValidity();

        // Handling quantity increase and decrease
        increaseButtonsBundle.forEach(btn => {
            btn.addEventListener('click', () => {

                const cartItem = btn.closest('.cart-item');

                const qtyInput = cartItem.querySelector('.item-quantity');
                qtyInput.value = (parseInt(qtyInput.value) || 0) + 1;

                const bundleQty = parseInt(qtyInput.value);

                cartItem.querySelectorAll('.add_qty_bundling').forEach(input => {
                    const baseQty = parseInt(input.dataset.baseQty);
                    input.value = baseQty * bundleQty;
                });

                qtyInput.dispatchEvent(new Event('input'));
            });
        });

        decreaseButtonsBundle.forEach(btn => {
            btn.addEventListener('click', () => {

                const cartItem = btn.closest('.cart-item');

                const qtyInput = cartItem.querySelector('.item-quantity');

                let current = parseInt(qtyInput.value) || 1;

                if (current > 1) {

                    qtyInput.value = current - 1;

                    const bundleQty = parseInt(qtyInput.value);

                    cartItem.querySelectorAll('.add_qty_bundling').forEach(input => {
                        const baseQty = parseInt(input.dataset.baseQty);
                        input.value = baseQty * bundleQty;
                    });

                    qtyInput.dispatchEvent(new Event('input'));
                }

            });
        });


        increaseButtons.forEach(btn => {
            btn.addEventListener('click', () => {

                const item = btn.closest('.product-item');
                const qtyInput = item.querySelector('.item-quantity');

                qtyInput.value = (parseInt(qtyInput.value) || 0) + 1;

                qtyInput.dispatchEvent(new Event('input'));
            });
        });

        decreaseButtons.forEach(btn => {
            btn.addEventListener('click', () => {

                const item = btn.closest('.product-item');
                const qtyInput = item.querySelector('.item-quantity');

                let current = parseInt(qtyInput.value) || 1;

                if (current > 1) {
                    qtyInput.value = current - 1;
                    qtyInput.dispatchEvent(new Event('input'));
                }

            });
        });

        amountInput.addEventListener('input', updateChange);

        // Function to handle applying voucher
        $('#btn-submit-check-result').on('click', function(e) {
            e.preventDefault();

            let promo_code = $('#promo_code_input').val();
            let customer = $('input[name="customer"]').val();

            if (!promo_code) return alert('Masukkan kode voucher!');

            $.ajax({
                url: '/show_promo_code',
                type: 'GET',
                data: {
                    promo_code,
                    customer
                },
                success: function(response) {
                    if (response.status === 'success' && response.data) {

                        $('#show-nominal').show();
                        $('#show-voucher-code').empty();
                        $('#show-code-voucher').val('');

                        // Reset voucher object
                        voucher.type = null;
                        voucher.value = 0;

                        if (response.data.voucher_code) {
                            $('#show-code-voucher').val(response.data.voucher_code)
                        }

                        // Set voucher based on response data
                        if (response.data.discount) {
                            voucher.type = 'percent';
                            voucher.value = response.data.discount;

                            $('#show-voucher-code').html(
                                `<span class="text-danger">-${response.data.discount}%</span>`
                            );
                        }

                        if (response.data.nominal) {
                            voucher.type = 'nominal';
                            voucher.value = response.data.nominal;

                            $('#show-voucher-code').html(
                                `<span class="text-danger">-${formatCurrency(response.data.nominal)}</span>`
                            );
                        }

                        // Update grand total after applying voucher
                        updateGrandTotal();
                    } else if (response.status === 'voucher_used') {
                        // Reset tampilan voucher
                        $('#show-nominal').hide();
                        $('#show-voucher-code').empty();

                        voucher.type = null;
                        voucher.value = 0;

                        // SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Voucher Invalid',
                            text: 'E-voucher sudah digunakan',
                            confirmButtonColor: '#d33',
                            didOpen: () => {
                                document.querySelector('.swal2-container').style
                                    .zIndex = '99999';
                            }
                        });
                    } else if (response.status === 'voucher_not_found') {
                        // Reset tampilan voucher
                        $('#show-nominal').hide();
                        $('#show-voucher-code').empty();

                        voucher.type = null;
                        voucher.value = 0;

                        // SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Voucher Invalid',
                            text: 'E-voucher tidak ada/tidak ditemukan',
                            confirmButtonColor: '#d33',
                            didOpen: () => {
                                document.querySelector('.swal2-container').style
                                    .zIndex = '99999';
                            }
                        });
                    } else if (response.status === 'voucher_not_matching') {
                        // Reset tampilan voucher
                        $('#show-nominal').hide();
                        $('#show-voucher-code').empty();

                        voucher.type = null;
                        voucher.value = 0;

                        // SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Voucher Invalid',
                            text: 'E-voucher Invalid/Not Matching',
                            confirmButtonColor: '#d33',
                            didOpen: () => {
                                document.querySelector('.swal2-container').style
                                    .zIndex = '99999';
                            }
                        });
                    } else if (response.status === 'voucher_expired') {
                        // Reset tampilan voucher
                        $('#show-nominal').hide();
                        $('#show-voucher-code').empty();

                        voucher.type = null;
                        voucher.value = 0;

                        // SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Voucher Expired',
                            text: 'E-voucher sudah Expired',
                            confirmButtonColor: '#d33',
                            didOpen: () => {
                                document.querySelector('.swal2-container').style
                                    .zIndex = '99999';
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        // Function to handle removing voucher
        $('#btn-remove-voucher').on('click', function() {
            voucher.type = null;
            voucher.value = 0;

            $('#promo_code_input').val('');
            $('#show-voucher-code').empty();
            $('#show-code-voucher').val('');
            $('#show-nominal').hide();

            // Update grand total after removing voucher
            updateGrandTotal();
        });

        // Initial call to update quantities and grand total
        updateTotalQuantity();
        updateGrandTotal();
    });

    $(document).ready(function() {
        $('#search-customer').on('keyup', function() {
            let keyword = $(this).val();

            if (keyword.length < 2) {
                $('#showCustomer').html('');
                return;
            }

            $.ajax({
                url: '/search_customer',
                type: 'GET',
                data: {
                    keyword: keyword
                },
                success: function(data) {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(function(customer) {
                            if (customer.status == 7)
                                html += `
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="padding:8px;">
                                                <strong>${customer.name} [${customer.email}]</strong>
                                            </th>
                                            <td style="text-align:center;">
                                                <input style="width:20px; height:20px;" class="customer-checkbox" name="customer" value="${customer.customer_code}" type="radio">
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            `;
                            else
                                html += `
                             <div>
                            <strong>${customer.name} [${customer.email}] &nbsp;
                               <span class="text-danger"> Tidak aktif</span></strong><br>
                            </div>
                            `;

                        });
                    } else {
                        html = '<div class="text-muted">Data tidak ditemukan</div>';
                    }

                    $('#showCustomer').html(html);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('#showCustomer').on('change', '.customer-checkbox', function() {
            let selectedCustomerCode = $(this).val(); // Ambil nilai email
            let customerInput = $('input[name="customer"]'); // Pilih input dengan name "customer"

            // Debugging: Cek apakah event change ter-trigger
            console.log('Checkbox changed:', selectedCustomerCode);

            // Jika checkbox dipilih, set nilai ke input customer
            if ($(this).is(':checked')) {
                customerInput.val(selectedCustomerCode);
                console.log('Customer selected:', selectedCustomerCode); // Debugging
            } else {
                // Jika checkbox tidak dipilih, kosongkan input
                customerInput.val('');
                console.log('Customer deselected'); // Debugging
            }
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        const paymentType = document.getElementById("paymentType");
        const showPaymentAmount = document.getElementById("showPaymentAmount");

        function togglePaymentAmount() {
            if (paymentType.value === "1") {
                showPaymentAmount.style.display = "block";
            } else {
                showPaymentAmount.style.display = "none";
            }
        }

        // jalankan saat pertama load (kalau ada value default)
        togglePaymentAmount();

        // jalankan saat select berubah
        paymentType.addEventListener("change", togglePaymentAmount);
    });


    // add bundling qty :
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const amountInput = document.getElementById('amount');
        const grandTotalElement = document.getElementById('grandtotal');
        const errorMessage = document.getElementById('amount-error');
        const submitBtn = document.getElementById('submitBtn');

        amountInput.addEventListener('input', function() {

            let amount = parseFloat(this.value) || 0;

            // Ambil nilai dari span grandtotal
            let grandTotal = parseFloat(
                grandTotalElement.textContent.replace(/[^0-9]/g, '')
            ) || 0;

            if (amount < grandTotal) {

                // Jika jumlah kurang dari grand total
                errorMessage.textContent =
                    "Jumlah harus sama dengan total: " + grandTotal.toLocaleString();

                this.classList.add('is-invalid');

                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-general');
                submitBtn.classList.add('btn-general-secondary');

            } else {

                errorMessage.textContent = "";
                this.classList.remove('is-invalid');

                // Aktifkan tombol pesan kembali
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled');
                submitBtn.classList.remove('btn-general-secondary');
                submitBtn.classList.add('btn-general');
            }

        });

    });
</script>


{{-- script for preview-qrcode voucher --}}
<script>
    let scanner = null;
    let scanned = false;

    function stopScanner() {
        if (scanner) {
            try {
                scanner.stop();
            } catch (e) {}
            scanner = null;
        }

        let video = document.getElementById('preview-qrvoucher');
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    }

    // FORCE CLOSE MODAL
    function forceCloseModal() {
        let modal = document.getElementById('openQrVoucher');

        $('#openQrVoucher').modal('hide');

        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');

        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }

    $('#openQrVoucher').on('hidden.bs.modal', function() {
        stopScanner();
    });

    $('#openQrVoucher').on('shown.bs.modal', function() {

        let video = document.getElementById('preview-qrvoucher');

        stopScanner();
        scanned = false;

        scanner = new Instascan.Scanner({
            video: video,
            scanPeriod: 2,
            mirror: false
        });

        scanner.addListener('scan', function(content) {

            if (scanned) return;
            scanned = true;

            console.log('QR:', content);

            let voucher = '';

            try {
                const data = JSON.parse(content);
                if (data && data.voucher_code) {
                    voucher = data.voucher_code;
                }
            } catch (e) {
                voucher = '';
            }

            // =========================
            // ❌ TIDAK ADA VOUCHER
            // =========================
            if (!voucher) {

                document.getElementById('promo_code_input').value = '';

                alert('Kode Voucher Tidak ada, silakan scan lagi');

                // 🔥 RESET SCAN SUPAYA BISA SCAN ULANG
                scanned = false;

                return; // ❗ CAMERA & MODAL TETAP JALAN
            }

            // =========================
            // ✔ VOUCHER VALID
            // =========================
            document.getElementById('promo_code_input').value = voucher;

            // STOP CAMERA + CLOSE MODAL
            setTimeout(() => {

                stopScanner();
                forceCloseModal();

            }, 50);

        });

        Instascan.Camera.getCameras()
            .then(function(cameras) {

                if (!cameras || cameras.length === 0) {
                    alert('Kamera tidak ditemukan');
                    return;
                }

                let selected = cameras[0];

                cameras.forEach(c => {
                    if (c.name && c.name.toLowerCase().includes('back')) {
                        selected = c;
                    }
                });

                return scanner.start(selected);
            })
            .catch(function(e) {
                console.error('Camera error:', e);
                alert('Gagal akses kamera: ' + e);
            });
    });


    $('#btn-remove-customer-code').on('click', function() {
        $('#search-customer').val('');
        $('#showCustomer').empty();
    });

    // Scanner for QR Cod Customer:

    let scannerx = null;
    let scannedz = false;

    function stopScanner() {
        if (scannerx) {
            try {
                scannerx.stop();
            } catch (e) {}
            scannerx = null;
        }

        const video = document.getElementById('preview-qrcodecustomer');
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(t => t.stop());
            video.srcObject = null;
        }
    }

    // SEARCH CUSTOMER (tetap sama)
    function searchCustomer(keyword) {
        if (keyword.length < 2) {
            $('#showCustomer').html('');
            return;
        }

        $.ajax({
            url: '/search_customer',
            type: 'GET',
            data: {
                keyword: keyword
            },
            success: function(data) {
                let html = '';

                if (data.length > 0) {
                    data.forEach(function(customer) {
                        if (customer.status == 7) {
                            html += `
                        <div>
                            <strong>${customer.name} [${customer.email}] &nbsp;
                            <input class="customer-checkbox" name="customer" value="${customer.customer_code}" type="radio">
                            Pilih</strong><br>
                            <small>Aktif</small>
                        </div>`;
                        } else {
                            html += `
                        <div>
                            <strong>${customer.name} [${customer.email}] &nbsp;
                            <span class="text-danger"> Tidak aktif</span></strong><br>
                        </div>`;
                        }
                    });
                } else {
                    html = '<div class="text-muted">Data tidak ditemukan</div>';
                }

                $('#showCustomer').html(html);
            }
        });
    }

    // RADIO SELECT
    $('#showCustomer').on('change', '.customer-checkbox', function() {
        let selectedCustomerCode = $(this).val();
        let customerInput = $('input[name="customer"]');

        customerInput.val($(this).is(':checked') ? selectedCustomerCode : '');
    });

    // MODAL CLEANUP ONLY
    $('#openQrCustomer').on('hidden.bs.modal', function() {
        stopScanner();
    });

    // OPEN MODAL
    $('#openQrCustomer').on('shown.bs.modal', function() {

        scannedz = false;

        const video = document.getElementById('preview-qrcodecustomer');

        stopScanner();

        setTimeout(() => {

            scannerx = new Instascan.Scanner({
                video: video,
                scanPeriod: 5,
                mirror: false
            });

            scannerx.addListener('scan', function(content) {

                if (scannedz) return;
                scannedz = true;

                console.log('QR:', content);

                let code = '';

                try {
                    const data = JSON.parse(content);

                    if (data && data.customer_code) {
                        code = data.customer_code;
                    }
                } catch (e) {
                    code = '';
                }

                // =========================
                // ❌ TIDAK ADA CUSTOMER CODE
                // =========================
                if (!code) {

                    alert('Customer Code tidak ada');

                    // 🔥 RESET SCAN FLAG supaya bisa scan lagi
                    scannedz = false;

                    return; // ❗ JANGAN STOP CAMERA / JANGAN CLOSE MODAL
                }

                // =========================
                // ✔ VALID CODE
                // =========================
                document.getElementById('search-customer').value = code;

                setTimeout(() => {
                    searchCustomer(code);
                }, 300);

            });

            Instascan.Camera.getCameras()
                .then(function(cameras) {

                    if (!cameras || cameras.length === 0) {
                        alert('Kamera tidak ditemukan / izin belum diberikan');
                        return;
                    }

                    let selected = cameras[0];

                    for (let c of cameras) {
                        if (c.name && c.name.toLowerCase().includes('back')) {
                            selected = c;
                        }
                    }

                    return scannerx.start(selected);
                })
                .catch(function(e) {
                    console.error('Camera error:', e);
                    alert('Tidak bisa akses kamera: ' + e.message);
                });

        }, 800);
    });
</script>



<style>
    .modal-body {
        display: flex;
        justify-content: center;
    }

    #preview-qrcodecustomer {
        display: flex;
        justify-content: center;
        border-radius: 10px;
        height: auto;
        border: 1px solid #ccc;
    }

    #preview-qrvoucher {
        display: flex;
        justify-content: center;
        border-radius: 10px;
        height: auto;
        border: 1px solid #ccc;
    }
</style>


@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 1000,
            showConfirmButton: false,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    </script>
@elseif(Session::has('add_cart_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('add_cart_success') }}",
            icon: 'success',
            timer: 1000,
            toast: true,
            position: 'bottom-left',
            showConfirmButton: false,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    </script>
@elseif(Session::has('failed_voucher'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_voucher') }}",
            icon: 'error',
            timer: 3000,
            showConfirmButton: true,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    </script>
@elseif (Session::has('success_empty_cart'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('success_empty_cart') }}",
            icon: 'success',
            timer: 1000,
            toast: true,
            position: 'bottom-left',
            showConfirmButton: false,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    </script>
@elseif(Session::has('failed_message'))
    <script>
        Swal.fire({
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 3000,
            showConfirmButton: true,
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '99999';
            }
        });
    </script>
@endif

</html>
