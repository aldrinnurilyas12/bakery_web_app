<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Kategori</title>
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
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                CRM > <strong> E-Voucher Ulang Tahun Pelanggan </strong>
                            </div>
                        </div>

                        <div class="card-header">
                            <div style="font-size: 13px;" class="alert alert-info">
                                <ul>
                                    <li>E-Voucher Ulang Tahun hanya dapat dibagikan ketika Pelanggan berulang tahun pada
                                        bulan ini
                                    </li>
                                    <li>E-Voucher Ulang Tahun hanya bisa dibagikan ketika pelanggan sudah menjadi bagian
                                        member dan sudah melakukan transaki lebih dari 10x dalam 30 hari terakhir</li>
                                    <li>Hanya Staff Operasional yang dapat membagikan E-Voucher ke Pelanggan dan
                                        E-Voucher
                                        hanya bisa dikirim melalui Email Pelanggan</li>
                                    <li>1 Pelanggan hanya bisa 1 dapat E-Voucher</li>
                                </ul>
                            </div>

                            @if ($customers_data->count() > 1)
                                <hr>
                                <h4>Bagikan E-Voucher keseluruh Pelanggan</h4>
                                <hr>
                                <form action="{{ route('voucher_birthday_shared') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="form-group">
                                        <label for=""><strong>Pilih E-Voucher</strong></label>
                                        <select style="width:max-content;" name="voucher_code" id=""
                                            class="form-control" required>
                                            <option value="">=== Pilih E-Voucher ===</option>
                                            @foreach ($vouchers as $voucher)
                                                <option value="{{ $voucher->voucher_code }}">
                                                    {{ $voucher->voucher_name }}
                                                    &nbsp;
                                                    <span>|</span>
                                                    &nbsp; Kuota: {{ $voucher->quota }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr>
                                    @foreach ($customers_data as $customer)
                                        @if ($customer->total_transactions >= 10)
                                            <input type="text" name="customer[]"
                                                value="{{ $customer->customer_code }}" hidden>
                                        @endif
                                    @endforeach
                                    <button style="width:max-content;" type="submit" class="btn-general">Bagikan
                                        semua</button>
                                </form>
                            @endif
                        </div>

                        <hr>



                        <div class="card-body">
                            @if ($customers_data->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Status</th>
                                                <th>Pelanggan</th>
                                                <th>Total Transaksi</th>
                                                <th>Kriteria</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Tanggal Member</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($customers_data as $key => $customer)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            @if ($customer->customer_code == $check_voucher_shared->customer)
                                                                <span>-</span>
                                                            @else
                                                                <div style="display: flex;gap:10px;" class="btn-action">
                                                                    @if ($customer->total_transactions >= 10)
                                                                        <a class="btn-general" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#shareVoucher{{ $customer->customer_code }}">Bagikan
                                                                            Voucher</a>
                                                                    @else
                                                                        <span>-</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if ($customer->customer_code == $check_voucher_shared->customer)
                                                            <span><i class="fa fa-square-check"></i> Bagikan</span>
                                                        @else
                                                            <span><i class="fa fa-close"></i> Belum</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $customer->name }}
                                                        <br>
                                                        <span><small>{{ $customer->email }} </small></span>
                                                    </td>
                                                    <td> {{ $customer->total_transactions }} </td>
                                                    <td>
                                                        @if ($customer->total_transactions > 10)
                                                            <span><i style="color: green;" class="fa fa-square-check">
                                                                </i> Memenuhi</span>
                                                        @else
                                                            <span class="text-danger"><i style="color: red;"
                                                                    class="fa fa-close">
                                                                </i> Tidak Memenuhi</span>
                                                        @endif

                                                    </td>
                                                    <td>{{ \Carbon\carbon::parse($customer->birth_date)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\carbon::parse($customer->member_date)->format('d M Y') }}
                                                    </td>
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
                                                <h3>Tidak ada data Pelanggan Ulang Tahun Bulan ini</h3>
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

    @foreach ($customers_data as $cust)
        <div wire:ignore class="modal fade" id="shareVoucher{{ $cust->customer_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $cust->customer_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bagikan E-Voucher Ulang Tahun</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    @if ($cust->customer_code == $check_voucher_shared->customer)
                        <p style="text-align: center;">E-Voucher Sudah dibagikan pada pelanggan ini</p>
                    @else
                        <div class="modal-body">
                            <form class="form-delete" action="{{ route('share_voucher_only_customer') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for=""><strong>Pilih E-Voucher</strong></label>
                                    <select style="width:max-content;" name="voucher_code" id=""
                                        class="form-control" required>
                                        <option value="">=== Pilih E-Voucher ===</option>
                                        @foreach ($vouchers as $voucher)
                                            <option value="{{ $voucher->voucher_code }}">{{ $voucher->voucher_name }}
                                                &nbsp;
                                                <span>|</span>
                                                &nbsp; Kuota: {{ $voucher->quota }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <br>

                                <div class="form-group">
                                    <label for=""><strong>Nama Pelanggan</strong></label>
                                    <br>
                                    <input type="text" name="customer" value="{{ $cust->customer_code }}" hidden>
                                    <label for="">{{ $cust->name }}</label>
                                </div>

                        </div>

                        <div class="modal-footer">
                            <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                    class="btn-text">Ya Bagikan</span>
                                <span class="spinner"></span></button>
                            </form>
                        </div>
                    @endif
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
