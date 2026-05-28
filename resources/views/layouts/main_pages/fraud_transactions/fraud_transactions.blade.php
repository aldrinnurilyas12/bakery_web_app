<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Transaksi Fraud</title>
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
                    $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager', 'IT Developer']);
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">

                            <div class="title">
                                Master Data / Transaksi Fraud
                            </div>
                        </div>
                        <hr>
                        <div style="font-size: 13px;" class="alert alert-info">
                            {{-- <ul>
                                <li>Kategori tidak bisa dihapus jika sudah ada di master products
                                </li>
                            </ul> --}}
                        </div>
                        <div class="card-body">
                            @if ($fraud_transactions->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if ($user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Informasi Transaksi</th>

                                                <th>Fraud Analysis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($fraud_transactions as $key => $fraud)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if ($user_permission_forbidden)
                                                        <td>
                                                            @if($fraud->status_name == 'Resolved')
                                                            <a class="btn btn-success"><i class="fa fa-check"></i> Sudah Selesai</a>

                                                             <a class="btn btn-warning" href="#" data-toggle="modal"
                                                                            data-target="#showModalTimeline{{ $fraud->fraud_code}}" href=""><i
                                                                            class="fa fa-history"></i> Timeline Progress </a>
                                                            @else
                                                                <div style="display: flex;gap:10px;" class="btn-action">
                                                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                                                            data-target="#showModalStatus{{ $fraud->fraud_code}}" href=""><i
                                                                            class="fas fa-edit"></i> Tindakan</a>
                                                                    
                                                            @endif
        
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th width="30%">Tanggal Transaksi</th>
                                                                    <td>{{ $fraud->transaction_date }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Kode Transaksi</th>
                                                                    <td>{{ $fraud->transaction_code }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Pelanggan</th>
                                                                    <td>{{ $fraud->customer ?: '-' }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Total Amount</th>
                                                                    <td>Rp. {{ number_format($fraud->total_amount) }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Kembalian</th>
                                                                    <td>Rp. {{ number_format($fraud->payment_changes) }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Grand Total</th>
                                                                    <td>Rp. {{ number_format($fraud->grand_total) }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Metode Pembayaran</th>
                                                                    @if($fraud->payment_category)
                                                                    <td>{{ $fraud->payment_category }}</td>
                                                                    @else
                                                                    <td><span class="text-danger">Tidak ada metode pembayaran</span></td>
                                                                    @endif
                                                                </tr>

                                                                <tr>
                                                                    <th>Kasir</th>
                                                                    <td>{{ $fraud->casheer }}</td>
                                                                </tr>


                                                                <tr>
                                                                    <th>Outlet</th>
                                                                    <td>{{ $fraud->store_name }}</td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td>
                                                       <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th width="30%">Kode Fraud</th>
                                                                    <td>{{ $fraud->fraud_code ?: '-' }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Tipe Fraud</th>
                                                                    <td>{{ $fraud->fraud_name ?: '-' }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Level Severity</th>
                                                                    <td>
                                                                      {{$fraud->severity_level}}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Investigasi Oleh</th>
                                                                    <td>{{ $fraud->investigation_by ?: '-' }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Approval Oleh</th>
                                                                    <td>{{ $fraud->approval_by ?: '-' }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Note</th>
                                                                    <td>{{ $fraud->notes ?: '-' }}</td>
                                                                </tr>
                                                                 @if($fraud->it_testing == 'Y')
                                                                <tr>
                                                                    <th>IT Testing</th>
                                                                    @if($fraud->it_testing == 'Y')
                                                                    <td>Ya</td>
                                                                    @else
                                                                    <td>-</td>
                                                                    @endif
                                                                </tr>
                                                                @endif

                                                                 @if($fraud->it_testing == 'Y')
                                                                <tr>
                                                                    <th>Testing Oleh</th>
                                                                    <td>{{ $fraud->it_testing_by ?: '-' }}</td>
                                                                </tr>
                                                                @endif

                                                                <tr>
                                                                    <th>Fraud Status Info</th>
                                                                    <td>
                                                                        @if ($fraud->fraud_status_info == 'Fraud')
                                                                            <span class="text-danger">{{ $fraud->fraud_status_info }}</span>
                                                                        @elseif ($fraud->fraud_status_info == 'Not Fraud')
                                                                            <span class="text-success">{{ $fraud->fraud_status_info }}</span>
                                                                        @else
                                                                            <span class="text-warning">{{ $fraud->fraud_status_info }}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Status Info</th>
                                                                    <td>
                                                                       {{ $fraud->status_name }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Updated At</th>
                                                                    <td>{{ $fraud->updated_at ?: '-' }}</td>
                                                                </tr>
                                                            </tbody>
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
                                            <div style="display: block;align-content: center;" class="text-content">
                                                <h3>Belum ada Transaksi Fraud</h3>
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

    @foreach ($fraud_transactions as $fraud)

        <?php 
        
        $investigation_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $approval_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        ?>
        <div wire:ignore class="modal fade" id="showModalStatus{{ $fraud->fraud_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $fraud->fraud_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tindakan Transaksi Fraud</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form class="form-delete" action="{{ route('update_fraud_transaction', $fraud->fraud_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for=""><strong>Kode Transaksi</strong></label>
                            <input class="form-control" type="text" value="{{ $fraud->transaction_code }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for=""><strong>Kode Fraud</strong></label>
                            <input class="form-control" name="fraud_code" type="text" value="{{ $fraud->fraud_code }}" readonly>
                        </div>


                        <div class="form-group">
                            <label for=""><strong>Status saat ini</strong></label>
                            <input class="form-control" type="text" value="{{ $fraud->status_name }}" readonly>
                        </div>

                        <hr>

                        @if($fraud->status_name == 'Testing Only')

                         <div class="form-group">
                                <input hidden type="text" name="status_progress" class="form-control" value="23">
                            </div>
                         <div class="form-group">
                                <label for=""><strong>Status Fraud</strong></label>
                                <select name="fraud_status_info" class="form-control" id="" required>
                                    <option value="">=== Pilih status ===</option>
                                    @foreach ($status_fraud as $sts )
                                        <option value="{{ $sts->id }}">{{ $sts->info_name }}</option>
                                    @endforeach

                                </select>
                            </div>

                        @elseif($fraud->status_name == 'Under Review')
                            <div class="form-group">
                                <input hidden type="text" name="fraud_status_info" class="form-control" value="{{ $fraud->fraud_status_info_id }}">
                            </div>

                            <div class="form-group">
                                <label for=""><strong>Status Progress</strong></label>
                                <br>
                                <input  type="checkbox" name="status_progress" value="25" required>&nbsp; Under Investigation
                                <input type="text" value="{{ $investigation_by }}" name="investigation_by" hidden>
                            </div>
                        @elseif($fraud->status_name == 'Under Investigation')

                            <div class="form-group">
                                <label for=""><strong>Status Fraud</strong></label>
                                <select name="fraud_status_info" class="form-control" id="" required>
                                    <option value="">=== Pilih status ===</option>
                                    @foreach ($status_fraud as $sts )
                                        <option value="{{ $sts->id }}">{{ $sts->info_name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                 <label for=""><strong>Beri catatan</strong></label>
                                <textarea class="form-control" name="notes" id="" cols="30" rows="3">
                                </textarea>
                            </div>


                            <div class="form-group">
                                <label for=""><strong>Status Progress </strong></label>
                                <br>
                                <input type="checkbox" name="status_progress" value="23" required>&nbsp; Resolved
                                <input type="text" value="{{ $investigation_by }}" name="investigation_by" hidden>
                                <input type="text" value="{{ $approval_by }}" name="approval_by" hidden>
                            </div>
                        @endif


                
                
                    </div>
                    <div class="modal-footer">
                            <button id="btn-general" type="submit" class="btn-general"><span
                                    class="btn-text">Simpan data</span>
                                <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

     @foreach ($fraud_transactions as $fraud)

                <div wire:ignore class="modal fade" id="showModalTimeline{{ $fraud->fraud_code }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel{{ $fraud->fraud_code }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Progress Timeline Transaksi Fraud</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        
                            <div class="modal-body">

                            
                                <table class="table table-bordered">

                                    <thead>
                                        <tr>
                                            <th>Progress</th>
                                            <th>Tanggal</th>
                                            <th>Updated By</th>
                                        </tr>

                                        <tbody>
                                             @foreach ($fraud_timeline as $ftl)
                                                @if($ftl->fraud == $fraud->fraud_code)
                                                <tr>
                                                    <td>{{ $ftl->status_name ?: '-' }}</td>
                                                    <td>{{ $ftl->updated_at }}</td>
                                                    <td>{{ $ftl->name ?: '-' }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </thead>

                                </table>
                                
                                <div class="form-group">
                                    <label for=""><strong>Status Fraud</strong></label>
                                    @if($fraud->fraud_status_info == 'Fraud')
                                    <p class="text-danger">{{ $fraud->fraud_status_info }}</p>
                                    @elseif($fraud->fraud_status_info == 'Not Fraud')
                                    <p class="text-success">{{ $fraud->fraud_status_info }}</p>
                                    @else
                                     <p class="text-warning">{{ $fraud->fraud_status_info }}</p>
                                    @endif
                                </div>
                        
                            </div>
                            <div class="modal-footer">
                            
                                    <button id="btn-general" type="button" data-dismiss="modal" class="btn-general"><span
                                            class="btn-text">Tutup</span>
                                    <span class="spinner"></span></button>
                            
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
