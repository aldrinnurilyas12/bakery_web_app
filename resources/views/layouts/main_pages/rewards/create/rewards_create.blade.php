<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Promo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Tambah Data Rewards</h4>
                    <hr>
                    <form action="{{ route('master_rewards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan nama reward</strong></label>
                            <input type="text" name="rewards_name" class="form-control"
                                value="{{ old('rewards_name') }}" placeholder="Masukan nama rewards" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" autocomplete="off">
                        </div>


                        <div class="form-group">
                            <label><strong>Kuota Reward</strong></label>
                            <input type="text" name="quota" class="form-control" value="{{ old('quota') }}"
                                placeholder="Masukan kuota reward min : 2 " autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku rewards</strong></label>
                            <input type="date" name="start_date" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku reward</strong></label>
                            <input type="date" name="end_date" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Upload Foto/Gambar</strong></label>
                            <input type="file" name="images" class="form-control" autocomplete="off" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Rewards</button>
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
