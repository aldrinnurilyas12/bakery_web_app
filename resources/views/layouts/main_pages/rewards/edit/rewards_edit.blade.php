<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah data rewards</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakerylogo.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Ubah Data Reward</h4>
                    <hr>
                    <form action="{{ route('rewards_edit', $rewards->rewards_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @METHOD('PUT')

                        <div class="form-group">
                            <label><strong>Kode reward</strong></label>
                            <input type="text" name="rewards_code" class="form-control"
                                value="{{ $rewards->rewards_code }}" autocomplete="off" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Nama reward</strong></label>
                            <input type="text" name="rewards_name" class="form-control"
                                value="{{ $rewards->rewards_name }}" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Jumlah point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ $rewards->point }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Kuota Reward</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ $rewards->quota }}"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Status Reward</strong></label>
                            <select name="status" class="form-control" id="">
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $sts->id == $rewards->status ? 'selected' : '' }}>
                                        {{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku rewards</strong></label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', $rewards->start_date ? $start_date->format('Y-m-d') : null) }}"
                                class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku rewards</strong></label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', $rewards->end_date ? $end_date->format('Y-m-d') : null) }}"
                                class="form-control" autocomplete="off">
                        </div>
                        @if ($rewards->images == null)
                            <p>anda belum upload gambar</p>
                            <input type="file" name="images">
                        @else
                            <div class="form-group">
                                <label for=""><strong>Ubah Gambar/Foto</strong></label>
                                <br>
                                <img width="90" height="90" src="{{ url('storage/' . $rewards->images) }}"
                                    alt="">
                                <input type="file" name="images">
                            </div>
                        @endif
                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $rewards->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $rewards->updated_by ?: '-' }}"
                                readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Reward</button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>




</html>
