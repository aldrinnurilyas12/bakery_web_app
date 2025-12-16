<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Bahan Baku</title>
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
                    <h4>Tambah Data Bahan Baku</h4>
                    <hr>
                    <form action="{{ route('master_material.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan Nama Bahan Baku</strong></label>
                            <input type="text" name="material_name" class="form-control"
                                value="{{ old('material_name') }}" placeholder="Masukan nama bahan baku"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan Harga Bahan Baku</strong></label>
                            <input type="text" name="price" class="form-control" value="{{ old('price') }}"
                                placeholder="Masukan harga" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan Stok</strong></label>
                            <input type="text" name="quantity" class="form-control" value="{{ old('quantity') }}"
                                placeholder="Masukan stok bahan baku" autocomplete="off">
                        </div>


                        <div class="form-group">
                            <label><strong>Masa Bahan Baku</strong></label>
                            <select name="material_type" class="form-control" id="">
                                <option value="">=== Pilih Massa Bahan Baku ===</option>
                                <option value="pcs">Pcs</option>
                                <option value="miligram">Miligram</option>
                                <option value="gram">Gram</option>
                                <option value="kilogram">Kilogram</option>
                                <option value="quintal">Quintal</option>
                                <option value="Ton">Ton</option>
                                <option value="Box">Box</option>
                                <option value="Sachet">Sachet</option>
                                <option value="Pack">Pack</option>
                                <option value="Karung">Karung</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Expired Bahan Baku</strong></label>
                            <input type="date" name="expired_date" class="form-control" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah material</button>
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
