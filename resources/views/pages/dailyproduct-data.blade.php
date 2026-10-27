@extends('layouts.main')

@section('content')
    @livewire('daily-products')
    @php
        $nik = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $check_login_mode = DB::table('users')->where('nik', $nik)->value('login_mode');
    @endphp
    @if ($check_login_mode == 'TRX')
        @include('layouts.component_admin.sidebar.sidebar_transaction')
    @else
        @include('layouts.component_admin.sidebar.sidebar')
    @endif
@endsection
