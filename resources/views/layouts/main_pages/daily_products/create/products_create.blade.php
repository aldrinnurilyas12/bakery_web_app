<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Item</title>
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
                    <h4>Tambah Data Daily Produk</h4>
                    <hr>
                    <form action="{{ route('master_daily_products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><strong>Produk</strong></label>
                            @if ($products->isNotEmpty())
                                <select class="form-control" name="product_code" id="">
                                    <option value="">==== Pilih Produk ====</option>
                                    @foreach ($products as $item)
                                        <option value="{{ $item->product_code }}">
                                            {{ $item->product }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-secondary">Anda belum buat data Kategori, <a
                                        href="{{ route('category_create') }}">Buat kategori</a> </p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah stok</strong></label>
                            <input type="text" name="stock_available" class="form-control"
                                value="{{ old('stock_available') }}" placeholder="Masukan jumlah stock"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah Point</strong></label>
                            <input type="text" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label><strong>Status Produk</strong></label>
                            <select name="status" class="form-control" id="">
                                <option value="">=== Pilih Status ===</option>
                                @foreach ($status as $sts)
                                    <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Item</button>
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
