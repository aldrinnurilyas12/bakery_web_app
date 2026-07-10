<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Klaim Reward</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



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
                                Master Data / <a href="{{ route('master_rewards.index') }}">Reward</a>
                            </div>

                            @if ($module_documentation)
                                <div style="align-self: center; background: rgb(222, 222, 255);padding:8px; border-radius: 5px;"
                                    class="documentation-module">
                                    <a title="Dokumentasi Modul"
                                        href="{{ route('show_module_documentation', $module_documentation->url_path) }}">
                                        <i aria-label="Module Documentation" class="fa fa-file"></i>
                                    </a>
                                </div>
                            @endif

                        </div>

                        <ul class="nav nav-tabs" id="expenseTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                    data-bs-target="#all" type="button">
                                    Semua
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="claimed-tab" data-bs-toggle="tab" data-bs-target="#claimed"
                                    type="button">
                                    Sudah klaim
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unclaimed-tab" data-bs-toggle="tab"
                                    data-bs-target="#unclaimed" type="button">
                                    Belum klaim
                                </button>
                            </li>
                        </ul>



                        <div class="tab-content">
                            {{-- Tab all products --}}
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">
                                        <div class="card-body">
                                            @if ($reward_data->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Aksi</th>
                                                                <th>Kode Redeem</th>
                                                                <th>Reward</th>
                                                                <th>Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Pelanggan</th>
                                                                <th>Status</th>
                                                                <th>Tanggal Pick Up</th>
                                                                <th>Tanggal Redeem</th>
                                                                <th>Approval</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($reward_data as $key => $reward)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    <td>
                                                                        <div style="display: flex;gap:10px;"
                                                                            class="btn-action">
                                                                            @if ($reward->status_name == 'Claimed')
                                                                                <a class="btn btn-secondary"
                                                                                    href="#">Klaim
                                                                                </a>
                                                                            @else
                                                                                <a class="btn btn-primary"
                                                                                    href="#" data-toggle="modal"
                                                                                    data-target="#deleteModal{{ $reward->id }}">Klaim
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $reward->redeem_code }}</td>
                                                                    <td> {{ $reward->reward }} </td>
                                                                    <td>{{ $reward->rewards_name }}</td>
                                                                    <td>{{ $reward->quantity }}</td>
                                                                    <td> {{ $reward->customer }} </td>
                                                                    <td>
                                                                        @if ($reward->status_name == 'Claimed')
                                                                            <span class="text-success">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-secondary">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($reward->pickup_schedule)->format('Y-m-d') }}
                                                                    </td>
                                                                    <td> {{ $reward->redeem_date }} </td>
                                                                    <td>{{ $reward->approval_by }}</td>
                                                                    <td>{{ $reward->created_at }}</td>
                                                                    <td>{{ $reward->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;align-self: center;"
                                                                class="text-content">
                                                                <h3>Belum ada Redeem Reward</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="claimed" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">

                                        <div class="card-body">
                                            @if ($reward_data->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Redeem</th>
                                                                <th>Reward</th>
                                                                <th>Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Pelanggan</th>
                                                                <th>Status</th>
                                                                <th>Tanggal Pick Up</th>
                                                                <th>Tanggal Redeem</th>
                                                                <th>Approval</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($reward_data_claimed as $key => $reward)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    <td>{{ $reward->redeem_code }}</td>
                                                                    <td> {{ $reward->reward }} </td>
                                                                    <td>{{ $reward->rewards_name }}</td>
                                                                    <td>{{ $reward->quantity }}</td>
                                                                    <td> {{ $reward->customer }} </td>
                                                                    <td>
                                                                        @if ($reward->status_name == 'Claimed')
                                                                            <span class="text-success">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-secondary">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($reward->pickup_schedule)->format('Y-m-d') }}
                                                                    </td>
                                                                    <td> {{ $reward->redeem_date }} </td>
                                                                    <td>{{ $reward->approval_by }}</td>
                                                                    <td>{{ $reward->created_at }}</td>
                                                                    <td>{{ $reward->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;" class="text-content">
                                                                <h3>Belum ada Redeem Reward</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>



                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="unclaimed" role="tabpanel">
                                <div class="container-products">
                                    <div class="menu-list">

                                        <div class="card-body">
                                            @if ($reward_data->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table" id="dataTable" width="100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Aksi</th>
                                                                <th>Kode Redeem</th>
                                                                <th>Reward</th>
                                                                <th>Barang</th>
                                                                <th>Jumlah</th>
                                                                <th>Pelanggan</th>
                                                                <th>Status</th>
                                                                <th>Tanggal Pick Up</th>
                                                                <th>Tanggal Redeem</th>
                                                                <th>Approval</th>
                                                                <th>Created at</th>
                                                                <th>Updated at</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 1;
                                                            ?>
                                                            @foreach ($reward_data_unclaimed as $key => $reward)
                                                                <tr>
                                                                    <td><?php echo $no++; ?></td>
                                                                    <td>
                                                                        <div style="display: flex;gap:10px;"
                                                                            class="btn-action">
                                                                            @if ($reward->status_name == 'Claimed')
                                                                                <a class="btn btn-secondary"
                                                                                    href="#">Klaim
                                                                                </a>
                                                                            @else
                                                                                <a class="btn btn-primary"
                                                                                    href="#" data-toggle="modal"
                                                                                    data-target="#deleteModal{{ $reward->id }}">Klaim
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $reward->redeem_code }}</td>
                                                                    <td> {{ $reward->reward }} </td>
                                                                    <td>{{ $reward->rewards_name }}</td>
                                                                    <td>{{ $reward->quantity }}</td>
                                                                    <td> {{ $reward->customer }} </td>
                                                                    <td>
                                                                        @if ($reward->status_name == 'Claimed')
                                                                            <span class="text-success">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-secondary">
                                                                                {{ $reward->status_name }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($reward->pickup_schedule)->format('Y-m-d') }}
                                                                    </td>
                                                                    <td> {{ $reward->redeem_date }} </td>
                                                                    <td>{{ $reward->approval_by }}</td>
                                                                    <td>{{ $reward->created_at }}</td>
                                                                    <td>{{ $reward->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                                    class="empty-transaction">

                                                    <div style="display: flex;" class="empty-content">
                                                        <div style="display: flex; gap:20px;margin:auto;"
                                                            class="alert-info">
                                                            <img width="70" height="70"
                                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                                alt="">
                                                            <div style="display: block;" class="text-content">
                                                                <h3>Belum ada Redeem Reward</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    @foreach ($reward_data as $reward)
        <div wire:ignore class="modal fade" id="deleteModal{{ $reward->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $reward->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Klaim Reward</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin Klaim Reward
                        {{ $reward->redeem_code }} ?</div>
                    <div class="modal-footer">
                        <form id="formGeneralMaster" action="{{ route('claimed-reward', $reward->redeem_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Klaim
                                    Reward</span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

    /* Container */
    .container {
        max-width: 900px;
    }

    /* Tabs wrapper */
    .nav-tabs {
        border-bottom: none;
        gap: 10px;
        padding: 25px;
    }

    /* Tab button */
    .nav-tabs .nav-link {
        border: none;
        color: #555;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* Hover effect */


    /* Active tab */
    .nav-tabs .nav-link.active {
        background: #bb0239;
        color: #fff;

    }

    /* Tab content box */
    .tab-content {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Form styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #bb0239;
        box-shadow: none;
    }

    /* Button */
    .btn-primary {
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 500;
    }

    /* Title */
    h3 {
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>

</html>
