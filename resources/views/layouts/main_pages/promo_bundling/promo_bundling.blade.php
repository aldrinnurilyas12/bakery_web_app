<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Promo Bundling</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                @php
                    $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
                    $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
                    $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);
                @endphp
                <div class="container-fluid px-4">
                    <br>

                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="title">
                                Master Data > Promo Bundling
                            </div>

                            <div style="display: flex;gap:10px;" class="flex-content">

                                @if ($bundling->isNotEmpty())
                                    @if (!$user_permission_forbidden)
                                        <div style="display: flex;gap:10px;" class="button-add-product">
                                            <a class="btn btn-primary" href="{{ route('promo_bundling_create') }}">Tambah Promo Bundling</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    

                        <div class="card-body">
                            <div wire:poll.keep.alive.2s>

                                @if ($bundling->isNotEmpty())
                                    <div style="display: flex; flex-wrap: wrap; gap:10px;">
                                        @foreach ($bundling as $bundl)
                                            <div class="card bg-light text-black mb-4">
                                                <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                                    class="card-body">
                                                    <div style="display: flex; gap:10px;" class="image-content">
                                                        <img width="90" height="90"
                                                            src="{{ url('storage/' . $bundl->images) }}" alt="">
                                                        <div class="content-text">
                                                            <div style="width: 200px;" class="title-text">
                                                                <h5 style="font-size:15px;">{{ $bundl->bundling_name }}</h5>
                                                            </div>
                                                            <p
                                                                style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px;">
                                                                Harga:
                                                                {{ "Rp." . number_format($bundl->price) }}
                                                                <br>
                                                                <span>Kuota:
                                                                    {{ $bundl->quantity }}</span>
                                                                &nbsp;
                                                                <span>Available:
                                                                    {{ $bundl->total_available }}
                                                                    </span>
                                                                &nbsp;
                                                                <span>Redeem:
                                                                    {{ $bundl->total_redeem }}</span>
                                                            </p>

                                                            <div class="product-detaio">
                                                                <h5 style="font-size:15px;">Rincian Item:</h5>
                                                                <ul>
                                                                    @php
                                                                        $stockHabis = false;
                                                                    @endphp
                                                                    @foreach ($all_product as $product)
                                                                        @if($bundl->bundling_code == $product->bundling_code)

                                                                         @if($product->stock_available <= 0)
                                                                            @php
                                                                                $stockHabis = true;
                                                                            @endphp
                                                                         @endif
                                                                            <li style="font-weight: normal;font-size:13px;">{{ $product->quantity }}x {{ $product->product_name }}</li>
                                                                        @endif
                                                                    @endforeach
                                                                    
                                                                </ul>
                                                            </div>






                                                            <div style="font-size: 13px; font-weight: 500;margin-bottom: 0;"
                                                                class="status">
                                                                @if ($bundl->status == 7)
                                                                    <p style="margin-bottom: 0;">Status: <span
                                                                            class="text-success">Aktif</span></p>
                                                                @else
                                                                    <p style="margin-bottom: 0;">Status: <span
                                                                            class="text-danger">Tidak aktif</span></p>
                                                                @endif
                                                            </div>

                                                            <div style="font-size: 13px; font-weight: 500;" class="date">
                                                                <label for="">Tanggal Berlaku</label>
                                                                <br>
                                                                <small>{{ \Carbon\Carbon::parse($bundl->start_date)->format('Y-m-d') }}</small>
                                                                <span>s.d</span>
                                                                <small>
                                                                    {{ \Carbon\Carbon::parse($bundl->end_date)->format('Y-m-d') }}</small>
                                                            </div>

                                                            @if($stockHabis)
                                                                    <div style="font-size: 13px;" class="alert alert-danger mt-2">
                                                                        <span>Produk ada yang habis</span>
                                                                        <br>
                                                                        <span>Promo bundling tidak dapat digunakan.</span>
                                                                    </div>
                                                                @endif

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-footer d-flex align-items-center justify-content-between">

                                                                @if ($bundl->status == 7)
                                                                    <a class="btn btn-danger" data-toggle="modal"
                                                                        data-target="#deleteModalRewards{{ $bundl->bundling_code }}">
                                                                        Nonaktifkan
                                                                    </a>
                                                                @else
                                                                    <a class="btn btn-primary" data-toggle="modal"
                                                                        data-target="#deleteModalRewards{{ $bundl->bundling_code }}">
                                                                        Aktifkan
                                                                    </a>
                                                                @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                        <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                            <div>
                                                <h3>Belum ada Promo Bundling</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary" href="{{ 'promo_bundling_create' }}">Tambah Promo Bundling</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

     @foreach ($bundling as $bundl)
        <div wire:ignore class="modal fade"
            id="deleteModalRewards{{ $bundl->bundling_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $bundl->bundling_code }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Perbarui Status Promo Bundling</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($bundl->status == 7)
                            Apakah anda yakin ingin Nonaktifkan Promo Bundling:
                            {{ $bundl->bundling_name }}
                            ?
                        @else
                            Apakah anda yakin ingin aktifkan Promo Bundling
                            {{ $bundl->bundling_name }}
                            ?
                        @endif
                        <br>
                        <br>
                        <form method="POST"
                            action="{{ route('bundling_nonactive',$bundl->bundling_code) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                @if ($bundl->status == 7)
                                    <input type="checkbox" name="status" value="8">
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <label for="">Ya, Nonaktifkan</label>
                                @else
                                    <input type="checkbox" name="status" value="7">
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <label for="">Ya, Aktifkan</label>
                                @endif
                            </div>
                            <br>

                            @if ($bundl->status == 7)
                                <button class="btn btn-danger" type="submit">Nonaktifkan</button>
                            @else
                                <button class="btn btn-primary" type="submit">Aktifkan</button>
                            @endif

                        </form>
                    </div>

                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>

   

</script>

<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

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




