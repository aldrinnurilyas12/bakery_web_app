<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Bahan Baku</title>
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
                    <h4>Ubah Data Bahan Baku</h4>
                    <hr>
                    <form action="{{ route('material_edit', $raw_material->material_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><strong>Kode Bahan Baku</strong></label>
                            <input type="text" class="form-control" value="{{ $raw_material->material_code }}"
                                autocomplete="off" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Bahan Baku</strong></label>
                            <input type="text" name="material_name" class="form-control"
                                value="{{ $raw_material->material_name }}" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Harga Bahan Baku</strong></label>
                            <input type="text" name="price" class="form-control" value="{{ $raw_material->price }}"
                                placeholder="Masukan harga" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Stok</strong></label>
                            <input type="text" name="quantity" class="form-control"
                                value="{{ $raw_material->quantity }}" placeholder="Masukan stok bahan baku"
                                autocomplete="off">
                        </div>


                        <div class="form-group">
                            <label><strong>Pilih Massa Bahan Baku</strong></label>
                            <select name="material_type" class="form-control">
                                <option value="pcs" {{ $raw_material->material_type == 'pcs' ? 'selected' : '' }}>Pcs
                                </option>
                                <option value="miligram"
                                    {{ $raw_material->material_type == 'miligram' ? 'selected' : '' }}>Miligram</option>
                                <option value="gram" {{ $raw_material->material_type == 'gram' ? 'selected' : '' }}>
                                    Gram</option>
                                <option value="kilogram"
                                    {{ $raw_material->material_type == 'kilogram' ? 'selected' : '' }}>Kilogram</option>
                                <option value="quintal"
                                    {{ $raw_material->material_type == 'quintal' ? 'selected' : '' }}>Quintal</option>
                                <option value="ton" {{ $raw_material->material_type == 'ton' ? 'selected' : '' }}>
                                    Ton</option>
                                <option value="sachet"
                                    {{ $raw_material->material_type == 'sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="pack" {{ $raw_material->material_type == 'pack' ? 'selected' : '' }}>
                                    Pack</option>
                                <option value="box" {{ $raw_material->material_type == 'box' ? 'selected' : '' }}>
                                    Box</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label><strong>Kategori Bahan Baku</strong></label>
                            <select name="material_category" class="form-control" id="">
                                @foreach ($material_category as $ctg)
                                    <option value="{{ $ctg->id }}"
                                        {{ $ctg->id == $raw_material->material_category ? 'selected' : '' }}>
                                        {{ $ctg->category_name . ' => ' . $ctg->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Status Bahan Baku</strong></label>
                            <select name="status" class="form-control" id="">
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}"
                                        {{ $sts->id == $raw_material->status ? 'selected' : '' }}>
                                        {{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Expired Bahan Baku</strong></label>
                            <input type="date" name="expired_date"
                                value="{{ old('expired_date', $raw_material->expired_date ? $expired_date->format('Y-m-d') : null) }}"
                                class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui pada</strong></label>
                            <input type="text" class="form-control" value="{{ $raw_material->updated_at ?: '-' }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Diperbarui oleh</strong></label>
                            <input type="text" class="form-control" value="{{ $raw_material->updated_by ?: '-' }}"
                                readonly>
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
