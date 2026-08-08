<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Maintenance Information</title>
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
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Lainnya > <strong> Informasi Perbaikan Sistem </strong>
                            </div>


                            @if ($check_last_active && $check_last_active->type == 'admin_web')
                                <div class="button-add-product">
                                    <a class="btn-general" href="{{ route('maintenance_create') }}">Tambah
                                        Informasi</a>
                                </div>
                            @elseif($check_last_active && $check_last_active->type == 'customer_web')
                                <div class="button-add-product">
                                    <a class="btn-general" href="{{ route('maintenance_create') }}">Tambah
                                        Informasi</a>
                                </div>
                            @elseif(!$check_last_active)
                                <div class="button-add-product">
                                    <a class="btn-general" href="{{ route('maintenance_create') }}">Tambah
                                        Informasi</a>
                                </div>
                            @else
                            @endif

                        </div>

                        <div style="font-size: 13px;" class="alert alert-info">
                            <ul>

                                <li>Status informasi maintenance akan berubah jika sudah melewati masa tanggal akhir
                                    ketentuan</li>
                                <li>Jika ingin menambahkan data point terbaru maka tunggu masa akhir selesai dan atau
                                    bisa nonaktifkan status informasi yang sedang berjalan</li>
                            </ul>
                        </div>


                        <div class="card-body">
                            @if ($maintenance_info->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>

                                                <th>Aksi</th>
                                                <th>Nama Informasi</th>
                                                <th>Pesan Informasi</th>
                                                <th>Tanggal awal</th>
                                                <th>Tanggal akhir</th>
                                                <th>Status</th>
                                                <th>Tipe Info</th>
                                                <th>Dibuat pada</th>
                                                <th>Dibuat oleh</th>
                                                <th>Diubah pada </th>
                                                <th>Diubah oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $type_info = DB::table('maintenance_information')->get();
                                            ?>
                                            @foreach ($maintenance_info as $info)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>

                                                    <td>
                                                        <div style="display: flex;gap:10px;" class="btn-action">

                                                            @if ($info->status_name == 'Active')
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#changeStatus{{ $info->info_code }}"><i
                                                                        class="fas fa-edit"></i></a>
                                                            @else
                                                                <span>-</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td>{{ $info->maintenance_information }}</td>
                                                    <td> {{ $info->message }} </td>
                                                    <td>{{ \Carbon\carbon::parse($info->start_date)->format('d M Y') }}
                                                        <br>
                                                        {{ $info->hour_start }}
                                                    </td>
                                                    <td>{{ \Carbon\carbon::parse($info->end_date)->format('d M Y') }}
                                                        <br>
                                                        {{ $info->hour_end }}
                                                    </td>
                                                    <td>
                                                        @if ($info->status_name == 'Active')
                                                            <span class="text-success">Aktif</span>
                                                        @else
                                                            <span class="text-danger">Nonaktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @foreach ($type_info as $type)
                                                            @if ($type->info_code == $info->info_code)
                                                                <li> {{ $type->type }}</li>
                                                            @endif
                                                        @endforeach

                                                    </td>
                                                    <td>{{ $info->created_at }}</td>
                                                    <td>{{ $info->created_by }}</td>
                                                    <td> {{ $info->updated_at }} </td>
                                                    <td>{{ $info->updated_by }}</td>
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
                                            <div style="display: block;align-content:center;" class="text-content">
                                                <h3>Belum ada Informasi Maintenance</h3>

                                                <a class="btn btn-primary"
                                                    href="{{ route('maintenance_create') }}">Tambah
                                                    Informasi</a>

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

    @foreach ($maintenance_info as $maintenance)
        <div wire:ignore class="modal fade" id="changeStatus{{ $maintenance->info_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $maintenance->info_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah status Informasi Maintenance
                            #{{ $maintenance->info_code }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-delete"
                            action="{{ route('change-status-maintenance', $maintenance->info_code) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for=""><strong>Pilih status</strong></label>
                                <select name="status" id="" class="form-control">
                                    <option value="">=== Pilih Status ===</option>
                                    @foreach ($status_categories as $sts)
                                        <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <input type="text" value="{{ $maintenance->info_code }}" name="info_code" hidden>



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


</script>

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
