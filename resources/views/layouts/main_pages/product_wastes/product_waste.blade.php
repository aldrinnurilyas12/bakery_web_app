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
                    $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);
                @endphp
                <div class="container-fluid px-4">
                    <br>

                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="title">
                                Master Data / <a href="{{ route('production_products') }}">Produk Waste</a>
                            </div>


                        </div>

                        @if (!$filter_forbidden_access)
                            <div class="card-header">
                                <div class="title">
                                    <div class="filter-data">
                                        <label for=""><strong>Pilih Store</strong></label>
                                        <br>
                                        <div style="display: flex; gap:10px;margin-top:5px;"
                                            class="d-flex-container-filter">
                                            <form action="{{ route('filter_wastes') }}" method="GET">
                                                <div style="display:flex;gap:20px;" class="d-flex-content">
                                                    <select style="width:max-content;" name="filter" id=""
                                                        class="form-control">
                                                        <option value="">=== Pilih Data ===</option>
                                                        <option value="all"
                                                            {{ request('filter') == 'all' ? 'selected' : '' }}>
                                                            Semua Store
                                                        </option>
                                                        @foreach ($store as $shop)
                                                            <option value="{{ $shop->id }}"
                                                                {{ request('filter') == $shop->id ? 'selected' : '' }}>
                                                                {{ $shop->store_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-primary">Pilih</button>
                                                </div>
                                            </form>

                                            {{-- <div class="excel-file">
                                     <form action="{{ route('export_transaction_excel') }}" method="POST">
                                         @csrf
                                         <input type="hidden" name="filter_transaction"
                                             value="{{ request('filter_transaction') }}">
                                         <button type="submit" class="btn btn-success">
                                             <i class="fas fa-file-excel"></i>
                                             &nbsp; Excel
                                         </button>
                                     </form>
                                 </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <div wire:poll.keep.alive.2s>


                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                @foreach ($waste_category as $waste_type)
                                                    <th>{{ $waste_type->waste_type }}</th>
                                                @endforeach


                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($products as $prd)
                                                <tr>
                                                    <td>{{ $prd->product_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>






















                                {{-- <div>
                                        <div class="table-responsive">
                                            <table class="table" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        @if (!$user_permission_forbidden)
                                                            <th>Aksi</th>
                                                        @endif
                                                        <th>Kode Waste</th>
                                                        <th>Kode Produksi</th>
                                                        <th>Produk Daily</th>
                                                        <th>Produk</th>
                                                        <th>Tanggal Waste</th>
                                                        <th>Approved Oleh</th>
                                                        <th>Status</th>
                                                        <th>Store</th>
                                                        <th>Alasan</th>
                                                        <th>Created at</th>
                                                        <th>Created by</th>
                                                        <th>Updated at</th>
                                                        <th>Updated by</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($product_wastes as $raw)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            @if (!$user_permission_forbidden)
                                                                <td>
                                                                    <div style="display: flex; gap:10px;"
                                                                        class="btn-action">
                                                                        <a
                                                                            href="{{ route('waste-update', $raw->waste_code) }}"><i
                                                                                class="fas fa-edit"></i></a>

                                                                        <a href="#" data-toggle="modal"
                                                                            data-target="#deleteModal{{ $raw->waste_code }}"><i
                                                                                class="fas fa-trash"></i></a>
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#showRaw{{ $raw->waste_code }}">{{ $raw->waste_code }}</a>
                                                            </td>
                                                            <td>{{ $raw->production_code ?: '-' }}</td>
                                                            <td>{{ $raw->product_daily ?: '-' }}</td>
                                                            <td>
                                                                @if ($raw->production_code)
                                                                    {{ $raw->product_name }}
                                                                @else
                                                                    {{ $raw->daily_product_name }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $raw->waste_date }}</td>
                                                            <td>
                                                                @if ($raw->approved_by)
                                                                    {{ $raw->approved_by }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ $raw->status_name }}</td>
                                                            <td>
                                                                @if ($raw->production_store)
                                                                    {{ $raw->production_store }}
                                                                @else
                                                                    {{ $raw->daily_store }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($raw->reason)
                                                                    {{ $raw->reason }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ $raw->created_at }}</td>
                                                            <td>{{ $raw->created_by }}</td>
                                                            <td>{{ $raw->updated_at }}</td>
                                                            <td>{{ $raw->updated_by }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                {{-- @else
                                <div
                                    style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                    <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                        <img width="70" height="70"
                                            src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                        <div>
                                            <h3>Belum ada data Produk Waste</h3>
                                            @if (!$user_permission_forbidden)
                                                <p class="text-secondary">Tambah data Produk Waste</p>
                                                <a class="btn btn-primary" href="{{ 'product-waste-create' }}">Tambah
                                                    Produk
                                                    Waste</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif --}}

                            </div>
                        </div>

                    </div>
                </div>
            </main>

            {{-- Modal delete  --}}
            {{-- @foreach ($product_wastes as $waste)
                <div wire:ignore class="modal fade" id="deleteModal{{ $waste->waste_code }}" tabindex="-1"
                    role="dialog" aria-labelledby="exampleModalLabel{{ $waste->waste_code }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Hapus data Produk Waste</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Apakah anda yakin ingin menghapus data Produk Waste?</div>
                            <div class="modal-footer">
                                <form action="{{ route('waste-delete', $raw->waste_code) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}


            {{-- modal show description --}}

            {{-- @foreach ($product_wastes as $production)
        <div wire:ignore class="modal fade" id="addDescriptionModal{{ $production->production_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Berikan alasan produksi di Batalkan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('production_reason_cancelled', $production->production_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>Kode Produksi</strong></label>
                                <input type="text" value="{{ $production->production_code }}" class="form-control"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for=""><strong>Berikan alasan</strong></label>
                                <textarea name="description" class="form-control" id="" cols="30" rows="4">
                            </textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}

            {{-- end --}}

            {{-- modal show raw materials --}}



            {{-- Modal show Raw Materials --}}
            {{-- @foreach ($product_wastes as $waste)
                <div wire:ignore class="modal fade" id="showRaw{{ $waste->waste_code }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel{{ $waste->waste_code }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Produk Waste
                                    {{ $waste->product_name }}
                                </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table style="font-size: 14px;font-weight:600;color:black;" class="table">
                                    <th>No</th>
                                    <th>Tipe Waste</th>
                                    <th>Banyak</th>

                                    <?php
                                    $no = 1;
                                    ?>
                                    <tbody>
                                        @foreach ($product_wastes_detail as $raw)
                                            @if ($waste->waste_code == $raw->waste_code)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $raw->waste_type }}</td>
                                                    <td>{{ $raw->quantity }}</td>
                                                </tr>
                                            @else
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}

            {{-- Modal show change status target produksi --}}

            {{-- @foreach ($product_wastes as $production)
        <div wire:ignore class="modal fade" id="editStatus{{ $production->production_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah data Target Produksi Produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update_target_production', $production->production_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>Kode Produksi</strong></label>
                                <input type="text" value="{{ $production->production_code }}" class="form-control"
                                    readonly>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Target Total Produksi</strong></label>
                                <input type="text" value="{{ $production->target_total }}" class="form-control"
                                    readonly>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Total Produk Jadi</strong></label>
                                <input type="number" name="actual_quantity" value="{{ $production->actual_quantity }}"
                                    class="form-control">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Total Produk Gagal</strong></label>
                                <small>*Masukan 0 jika tidak ada</small>
                                <input type="number" name="reject_quantity"
                                    value="{{ $production->reject_quantity }}" class="form-control">
                            </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}

            {{-- Modal change status produksi --}}
            {{-- @foreach ($product_wastes as $production)
        <div wire:ignore class="modal fade" id="editStatusProduction{{ $production->production_code }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Status Produksi Produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update_production_status', $production->production_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>Kode Produksi</strong></label>
                                <input type="text" value="{{ $production->production_code }}"
                                    class="form-control" readonly>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Status Produksi saat ini</strong></label>
                                <input type="text" value="{{ $production->status }}" class="form-control"
                                    readonly>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for=""><strong>Status Produksi</strong></label>
                                <select name="status" class="form-control">
                                    <option value="">=== Pilih Status Produksi ===</option>
                                    @foreach ($status as $sts)
                                        <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}

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
            @elseif(Session::has('failed_message'))
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


        </div>
    </div>
</body>

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
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
