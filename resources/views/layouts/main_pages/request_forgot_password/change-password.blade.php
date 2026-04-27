@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Change Password')
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
        <h2 style="font-weight: bold;font-size: 20px;">Ubah kata sandi</h2>
    </div>

    <form id="formGeneral" method="POST" action="{{ route('change-password-proccess', request('email')) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <input type="text" hidden name="email" value="{{ request('email') }}" autocomplete="off" readonly>
            <input type="text" hidden name="otp" value="{{ request('otp') }}" autocomplete="off" readonly>
        </div>

        <div class="form-group">
            <label>Masukan kata sandi baru</label>
            <div style="position: relative;">
                <input id="password" type="password" name="password" value="{{ old('password') }}"
                    placeholder="Masukan kata sandi baru" autocomplete="off">
                <x-input-error :messages="$errors->get('password')" class="text-danger" />
                <i class="fas fa-eye" id="togglePassword"
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                </i>
            </div>
        </div>


        <div class="form-group">
            <label>Konfirmasi kata sandi</label>
            <div style="position: relative;">
                <input id="confirm_password" type="password" name="confirm_password" value="{{ old('confirm_password') }}"
                    placeholder="Konfirmasi kata sandi" autocomplete="off">
                <x-input-error :messages="$errors->get('confirm_password')" class="text-danger" />
                <i class="fas fa-eye" id="toggleConfirmPassword"
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                </i>
            </div>
        </div>

        <button id="btnGeneral" type="submit" class="btn-general"><span class="btn-text">Buat kata sandi</span>
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
        const toggle_confirm = document.getElementById('toggleConfirmPassword');
        const password = document.getElementById('password');
        const confirm_password = document.getElementById('confirm_password');

        toggle.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';

            password.setAttribute('type', type);

            // ganti icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });


        toggle_confirm.addEventListener('click', function() {
            const confirm_type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';

            confirm_password.setAttribute('type', confirm_type);

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
