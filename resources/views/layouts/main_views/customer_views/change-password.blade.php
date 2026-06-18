<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery | Ubah kata sandi</title>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/homepage.css') }}">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Free 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">

        <div class="container-fluid">
            <div class="container">
                <br>
                <br>
                <div style="display: flex; gap:10px;align-items:center;" class="title-content-head">
                    <a style="color:black;" href="{{ route('profile-menu') }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <h4 style="font-size: 20px;"><strong>Ubah kata sandi</strong></h4>
                </div>

                <div class="menu-list">
                    <hr class="hr-menu">
                    <form id="changePassword" action="{{ route('update_password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for=""><strong>Alamat email anda</strong></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="Masukan alamat email anda" autocomplete="off" required>
                            <x-input-error :messages="$errors->get('email')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Kata sandi baru </strong></label>
                            <div style="position: relative;">
                                <input id="password" type="password" name="password" class="form-control"
                                    placeholder="Buat kata sandi baru" required>
                                <x-input-error :messages="$errors->get('password')" class="text-danger" />
                                <i class="fas fa-eye" id="togglePassword"
                                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                </i>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for=""><strong>Konfirmasi kata sandi </strong></label>
                            <div style="position: relative;">
                                <input id="confirm_password" type="password" name="confirm_password" class="form-control"
                                    placeholder="Konfirmasi kata sandi" required>
                                 <x-input-error :messages="$errors->get('confirm_password')" class="text-danger" />
                                <i class="fas fa-eye" id="toggleConfirmPassword"
                                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                                </i>
                            </div>
                        </div>

                        <button id="btnResetPassword" type="submit" class="btn-general">
                            Ubah kata sandi
                            <span class="spinner"></span>
                        </button>

                    </form>


                    <form id="logout-form" action="{{ route('logout_account') }}" method="POST" class="d-none">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>


        </div>



    </div>
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
                                <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi dan anda
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

    @include('layouts.main_views.components.bottom_nav')

    </div>

    {{-- logout --}}
    {{-- <div class="modal fade" id="openqr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <p class="info-point">*Tunjukan QR Code ini kepada kasir saat transaksi dan anda akan mendapatkan Point.</p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" aria-label="Close">Tutup</button>
                </div>
            </div>
        </div>
    </div> --}}

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
    document.getElementById("changePassword").addEventListener("submit", function() {
        const btn = document.getElementById("btnResetPassword");
        btn.classList.add("loading");
        btn.disabled = true;
    });

    const toggle = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');

    toggle.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // ganti icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener('click', function(){
        const type_confirm = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
        confirm_password.setAttribute('type', type_confirm);

        // ganti icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    })
</script>


<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</html>
