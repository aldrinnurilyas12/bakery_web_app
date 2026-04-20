@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Login')
<link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
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
            <input type="password" name="password" placeholder="Masukan password anda" required autocomplete="off">
        </div>

        <button id="btnGeneral" type="submit" class="btn-general"><span class="btn-text">Login</span>
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

    <div class="link">
        <a href="{{ route('register_account') }}">Daftar akun</a>
    </div>
@endsection
