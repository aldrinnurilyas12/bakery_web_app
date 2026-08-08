@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Maintenance Information')
<link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('layouts.component_admin.navbar.maintenance_info')


<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000
        });
    </script>
@elseif (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000
        });
    </script>
@endif



<style>
    .alert {
        color: red;
        transition: opacity 0.5s ease-out;
    }

    .auth-container {
        display: none;
    }
</style>
