<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Histori Transaksi</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome Free 6 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/star-rating.css') }}">
    <script src="{{ url('assets/front_end/js/star-rating.js') }}"></script>
</head>

<body>
    <div class="main-container">

        <div class="container-fluid">
            <div class="container">
                <br>
                <br>
                <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                    <div class="route-back">
                        <a href="{{ route('profile-menu') }}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                    <h4 style="font-size: 20px;margin-bottom:0;"><strong>Riwayat Transaksi</strong></h4>
                </div>

                <hr class="hr-menu">

                <div class="grid" role="tablist">

                    <!-- TAB SEMUA -->
                    <a class="card-category active" data-bs-toggle="tab" href="#tab-history" role="tab">
                        <i class="fa fa-history"></i>
                        <p class="product-category-show">Transaksi</p>
                    </a>
                    <a class="card-category" data-bs-toggle="tab" href="#tab-insight" role="tab">
                        <i class="fa fa-line-chart"></i>
                        <p class="product-category-show">Insight</p>
                    </a>


                </div>

                <hr class="hr-menu">
                <div class="tab-content">
                    {{-- Tab all products --}}
                    <div class="tab-pane fade show active" id="tab-history" role="tabpanel">
                        <div style="height: 400px;overflow-y: auto;" class="container-products">
                            <div class="menu-list">
                                @if ($history_transaction->isNotEmpty())
                                    @foreach ($history_transaction as $history)
                                        @php
                                            $review_available = DB::table('product_reviews')
                                                ->where('transaction', $history->transaction_code)
                                                ->first();
                                        @endphp
                                        <div style="display: flex; justify-content: space-between;align-items: center;"
                                            class="group-menu-date">
                                            <p class="text-invoice"><i style="color:gray;" class="fas fa-receipt"></i>
                                                <a href="{{ route('invoice', $history->transaction_code) }}">
                                                    {{ $history->transaction_code }}
                                                    &nbsp; <i class="fas fa-external-link-alt"></i></a>
                                            </p>

                                            <p class="order-date">
                                                <small>{{ \Carbon\Carbon::parse($history->transaction_date)->format('d M Y') }}</small>
                                            </p>

                                        </div>

                                        <div style="margin-bottom: 10px;" class="grand-total">
                                            <p>Total items :
                                                {{ $history->total_qty }}</p>
                                            <p>Total belanja :
                                                {{ 'Rp.' . number_format($history->grand_total) }}</p>
                                        </div>

                                        <div style="display:flex; justify-content: right;" class="date-reviews">

                                            @if (!$review_available)
                                                <a style="width:110px;" class="btn-general" href="#"
                                                    data-toggle="modal"
                                                    data-target="#showReview{{ $history->transaction_code }}">Beri
                                                    ulasan</a>
                                            @endif

                                        </div>

                                        <hr class="hr-menu">
                                    @endforeach
                                @else
                                    <p style="text-align: center;margin:0 auto;">Tidak ada transaksi.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-insight" role="tabpanel">
                    <div class="container-products">
                        @if ($history_transaction->isNotEmpty())
                            <div class="row">
                                <!-- Line Chart -->
                                <div class="col-md-12">
                                    <div style="width: 100%;" class="card-chart">
                                        <div class="card-header">Total Belanja Anda di Store kami</div>
                                        <div class="card-body">
                                            <canvas id="insightChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p style="text-align: center;margin:0 auto;">Tidak ada transaksi.</p>
                        @endif
                    </div>


                </div>



            </div>

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

            <div class="modal fade" id="logout" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div style="display: flex; justify-content: space-between;" class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Keluar</h5>
                            <button style="width: 10px; height:10px;justify-items: center;background:none;"
                                type="button" data-dismiss="modal" aria-label="Close">
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

            @include('layouts.main_views.components.bottom_nav')

        </div>
    </div>


    @foreach ($history_transaction as $history)
        <div wire:ignore class="modal fade" id="showReview{{ $history->transaction_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $history->transaction_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Berikan Review & Rating Produk </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="form-review" action="{{ route('product_review_save', $history->transaction_code) }}"
                        method="POST">
                        @csrf
                        <div class="modal-body">


                            <div style="overflow-y: auto;" class="product-content-review">
                                @foreach ($products_detail as $prd)
                                    @if ($history->transaction_code == $prd->transaction_code)
                                        <div style="display: flex; gap:20px;" class="felx-content-image">
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    value="{{ $history->transaction_code }}"
                                                    name="transaction_code[]" hidden>
                                            </div>
                                            <div class="img-content">
                                                <img width="100" height="100"
                                                    src="{{ 'storage/' . $prd->images }}" alt="">
                                            </div>
                                            <div class="product-content">
                                                <input type="text" name="product_code[]"
                                                    value="{{ $prd->product }}" hidden>
                                                <input type="text" name="variant_code[]"
                                                    value="{{ $prd->variant }}" hidden>
                                            </div>

                                            <div style="display:block;" class="fill-content">
                                                <div class="form-group">
                                                    <label style="font-size:15px;" for=""><strong>Rating
                                                            Produk</strong></label>
                                                    <select name="rating[]" class="star-rating" required>
                                                        <option value="5">Bagus Sekali</option>
                                                        <option value="4">Bagus </option>
                                                        <option value="3">Normal</option>
                                                        <option value="2">Buruk</option>
                                                        <option value="1">Sangat Buruk</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label style="font-size:15px;" for=""><strong>Berikan
                                                            Ulasan</strong></label>
                                                    <textarea class="form-control" name="review[]" id="" cols="30" rows="2" required></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <hr style="border:0.8px solid rgb(217, 217, 217);">
                                    @endif
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label style="font-size:15px;" for=""><strong>Sembuyikan nama
                                        anda</strong></label>
                                <br>
                                <label for=""><small>*Nama anda tidak akan terlihat</small></label>
                                <div style="display:block;" class="block-content">
                                    <input type="radio" value="Y" name="hidden_name"> Ya
                                    <br>
                                    <input type="radio" value="N" name="hidden_name"> Tidak
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                    class="btn-text">Berikan ulasan & Rating</span>
                                <span class="spinner"></span></button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    @endforeach
</body>

@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@elseif (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif

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


<script>
    var stars = new StarRating('.star-rating');
</script>

<script>
    const ctx = document.getElementById('insightChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Total Transaksi Bulan Ini',
                data: @json($data),
                backgroundColor: [
                    '#bb0239'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</html>
