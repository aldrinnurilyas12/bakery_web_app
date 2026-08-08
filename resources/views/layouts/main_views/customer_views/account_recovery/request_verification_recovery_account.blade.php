@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Account Recovery')
<link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div style="display: flex; justify-content: center;" class="d-flex-center">
        <div class="img-logo">
            <img src="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}" alt="Kencana Bakery Logo"
                class="logo" />
        </div>
    </div>
    <div style="display: flex; justify-content: left;font-weight: bold;" class="d-flex-center">
        <h2 style="font-weight: bold;font-size: 20px;">Verifikasi Pemulihan Akun Anda</h2>
    </div>

    <div class="p-info">
        <p><small>Link Pemulihan akun hanya aktif selama 5 menit mohon segera pulihkan akun</small></p>
    </div>

    <form id="formGeneral" method="POST"
        action="{{ route('recovery_account_verification_save', $customer_data->token_link) }}">
        @csrf
        @method('PUT')
        <input type="text" value="{{ $customer_data->token_link }}" name="token_link" hidden>
        <input type="text" value="{{ $customer_data->email }}" name="email" hidden>
        <button id="btnGeneral" type="submit" class="btn-general"><span class="btn-text">Pulihkan Akun</span>
            <span class="spinner"></span></button>
    </form>
    <script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

    @if ($errors->get('login'))
        <div class="alert alert-warning">
            <x-input-error :messages="$errors->get('login')" />
        </div>
    @elseif($errors->get('password'))
        <div class="alert alert-warning">
            <x-input-error :messages="$errors->get('password')" />
        </div>
    @endif



    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // ganti icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

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
@endsection
