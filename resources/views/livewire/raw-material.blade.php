<title>
    @yield('title', 'Kencana Bakery - Master Data Bahan Baku')</title>
<div>
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
                        Master Data / <a href="{{ route('raw_material') }}">Bahan Baku</a>
                    </div>
                    <div style="display: flex;gap:10px;" class="flex-content">
                        @if ($module_documentation)
                            <div style="align-self: center; background: rgb(222, 222, 255);padding:8px; border-radius: 5px;"
                                class="documentation-module">
                                <a title="Dokumentasi Modul"
                                    href="{{ route('show_module_documentation', $module_documentation->url_path) }}">
                                    <i aria-label="Module Documentation" class="fa fa-file"></i>
                                </a>
                            </div>
                        @endif

                        <div style="display: flex; gap:10px;" class="flex-content">
                            @if ($raw_material->isNotEmpty())
                                @if (!$user_permission_forbidden)
                                    <div class="button-add-product">
                                        <a class="btn btn-info" href="{{ route('unit_material') }}">
                                            Satuan Unit</a>
                                    </div>
                                    <div class="button-add-product">
                                        <a class="btn btn-primary" href="{{ route('material_create') }}">Tambah Bahan
                                            Baku</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-header">

                    <a href="#" data-toggle="modal" data-target="#showInfoPrice"> <i
                            class="fa fa-info-circle"></i>
                        ketentuan harga bahan baku</a>


                    <hr>
                    <div style="font-size: 13px;" class="alert alert-info">
                        <ul>
                            <li>Bahan baku bisa dihapus jika bahan baku belum pernah digunakan untuk produksi produk
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($raw_material->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0" wire:ignore>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            @if (!$user_permission_forbidden)
                                                <th>Aksi</th>
                                            @endif
                                            <th>Detail Bahan Baku</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $no = 1;

                                        @endphp
                                        @foreach ($raw_material as $raw)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                @if (!$user_permission_forbidden)

                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Aksi</th>
                                                                 <td>
                                                                    <div style="display: flex; gap:10px;" class="btn-action">
                                                                        <a
                                                                            href="{{ route('material_update', $raw->material_code) }}"><i
                                                                                class="fa fa-edit"></i></a>

                                                                        @if ($checking_material_usages->where('material_code', $raw->material_code)->isEmpty())
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#deleteModal{{ $raw->material_code }}"><i
                                                                                    class="fa fa-trash"></i></a>
                                                                        @endif
                                                                    </div>
                                                                 </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Penggunaan</th>
                                                                <td> <a
                                                                        href="{{ route('raw_material_usages', $raw->material_code) }}"><i
                                                                            class="fas fa-eye"></i></a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Riawayat PO</th>
                                                                 <td> <a
                                                                        href="{{ route('history_raw_material', $raw->material_code) }}"><i
                                                                            class="fas fa-list"></i></a></td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                @endif


                                                <td>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Kode</th>
                                                                <td>{{ $raw->material_code }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Bahan Baku</th>
                                                                <td>{{ $raw->material_name }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Stok</th>
                                                                <td>{{ $raw->quantity ?: '-' }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Harga</th>
                                                                 <td>
                                                                    @if ($raw->price == null)
                                                                        <span>-</span>
                                                                    @else
                                                                        {{ 'Rp.' . number_format($raw->price) }}
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Purchase Unit</th>
                                                                   <td>{{ $raw->purchase_unit }}</td>
                                                            </tr>

                                                            
                                                            <tr>
                                                                <th>Inventory Unit</th>
                                                                   <td>{{ $raw->inventory_unit }}</td>
                                                            </tr>

                                                            
                                                            <tr>
                                                                <th>Status</th>
                                                                    <td>
                                                                        @if ($raw->status_name == 'Ready')
                                                                            <p class="text-success">Ready </p>
                                                                        @else
                                                                            <p class="text-danger">Kosong </p>
                                                                        @endif
                                                                    </td>
                                                            </tr>

                                                             <tr>
                                                                <th>Kategori</th>
                                                                   <td>{{ $raw->category_name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tanggal Expired</th>
                                                                   <td>{{ $raw->expired_date }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </td>
                                                <td>
                                                     <table class="table table-bordered">
                                                         <tbody>

                                                            <tr>
                                                                <th>Dibuat pada</th>
                                                                   <td>{{ $raw->created_at }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Dibuat oleh</th>
                                                                   <td>{{ $raw->created_by }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Diubah pada</th>
                                                                   <td>{{ $raw->updated_at }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Diubah oleh</th>
                                                                   <td>{{ $raw->created_by }}</td>
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
                            <div
                                style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                    <img width="70" height="70"
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div>
                                        <h3>Belum ada Bahan Baku</h3>
                                        @if (!$user_permission_forbidden)
                                            <p class="text-secondary">Tambah data Bahan Baku</p>
                                            <a class="btn btn-primary" href="{{ 'material_create' }}">Tambah Bahan
                                                Baku</a>
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



    @foreach ($raw_material_usages_store as $raw)
        <div wire:ignore class="modal fade" id="showUsedStore{{ $raw->material_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $raw->material_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Penggunaan Bahan Baku by Store</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <p[] class="modal-title" id="exampleModalLabel">Bahan Baku :
                            <strong>{{ $raw->material_name }}</strong></p>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table" id="dataTable" width="100%" cellspacing="0" wire:ignore>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Store</th>
                                        <th>Total Penggunaan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $no = 1; @endphp

                                    @foreach ($store as $st)
                                        @php
                                            $used = $raw_material_usages_store
                                                ->where('material_code', $raw->material_code)
                                                ->where('store_name', $st->store_name)
                                                ->first();
                                        @endphp

                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $st->store_name }}</td>
                                            <td>{{ $used->total_used ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div wire:ignore class="modal fade" id="showInfoPrice" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <h5>Harga Bahan Baku mengikuti harga terbaru saat Purchase Order bahan baku.</h5>
                </div>
            </div>
        </div>
    </div>


    @foreach ($raw_material as $raw)
        <div wire:ignore class="modal fade" id="deleteModal{{ $raw->material_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $raw->material_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data Bahan Baku</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus data Bahan Baku
                        {{ $raw->material_code . '  - ' . $raw->material_name }}?
                    </div>
                    <div class="modal-footer">
                        <form class="form-delete" action="{{ route('material_delete', $raw->material_code) }}"
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
