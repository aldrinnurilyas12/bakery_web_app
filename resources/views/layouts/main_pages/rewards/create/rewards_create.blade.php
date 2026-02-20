<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Promo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h4>Tambah Data Rewards</h4>
                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Rewards hanya dapat dibuat atau diubah oleh user dengan role Admin atau Marketing.</li>
                            <li>Setiap reward wajib memiliki periode aktif (tanggal mulai dan berakhir) yang jelas.</li>
                            <li>Point atau nilai reward harus valid, tidak boleh negatif, dan sesuai ketentuan program.
                            </li>
                            <li>Reward hanya dapat diklaim oleh customer yang memenuhi syarat (misal minimal transaksi
                                atau jumlah point).</li>
                            <li>Reward yang sudah diklaim atau digunakan tidak boleh dihapus, hanya dapat dinonaktifkan
                                untuk mencegah kehilangan data transaksi.</li>
                        </ul>
                    </div>
                    <form action="{{ route('master_rewards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><strong>Masukan nama reward</strong></label>
                            <input type="text" name="rewards_name" class="form-control"
                                value="{{ old('rewards_name') }}" placeholder="Masukan nama rewards" autocomplete="off">
                            <x-input-error :messages="$errors->get('rewards_name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Masukan jumlah point</strong></label>
                            <input type="number" name="point" class="form-control" value="{{ old('point') }}"
                                placeholder="Masukan jumlah point" autocomplete="off">
                            <x-input-error :messages="$errors->get('point')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal awal berlaku rewards</strong></label>
                            <input type="date" name="start_date" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('start_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal akhir berlaku reward</strong></label>
                            <input type="date" name="end_date" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('end_date')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Upload Foto/Gambar</strong></label>
                            <input type="file" name="images" class="form-control" autocomplete="off">
                            <x-input-error :messages="$errors->get('images')" class="text-danger" />
                        </div>

                        <hr>
                        <h4>Jumlah Stock Reward Store</h4>
                        <hr>

                        <div class="form-group">
                            <div style="color: black; height: 400px;background: white;overflow: auto;"
                                class="modal-body">
                                <div class="table-responsive">
                                    <table style="font-size: 14px; color:black;" class="table" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pilih</th>
                                                <th>Nama Store</th>
                                                <th>Stock Reward</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $no = 1; ?>
                                            @foreach ($store as $outlet)
                                                <tr style="width: 200px;">
                                                    <td><?php echo $no++; ?></td>
                                                    <td>
                                                        <input class="allowed-checkbox" type="checkbox"
                                                            name="store[{{ $outlet->store_code }}]"
                                                            value="{{ $outlet->store_code }}">
                                                    </td>
                                                    <td>{{ '[' . $outlet->store_code . '] ' . ' - ' . $outlet->store_name }}
                                                    </td>
                                                    <td><input class="form-control"
                                                            name="stock[{{ $outlet->store_code }}]" type="number"></td>


                                                </tr>
                                            @endforeach
                                            <x-input-error :messages="$errors->get('store')" class="text-danger" />
                                            <x-input-error :messages="$errors->get('stock')" class="text-danger" />
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@elseif (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif





</html>
