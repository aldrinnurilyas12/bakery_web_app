<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Profile</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
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
                        <p class="hello">
                            <span
                                class="session-name">{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->name }}</span>
                        </p>
                    @else
                        <p class="hello">Selamat datang </p>
                </div>
                @endif
                @if (auth()->guard('customer')->user())
                    <div class="center-element">
                        <div class="card-segment-profile">
                            <div class="point">
                                <p style="color:black;">Point</p>
                                @if ($customer->point)
                                    <div class="user-point">
                                        {{ $customer->point }}
                                    </div>
                                @else
                                    <div class="user-point">
                                        -
                                    </div>
                                @endif
                            </div>

                            <div class="point">
                                <p style="color:black;">E-Voucher</p>
                                @if ($customer->total_voucher)
                                    <div class="user-voucher">{{ $customer->total_voucher }}</div>
                                @else
                                    <div class="user-voucher">-</div>
                                @endif
                            </div>

                            <div class="point">
                                <p style="color:black;">Transaksi</p>
                                @if ($customer->transaction_total)
                                    <div class="user-transaction">{{ $customer->transaction_total }}</div>
                                @else
                                    <div class="user-transaction">-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <hr>
        <div class="container">
            <div class="container-fluid">

                <div class="qr-code">
                    <div class="show-qrcode">
                        <a class="terms" href="#" data-toggle="modal" data-target="#openqr">
                            <img src="{{ url('storage/' . $customer->qr_code) }}" alt="">
                        </a>
                    </div>
                </div>

                <h4 style="font-size: 1.6rem;"><strong>Profil Pengguna</strong></h4>
                <hr class="hr-menu">
                <form id="saveProfile" method="POST" action="{{ route('update_customer', $customer->customer_code) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label><strong>Nama</strong></label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
                        <x-input-error :messages="$errors->get('name')" class="text-danger" />
                    </div>

                    <div class="form-group">
                        <label><strong>Tanggal Lahir</strong></label>
                        <input class="form-control" type="date"
                            value="{{ old('birth_date', $customer->birth_date ? $birth_date->format('Y-m-d') : null) }}"
                            name="birth_date" autocomplete="off">
                        <x-input-error :messages="$errors->get('birth_date')" class="text-danger" />
                    </div>

                    <div class="form-group">
                        <label><strong>Alamat</strong></label>
                        <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
                        <x-input-error :messages="$errors->get('address')" class="text-danger" />
                    </div>


                    <div class="form-group">
                        <label><strong>No. Handphone</strong></label>
                        <input type="text" name="phone_number" class="form-control"
                            value="{{ $customer->phone_number }}">
                        <x-input-error :messages="$errors->get('phone_number')" class="text-danger" />
                    </div>

                    <div class="form-group">
                        <label><strong>Email</strong></label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}"
                            readonly>
                        <x-input-error :messages="$errors->get('email')" class="text-danger" />
                    </div>

                    <div class="form-group">
                        <label><strong>Tanggal Member</strong></label>
                        <input type="text" class="form-control"
                            value="{{ \Carbon\Carbon::parse($customer->member_date)->format('d F Y') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label><strong>Tanggal Buat Akun</strong></label>
                        <input type="text" class="form-control" value="{{ $customer->created_at }}" readonly>
                    </div>


                    <button id="btnProfile" type="submit" class="btn-general">Simpan Data
                        <span class="spinner"></span>
                    </button>
                </form>

                <br>
                <h4 style="font-size: 1.6rem;"><strong>Akun Pengguna</strong></h4>
                <hr class="hr-menu">

                <div class="delete-account">
                    <p class="delete-title">Hapus Akun Permanen</p>
                    <p class="delete-desc">
                        Tindakan ini akan menghapus akun Anda secara permanen dan tidak dapat dibatalkan.
                    </p>
                    <a href="#" data-toggle="modal" data-target="#delete_account" class="btn-general">Hapus
                        Akun</a>
                </div>
                <br>
                @include('layouts.main_views.components.bottom_nav')
            </div>
        </div>



    </div>


    <div class="modal fade" id="openqr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div style="display: flex; justify-content: space-between;" class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR CODE</h5>
                    <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                        data-dismiss="modal" aria-label="Close">
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
                            mendapatkan Point.</p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button style="background: #bb0239;border: none;" data-dismiss="modal" class="btn btn-primary"
                        aria-label="Close">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div style="display: flex; justify-content: space-between;" class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus akun pengguna Kencana Bakery</h5>
                    <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                        data-dismiss="modal" aria-label="Close">
                        <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"
                    style="height:max-content; overflow-y: auto; font-size: 14px; line-height: 1.6;">
                    <div style="height: max-content;padding:5px;display:block; font-family: Cambria;">
                        <p>
                            Apakah Anda yakin ingin menghapus akun?<br>
                            Tindakan ini <strong>tidak dapat dibatalkan</strong> dan seluruh data akan dihapus permanen.
                        </p>

                    </div>
                    <br>

                    <div class="form-nonactive-account">
                        <form id="deleteAccount" action="{{ route('nonactive_account', $customer->customer_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <button id="btndelete_account" class="btn-general" type="submit"
                                style="background:#bb0239;color:white; border:none; padding:10px;">
                                <span class="btn-text">Hapus akun
                                    saya</span>
                                <span class="spinner"></span></button>
                        </form>
                    </div>

                </div>

                <div class="modal-footer">
                    <button style="background: #bb0239;border: none;" data-dismiss="modal" class="btn btn-primary"
                        aria-label="Close">Batal</button>
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

</body>

<script>
    // button
    document.getElementById("saveProfile").addEventListener("submit", function() {
        const btn = document.getElementById("btnProfile");
        btn.classList.add("loading");
        btn.disabled = true;
    });


    // btn delete
    document.getElementById("deleteAccount").addEventListener("submit", function() {
        const btn = document.getElementById("btndelete_account");
        const text = btn.querySelector(".btn-text");

        btn.classList.add("loading");
        btn.disabled = true;
        text.textContent = "Processing...";
    });
</script>

<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
