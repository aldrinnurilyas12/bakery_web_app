<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Produk Kategori</title>
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
                    <h4>Tambah Data Kategori</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Hindari penggunaan karakter : #,&,@,?,/,=,-,+ dan lainnya</li>
                            <li>Jika ingin menyambung jangan pakai '&' dan spasi, pakai underscore (_) contoh :
                                Muffins_and_Cupcakes</li>
                            <li>Icon diambil dari situs web font-awesome dengan hanya input seperti : fa fa-users</li>
                        </ul>
                    </div>
                    <form action="{{ route('master_category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Nama Kategori</strong></label>
                            <input type="text" name="category_name" class="form-control"
                                value="{{ old('category_name') }}" placeholder="Masukan nama kategori" id="inputEmail4"
                                autocomplete="off">
                            <x-input-error :messages="$errors->get('category_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Icon</strong></label>
                            <input type="text" name="icon" class="form-control" value="{{ old('icon') }}"
                                placeholder="Masukan icon contoh : fa fa-users" id="inputEmail4" autocomplete="off">
                            <x-input-error :messages="$errors->get('icon')" class="text-danger" />
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
