<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Production Produk detail</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                @php
                    $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
                    $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">

                            <div class="title">
                                Inventory > Produksi Produk > <strong>Produksi Produk Detail</strong>
                            </div>
                            <br>
                            <div class="error">
                                <x-input-error :messages="$errors->get('actual_quantity')" class="text-danger" />
                                <x-input-error :messages="$errors->get('reject_quantity')" class="text-danger" />
                            </div>

                        </div>
                        <div style="display: flex; gap:20px;" class="card-header">
                            <div class="back-btn">
                                <a class="btn btn-primary" href="{{ route('production_products') }}">Kembali</a>
                            </div>
                        </div>
                        <div class="card-header">
                            <div style="font-size: 13px;" class="alert alert-info">
                                <ul>
                                    <li>Data yang sudah diinput tidak dapat diubah lagi, maka periksalah dengan benar.
                                    </li>
                                    <li>Total biaya produksi per Produk dan biaya HPP akan muncul ketika Total Aktual
                                        dan Total Reject sudah diisi
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="form-group">
                                <label for=""><strong>Total Biaya Produksi</strong></label>
                                <input class="form-control" type="text"
                                    value="{{ 'Rp.' . number_format($production->first()->total_cost) }}" readonly>
                            </div>

                        </div>
                        <div class="card-body">
                            @if ($production->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Varian</th>
                                                <th>Target Produksi</th>
                                                <th>Anggaran Produksi & HPP</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($production as $prdc)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>{{ $prdc->product_name }}
                                                    </td>
                                                    <td> {{ $prdc->variant ?: '-' }} </td>
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Aksi</th>
                                                                <th>Total Target </th>
                                                                <th>Total Actual</th>
                                                                <th>Total Reject</th>

                                                            </tr>

                                                            <tr>
                                                                @if (!$user_permission_forbidden)
                                                                    @if ($prdc->actual_quantity == null && $prdc->reject_quantity == null)
                                                                        <td>
                                                                            <div style="display: flex;gap:10px;"
                                                                                class="btn-action">

                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#updateModal{{ $prdc->id }}"><i
                                                                                        class="fas fa-edit"></i></a>
                                                                            </div>
                                                                        </td>
                                                                    @else
                                                                        <td>-</td>
                                                                    @endif
                                                                @endif
                                                                <td>{{ $prdc->qty_target_total }}</td>
                                                                <td>
                                                                    {{ $prdc->actual_quantity === null ? '-' : $prdc->actual_quantity }}
                                                                </td>
                                                                <td>
                                                                    {{ $prdc->reject_quantity === null ? '-' : $prdc->reject_quantity }}
                                                                </td>
                                                            </tr>

                                                        </table>

                                                    </td>
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>HPP</th>
                                                                <th>Biaya Produksi per Produk</th>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    @if ($prdc->hpp)
                                                                        {{ 'Rp.' . number_format($prdc->hpp) }}
                                                                    @else
                                                                        <a
                                                                            href="{{ route('add_ingredients', $prdc->product) }}"><i
                                                                                class="fa fa-edit"></i></a>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($prdc->hpp)
                                                                        {{ 'Rp.' . number_format($prdc->hpp * $prdc->actual_quantity) }}
                                                                    @else
                                                                        <span class="text-danger">HPP belum ada untuk
                                                                            produk ini</span>
                                                                    @endif
                                                                </td>

                                                                {{-- <td>
                                                                    {{ $prdc->hpp !== null ? 'Rp.' . number_format((float) $prdc->hpp) : '-' }}
                                                                </td> --}}

                                                            </tr>

                                                        </table>



                                                    </td>

                                                    <td>{{ $prdc->created_at }}</td>
                                                    <td>{{ $prdc->updated_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                    class="empty-transaction">

                                    <div style="display: flex;" class="empty-content">
                                        <div style="display: flex; gap:20px;margin:auto;" class="alert-info">
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                alt="">
                                            <div style="display: block;" class="text-content">
                                                <h3>Tidak ada data produksi produk detail</h3>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @foreach ($production as $prdc)
        <div wire:ignore class="modal fade" id="updateModal{{ $prdc->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $prdc->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah data Produksi Produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Data yang sudah diinput tidak dapat diubah lagi, maka periksalah dengan benar.</li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <form class="form-delete" action="{{ route('production_detail_update', $prdc->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <input hidden type="text" value="{{ $prdc->id }}" name="production_id"
                                class="form-control" readonly>
                            <div class="form-group">
                                <label for=""><strong>Produk</strong></label>
                                @if ($prdc->variant)
                                    <input type="text" class="form-control"
                                        value="{{ $prdc->product_name . ' - ' . '[' . $prdc->variant . ']' }}"
                                        readonly>
                                @else
                                    <input type="text" class="form-control" value="{{ $prdc->product_name }}"
                                        readonly>
                                @endif
                            </div>
                            <br>

                            <div class="form-group">
                                <label for=""><strong>Total Target</strong></label>
                                <input type="text" name="target_total" value="{{ $prdc->qty_target_total }}"
                                    class="form-control" readonly>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Jumlah Aktual (Produk Jadi)</strong></label>
                                <input type="text" name="actual_quantity" class="form-control" required
                                    autocomplete="off">
                            </div>
                            <br>
                            <div id="showWasteConfirmation" class="form-group">
                                <label for=""><strong>Apakah ada Produk yang Waste/Kerusakan?</strong></label>

                                <div style="display: flex; gap:10px;" class="form-group-radio">
                                    <div class="input">
                                        <input type="radio" value="yes" name="waste_confirmation" required> Ya
                                    </div>

                                    <div class="input">
                                        <input type="radio" value="no" name="waste_confirmation" required>
                                        Tidak
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                class="btn-text">Simpan</span>
                            <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

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

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
