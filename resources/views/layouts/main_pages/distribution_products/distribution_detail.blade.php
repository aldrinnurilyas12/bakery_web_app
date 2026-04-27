<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Inventory Distribution Detail</title>
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
                                <span> Penerimaan Distribusi Produk</span>

                                <br>
                                <br>
                                <a class="btn btn-primary" href="{{ route('distribution_products.index') }}">Kembali</a>
                            </div>

                        </div>
                        <div class="card-body">
                            @if ($distribution_detail->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                @if (!$user_permission_forbidden)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>Kode Referensi</th>
                                                <th>Produk</th>
                                                <th>Detail Info</th>
                                                <th>Received by</th>
                                                <th>Status</th>
                                                <th>Diubah pada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($distribution_detail as $dst)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    @if (!$user_permission_forbidden)
                                                        <td>
                                                            <table style="font-size: 14px; color:black;"
                                                                class="table table-bordered" id="dataTable"
                                                                width="100%" cellspacing="0">

                                                                <tr>
                                                                    <th>Attachment</th>
                                                                    <th>Edit </th>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        @if ($dst->attachment_files)
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#showModalAttachment{{ $dst->distribution_store_code }}"><i
                                                                                    class="fas fa-eye"></i></a>
                                                                        @else
                                                                            <span>-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($dst->status_name == 'Received')
                                                                            <span>-</span>
                                                                        @else
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#showModalEdit{{ $dst->distribution_store_code }}"><i
                                                                                    class="fas fa-edit"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </td>
                                                    @endif
                                                    <td>{{ $dst->distribution_store_code }}</td>
                                                    <td>
                                                        @if ($dst->variant)
                                                            {{ $dst->product . ' [' . $dst->variant . ']' }}
                                                        @else
                                                            {{ $dst->product }}
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Status</th>
                                                                <th>Tanggal Expired</th>
                                                                <th>Total Delivered</th>
                                                                <th>Total Received </th>
                                                                <th>Total Reject</th>

                                                            </tr>

                                                            <tr>
                                                                <td>{{ $dst->status_name }}</td>
                                                                <td>{{ $dst->expired_date }}</td>
                                                                <td>{{ $dst->quantity }}</td>
                                                                <td>
                                                                    @if ($dst->received_quantity)
                                                                        {{ $dst->received_quantity }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($dst->reject_quantity)
                                                                        {{ $dst->reject_quantity }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </table>


                                                    </td>
                                                    <td>
                                                        @if ($dst->approval)
                                                            {{ $dst->approval }}
                                                        @else
                                                            <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $dst->created_at }}</td>
                                                    <td>{{ $dst->updated_at }}</td>
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
                                                <h3>Belum ada data Distribusi Produk</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('distribution_create') }}">Buat
                                                        distribusi</a>
                                                @endif
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

    @foreach ($distribution_detail as $dst)
        <div wire:ignore class="modal fade" id="showModalAttachment{{ $dst->distribution_store_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $dst->distribution_store_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attachment File
                            #{{ $dst->distribution_store_code }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="img-content-info">
                            <img style="width: 100%; height:100%;" src="{{ '../storage/' . $dst->attachment_files }}"
                                alt="">
                        </div>

                        <br>
                        <hr>
                        <div class="notes-info">
                            <h5>Notes:</h5>
                            <p>{{ $dst->notes }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    @endforeach



    @foreach ($distribution_detail as $dst)
        <div wire:ignore class="modal fade" id="showNotes{{ $dst->distribution_store_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $dst->distribution_store_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Notes & Reason
                            #{{ $dst->distribution_store_code }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ $dst->notes }}</h5>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($distribution_detail as $dst)
        <div wire:ignore class="modal fade" id="showModalEdit{{ $dst->distribution_store_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $dst->distribution_store_code }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Distribusi
                            #{{ $dst->distribution_store_code }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form class="form-delete"
                            action="{{ route('distribution_store_update', $dst->distribution_store_code) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input hidden type="text" class="form-control" name="distribution"
                                value="{{ $dst->distribution_store_code }}" readonly>
                            <div class="form-group">
                                <label><strong>Produk</strong></label>
                                <input type="text" class="form-control" value="{{ $dst->product }}" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Total Distribusi Produk</strong></label>
                                <input type="text" class="form-control" value="{{ $dst->quantity }}" readonly>
                            </div>

                            <div class="form-group">
                                <label><strong>Total Received</strong></label>
                                <input type="number" min="0" name="received_quantity" class="form-control"
                                    required>
                            </div>

                            <hr>
                            <div id="showWasteConfirmation" class="form-group">
                                <label for=""><strong>Adakah Produk Waste?</strong></label>

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
                            <hr>
                            <br>
                            <div class="form-group">
                                <div style="display: flex;gap:10px;" class="flex-content">
                                    <input type="checkbox" id="" required>
                                    <label for="">Dengan ini saya menyatakan produk ini sudah
                                        diterima</label>
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

<script>
    const showWasteForm = document.getElementById('waste-form');
    const wasteyes = document.getElementById('waste-yes');
    const wasteno = document.getElementById('waste-no');

    showWasteForm.style.display = 'none';

    wasteyes.addEventListener('change', function() {
        if (this.checked) {
            showWasteForm.style.display = 'block';
        }
    });

    wasteno.addEventListener('change', function() {
        if (this.checked) {
            showWasteForm.style.display = 'none';
        }
    })
</script>

</html>
