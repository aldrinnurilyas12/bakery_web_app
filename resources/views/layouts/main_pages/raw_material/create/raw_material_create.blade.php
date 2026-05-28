<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Bahan Baku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                    <h4>Tambah Data Bahan Baku</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('master_material.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan Nama Bahan Baku</strong></label>
                            <input type="text" name="material_name" class="form-control"
                                value="{{ old('material_name') }}" placeholder="Masukan nama bahan baku"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('material_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Purchase Unit Bahan Baku &nbsp; <span><a href="#" data-toggle="modal"
                                            data-target="#showPurchaseUnitInfo"><i
                                                class="fa fa-info-circle"></i></a></span></strong></label>
                            <select name="purchase_unit" class="form-control" id="">
                                <option value="">=== Pilih Purchase Unit Bahan Baku ===</option>
                                @foreach ($material_unit as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('purchase_unit')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Inventory Unit Bahan Baku &nbsp; <span><a href="#" data-toggle="modal"
                                            data-target="#showInventoryUnitInfo"><i
                                                class="fa fa-info-circle"></i></a></span></strong></label>
                            <select name="inventory_unit" class="form-control" id="">
                                <option value="">=== Pilih Inventory Unit Bahan Baku ===</option>
                                @foreach ($material_unit as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('inventory_unit')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Bahan Baku</strong></label>
                            <select name="material_category" class="form-control" id="">
                                <option value="">=== Pilih Kategori Bahan Baku ===</option>
                                @foreach ($material_category as $cat)
                                    <option value="{{ $cat->id }}">
                                        {{ $cat->category_name . ' => ' . $cat->description }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('material_category')" class="text-danger" />
                        </div>

                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>

            <div wire:ignore class="modal fade" id="showPurchaseUnitInfo" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Penjelasan Unit Pembelian</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Satuan Purchase Unit digunakan saat pembelian bahan baku dari supplier.<br><br>

                            Contoh:<br>
                            Telur ayam dibeli dalam satuan Kilogram.<br><br>

                            Jadi saat melakukan pembelian bahan baku telur ayam, jumlah yang dihitung adalah per
                            Kilogram.
                        </div>
                        <div class="modal-footer">

                            <button id="btn-delete-general" type="button" class="btn-general-delete"
                                data-dismiss="modal" aria-label="Close"><span class="btn-text">Tutup</span>
                                <span class="spinner"></span></button>

                        </div>
                    </div>
                </div>
            </div>


            <div wire:ignore class="modal fade" id="showInventoryUnitInfo" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Penjelasan Inventory Unit</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Satuan Inventory digunakan saat bahan baku dipakai dalam proses produksi atau Bill Of
                            Material (BOM).<br><br>

                            Contoh:<br>
                            - Purchase Unit: Telur ayam dibeli dalam Kilogram<br>
                            - Inventory Unit: Telur ayam digunakan dalam Butir<br><br>

                            Jadi pada BOM dan perhitungan HPP produk, yang digunakan adalah satuan Inventory Unit.<br>
                            Contoh: 1 pcs Roti Abon membutuhkan 4 butir telur ayam.
                        </div>
                        <div class="modal-footer">

                            <button id="btn-delete-general" type="button" class="btn-general-delete"
                                data-dismiss="modal" aria-label="Close"><span class="btn-text">Tutup</span>
                                <span class="spinner"></span></button>

                        </div>
                    </div>
                </div>
            </div>
</body>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>




</html>
