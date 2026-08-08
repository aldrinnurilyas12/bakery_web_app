<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah data Promo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Ubah Data Promo Campaign</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('promo_edit', $promo->promo_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @METHOD('PUT')

                        <div class="form-group">
                            <label><strong>Kode promo</strong></label>
                            <input type="text" name="promo_code" class="form-control"
                                value="{{ $promo->promo_code }}" autocomplete="off" readonly>
                            <x-input-error :messages="$errors->get('promo_code')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Nama promo</strong></label>
                            <input type="text" name="promo_name" class="form-control"
                                value="{{ $promo->promo_name }}" autocomplete="off">
                            <x-input-error :messages="$errors->get('promo_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Jumlah minimal transaksi</strong></label>
                            <input type="text" name="min_transaction" class="form-control"
                                value="{{ $promo->min_transaction }}" autocomplete="off">
                            <x-input-error :messages="$errors->get('min_transaction')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Deskripsi </strong></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="4">
                                {{ $promo->description }}
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Promo</strong></label>
                            <input type="number" name="quota" class="form-control" value="{{ $promo->quota }}"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('quota')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Status Promo</strong></label>
                            <select name="status" class="form-control" id="">
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $sts->id == $promo->status_id ? 'selected' : '' }}>
                                        {{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal promo</strong></label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', $promo->start_date ? $start_date->format('d M Y') : null) }}"
                                class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir promo</strong></label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', $promo->end_date ? $end_date->format('d M Y') : null) }}"
                                class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                        </div>
                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $promo->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $promo->updated_by ?: '-' }}"
                                readonly>
                        </div>
                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>




</html>
