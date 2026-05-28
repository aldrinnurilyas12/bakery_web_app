<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Supplier</title>
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
                    <h4>Tambah Data Supplier</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('supplier.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Perusahaan Supplier</strong></label>
                            <input type="text" name="store" class="form-control" value="{{ old('store') }}"
                                placeholder="Masukan nama perusahaan supplier" autocomplete="off" required>
                            <x-input-error :messages="$errors->get('store')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Supplier</strong></label>
                            <select class="form-control" name="supplier_category" id="" required>
                                <option value="">=== Kategori Supplier ===</option>
                                @foreach ($supplier_category as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->category_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_category')" class="text-danger" />
                        </div>


                        <div class="form-group">
                            <label><strong>Alamat Perusahaan Supplier</strong></label>
                            <textarea name="address" class="form-control" autocomplete="off" required>
                                {{ old('address') }}
                            </textarea>
                            <x-input-error :messages="$errors->get('address')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>No.Telepon</strong></label>
                            <input type="number" name="phone_number" class="form-control"
                                value="{{ old('phone_number') }}" placeholder="Masukan no telepon" autocomplete="off"
                                required>
                            <x-input-error :messages="$errors->get('phone_number')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Nama Penanggung Jawab Perusahaan Supplier</strong></label>
                            <input type="text" name="pic" class="form-control" value="{{ old('pic') }}"
                                placeholder="Masukan nama penanggung jawab perusahaan supplier" autocomplete="off"
                                required>
                            <x-input-error :messages="$errors->get('pic')" class="text-danger" />
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
