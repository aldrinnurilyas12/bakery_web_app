<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Production</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>


<div>
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
                        Master Data / <a href="{{ route('production_products') }}">Produksi Produk</a>
                    </div>

                    @if ($production_products->isNotEmpty())
                        @if (!$user_permission_forbidden)
                            <div class="button-add-product">
                                <a class="btn btn-primary" href="{{ route('production_create') }}">Tambah Produksi
                                    Produk</a>
                            </div>
                        @endif
                    @endif
                </div>
                @if (!$filter_forbidden_access)
                    <div class="card-header">
                        <div class="title">
                            <div class="filter-data">
                                <label for=""><strong>Filter Produksi Produk</strong></label>
                                <br>
                                <div style="display: flex; gap:10px;margin-top:5px;" class="d-flex-container-filter">
                                    <form action="{{ route('filter_production_product') }}" method="GET">
                                        <div style="display:flex;gap:20px;" class="d-flex-content">
                                            <div class="filter-date">
                                                <select style="width:max-content;" name="filter_date" id=""
                                                    class="form-control">
                                                    <option value="">=== Pilih Data ===</option>
                                                    <option value="today"
                                                        {{ request('filter_date') == 'today' ? 'selected' : '' }}>
                                                        Hari ini</option>
                                                    <option value="week"
                                                        {{ request('filter_date') == 'week' ? 'selected' : '' }}>
                                                        Minggu ini</option>
                                                    <option value="month"
                                                        {{ request('filter_date') == 'month' ? 'selected' : '' }}>
                                                        Bulan ini</option>
                                                    <option value="year"
                                                        {{ request('filter_date') == 'year' ? 'selected' : '' }}>
                                                        Tahun ini</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-primary">Pilih</button>
                                        </div>
                                    </form>

                                    {{-- <div class="excel-file">
                                     <form action="{{ route('export_transaction_excel') }}" method="POST">
                                         @csrf
                                         <input type="hidden" name="filter_date"
                                             value="{{ request('filter_date') }}">
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

                        @if ($production_products->isNotEmpty())
                            <div>
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Bahan Baku</th>
                                                <th>Kode Produksi</th>
                                                <th>Biaya Produksi</th>
                                                <th>Target Produksi Produk</th>
                                                <th>Tipe Produksi</th>
                                                <th>Status</th>
                                                <th>Deskripsi</th>
                                                <th>Tanggal Produksi</th>
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
                                            @foreach ($production_products as $raw)
                                                <tr>
                                                    <td>{{ $no++ }}</td>

                                                    <td>
                                                        <a
                                                            href="{{ route('production-detail', $raw->production_code) }}"><i
                                                                class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#showRaw{{ $raw->production_code }}"><i
                                                                class="fa fa-eye"></i></a>
                                                    </td>
                                                    <td>{{ $raw->production_code }}</td>

                                                    <td>{{ 'Rp.' . number_format($raw->total_cost) }}</td>

                                                    <td>

                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Total Produk</th>
                                                                <th>Total Target </th>
                                                                <th>Total Actual</th>
                                                                <th>Total Reject</th>

                                                            </tr>

                                                            <tr>
                                                                <td>{{ $raw->product_total }}</td>
                                                                <td>{{ $raw->target_total }}</td>
                                                                <td>
                                                                    @if ($raw->actual_quantity)
                                                                        {{ $raw->actual_quantity }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($raw->reject_quantity)
                                                                        {{ $raw->reject_quantity }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                        </table>


                                                    </td>
                                                    <td>
                                                        @if ($raw->production_type == 'per_day')
                                                            Per Hari
                                                        @elseif($raw->production_type == 'per_week')
                                                            Per Minggu
                                                        @else
                                                            Per Bulan
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($raw->status == 'Scheduled')
                                                            <span class="text-secondary">{{ $raw->status }}</span>
                                                        @elseif($raw->status == 'Completed')
                                                            <span class="text-success">{{ $raw->status }}</span>
                                                        @elseif($raw->status == 'Cancelled')
                                                            <span class="text-danger">{{ $raw->status }}</span>
                                                            @if ($raw->description)
                                                            @else
                                                                <a style="font-size:12px;" href="#"
                                                                    data-toggle="modal"
                                                                    data-target="#addDescriptionModal{{ $raw->production_code }}"><i
                                                                        class="fas fa-edit"></i>Alasan</a>
                                                            @endif
                                                        @else
                                                            <span class="text-black">{{ $raw->status }}</span>
                                                        @endif
                                                        <br>

                                                        @if ($raw->status == 'Completed')
                                                        @else
                                                            @if ($raw->status == 'Cancelled')
                                                            @else
                                                                <a style="font-size: 12px;color:black;"
                                                                    class="text-info" href="#" data-toggle="modal"
                                                                    data-target="#editStatusProduction{{ $raw->production_code }}">Ubah
                                                                    Status</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($raw->description)
                                                            {{ $raw->description }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $raw->production_date }}</td>
                                                    <td>{{ $raw->created_at }}</td>
                                                    <td>{{ $raw->created_by }}</td>
                                                    <td>{{ $raw->updated_at }}</td>
                                                    <td>{{ $raw->updated_by }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div
                                style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                    <img width="70" height="70"
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div>
                                        <h3>Belum ada data Produksi Produk</h3>
                                        @if (!$user_permission_forbidden)
                                            <a class="btn btn-primary" href="{{ 'production_create' }}">Tambah
                                                Produksi
                                                Produk</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Modal delete  --}}
    @foreach ($production_products as $production)
        <div wire:ignore class="modal fade" id="deleteModal{{ $production->production_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data Produksi Produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus data Produksi Produk
                        [{{ $production->production_code }}]?</div>
                    <div class="modal-footer">
                        <form class="form-delete" action="{{ route('production_delete', $raw->production_code) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="btn-delete-general" type="submit" class="btn-general-delete"><span
                                    class="btn-text">Hapus</span>
                                <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    {{-- modal show description --}}

    @foreach ($production_products as $production)
        <div wire:ignore class="modal fade" id="addDescriptionModal{{ $production->production_code }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Berikan alasan produksi di Batalkan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formGeneralMaster"
                            action="{{ route('production_reason_cancelled', $production->production_code) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for=""><strong>Kode Produksi</strong></label>
                                <input type="text" value="{{ $production->production_code }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for=""><strong>Berikan alasan</strong></label>
                                <textarea name="description" class="form-control" id="" cols="30" rows="4">
                            </textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- end --}}

    {{-- modal show raw materials --}}

    @php

        $raw_material = DB::table('raw_material_usages as r')
            ->leftJoin('raw_material as rw', 'r.raw_material', '=', 'rw.material_code')
            ->get();

        $total_raw_usage = DB::table('raw_material_usages as r')
            ->leftJoin('raw_material as rw', 'r.raw_material', '=', 'rw.material_code')
            ->count();
    @endphp

    {{-- Modal show Raw Materials --}}
    @foreach ($production_products as $production)
        <div wire:ignore class="modal fade" id="showRaw{{ $production->production_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $production->production_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data Produksi Produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table style="font-size: 14px;font-weight:600;color:black;" class="table">
                            <th>No</th>
                            <th>Bahan Baku</th>
                            <th>Penggunaan</th>

                            <?php
                            $no = 1;
                            ?>
                            <tbody>
                                @foreach ($raw_material as $raw)
                                    @if ($production->production_code == $raw->production_code)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $raw->material_name }}</td>
                                            <td>{{ $raw->quantity_used . ' ' . $raw->material_type }}</td>
                                        </tr>
                                    @else
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="display:inline-block;" class="modal-footer">
                        <div class="footer-info">

                            @php
                                $total_raw_usage = DB::table('raw_material_usages as r')
                                    ->leftJoin('raw_material as rw', 'r.raw_material', '=', 'rw.material_code')
                                    ->where('r.production_code', '=', $production->production_code)
                                    ->count();
                            @endphp
                            @if ($total_raw_usage)
                                <p>Total Penggunaan Bahan Baku : <strong> {{ $total_raw_usage }}
                                    </strong>
                                    Bahan Baku
                                </p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    {{-- Modal change status produksi --}}
    @foreach ($production_products as $production)
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
                        <form class="form-general"
                            action="{{ route('update_production_status', $production->production_code) }}"
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
                                <select name="status" class="form-control" required>
                                    <option value="">=== Pilih Status Produksi ===</option>
                                    @foreach ($status as $sts)
                                        <option value="{{ $sts->id }}">{{ $sts->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn-general" type="submit">
                            <span class="btn-text">Ubah status produksi</span>
                            <span class="spinner"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
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

</div>
