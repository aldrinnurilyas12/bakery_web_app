<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelanggan</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
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
                                Master Data > <strong>Pelanggan </strong>
                            </div>

                        </div>
                        <div class="card-body">
                            @if ($customers->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Informasi Pelanggan</th>
                                                <th>Detail Transaksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($customers as $key => $customer)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>


                                                    <td>
                                                        <table class="table table-bordered" cellspacing="0">
                                                            <tr>
                                                                <th>Kode Pelanggan</th>
                                                                <td>{{ $customer->customer_code }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Nama</th>
                                                                <td>{{ $customer->name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Alamat</th>
                                                                <td>
                                                                    @if ($customer->address)
                                                                        <textarea name="" id="" cols="30" rows="3" readonly>
                                                                    {{ $customer->address ?: '-' }}
                                                                    </textarea>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Email</th>
                                                                <td>{{ $customer->email }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>No.HP</th>
                                                                <td>{{ $customer->phone_number }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tanggal Buat Akun</th>
                                                                <td>{{ \Carbon\carbon::parse($customer->member_date)->format('d M Y') }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tanggal Hapus Akun</th>
                                                                <td>
                                                                    @if ($customer->deleted_at)
                                                                        {{ \Carbon\carbon::parse($customer->deleted_at)->format('d M Y') }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </td>

                                                    <td>
                                                        <table class="table table-bordered" width="100%"
                                                            cellspacing="0">
                                                            <tr>
                                                                <th>Total Transaksi</th>
                                                                <td>{{ $customer->transaction_total }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Total Pengeluaran</th>
                                                                <td>
                                                                    @if ($customer->spent_money == 0 || null)
                                                                        <span>-</span>
                                                                    @else
                                                                        {{ 'Rp.' . number_format($customer->spent_money ?: '-') }}
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Total E-Voucher</th>
                                                                <td>{{ $customer->total_voucher ?: '-' }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Point</th>
                                                                <td>{{ $customer->point ?: '-' }}</td>
                                                            </tr>

                                                        </table>
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
                                                <h3>Tidak ada data customer</h3>
                                                <p class="text-secondary">Belum ada data pelanggan saat ini</p>
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

    <script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

</body>

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
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
