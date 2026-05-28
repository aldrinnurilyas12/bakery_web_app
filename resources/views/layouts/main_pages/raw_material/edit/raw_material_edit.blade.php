<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Ubah Bahan Baku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
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
                    <form id="formGeneralMaster" action="{{ route('material_edit', $raw_material->material_code) }}"
                        method="POST" enctype="multipart/form-data">
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
                            <x-input-error :messages="$errors->get('material_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Pilih Purchase Unit Bahan Baku</strong></label>
                            <select name="purchase_unit" class="form-control">

                                @foreach ($material_unit as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ $raw_material->purchase_unit == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_name }}</option>
                                @endforeach

                            </select>
                            <x-input-error :messages="$errors->get('purchase_unit')" class="text-danger" />
                        </div>


                        @if ($raw_material->inventory_unit == null)
                            <div class="form-group">
                                <label><strong>Pilih Inventory Unit Bahan Baku</strong></label>
                                <small class="text-danger">*belum pilih inventory unit untuk bahan baku ini</small>
                                <select name="inventory_unit" class="form-control">
                                    <option>=== Pilih Inventory Unit ===</option>
                                    @foreach ($material_unit as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $raw_material->inventory_unit == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }}</option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('purchase_unit')" class="text-danger" />
                            </div>
                        @else
                            <div class="form-group">
                                <label><strong>Pilih Inventory Unit Bahan Baku</strong></label>
                                <select name="inventory_unit" class="form-control">

                                    @foreach ($material_unit as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $raw_material->inventory_unit == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }}</option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('purchase_unit')" class="text-danger" />
                            </div>
                        @endif


                        <div class="form-group">
                            <label><strong>Kategori Bahan Baku</strong></label>
                            <select name="material_category" class="form-control" id="">
                                @foreach ($material_category as $ctg)
                                    <option value="{{ $ctg->id }}"
                                        {{ $ctg->id == $raw_material->material_category ? 'selected' : '' }}>
                                        {{ $ctg->category_name . ' => ' . $ctg->description }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('material_category')" class="text-danger" />
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
