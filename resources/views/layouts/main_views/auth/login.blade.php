@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Login')
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
        <h2 style="font-weight: bold;font-size: 20px;">Login</h2>
    </div>

    <form id="formGeneral" method="POST" action="{{ route('login_execute') }}">
        @csrf

        <div class="form-group">
            <label>Email atau nomor handphone</label>
            <input type="text" name="login" value="{{ old('login') }}" placeholder="Masukan email anda atau no.hp"
                required autocomplete="off">
        </div>

        <div class="form-group">
            <label>Password</label>
            <div style="position: relative;">
                <input id="password" type="password" name="password" placeholder="Masukan password anda" required
                    autocomplete="off">
                <i class="fas fa-eye" id="togglePassword"
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                </i>
            </div>
        </div>

        <button id="btnGeneral" type="submit" class="btn-general"><span class="btn-text">Login</span>
            <span class="spinner"></span></button>
    </form>
    <script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
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


    <div class="link">
        <a href="{{ route('register_account') }}">Daftar akun</a>
    </div>

    <div class="link">
        <a style="text-decoration:underline;" href="{{ route('forgot-password-help') }}">Lupa password?</a>
    </div>


    <style>
        .alert {
            color: red;
            transition: opacity 0.5s ease-out;
        }
    </style>

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

        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500); // Menghapus elemen setelah fade out
                }, 4000); // Waktu tampilan alert, dalam milidetik (3 detik)
            }
        });
    </script>


@endsection
