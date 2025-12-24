<title>@yield('title', 'Kencana Bakery - Master Data Bahan Baku')</title>
<div>
    <main>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('raw_material') }}">Bahan Baku</a>
                    </div>

                    @if ($raw_material->isNotEmpty())
                        <div class="button-add-product">
                            <a class="btn btn-primary" href="{{ route('material_create') }}">Tambah Bahan Baku</a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($raw_material->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0" wire:ignore>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Kode Bahan Baku</th>
                                            <th>Bahan Baku</th>
                                            <th>Stok</th>
                                            <th>Massa</th>
                                            <th>Status</th>
                                            <th>Harga</th>
                                            <th>Kategori</th>
                                            <th>Tanggal Expired</th>
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
                                        @foreach ($raw_material as $raw)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <div style="display: flex; gap:10px;" class="btn-action">
                                                        <a href="{{ route('material_update', $raw->material_code) }}"><i
                                                                class="fa fa-edit"></i></a>

                                                        <a href="#" data-toggle="modal"
                                                            data-target="#deleteModal{{ $raw->material_code }}"><i
                                                                class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                                <td>{{ $raw->material_code }}</td>
                                                <td>{{ $raw->material_name }}</td>
                                                <td>{{ $raw->quantity }}</td>
                                                <td>{{ $raw->material_type }}</td>
                                                <td>
                                                    @if ($raw->status == 4)
                                                        <p class="text-success">Ready </p>
                                                    @else
                                                        <p class="text-danger">Kosong </p>
                                                    @endif
                                                </td>
                                                <td>{{ 'Rp.' . number_format($raw->price) }}</td>
                                                <td>{{ $raw->category_name }}</td>
                                                <td>{{ $raw->expired_date }}</td>
                                                <td>{{ $raw->created_at }}</td>
                                                <td>{{ $raw->created_by }}</td>
                                                <td>{{ $raw->updated_at }}</td>
                                                <td>{{ $raw->updated_by }}</td>
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
                                        <p class="text-secondary">Tambah data Bahan Baku</p>
                                        <a class="btn btn-primary" href="{{ 'material_create' }}">Tambah Bahan
                                            Baku</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </main>

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
                        <form action="{{ route('material_delete', $raw->material_code) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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




</div>
