<div>
    <main>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Promo Campaign</a>
                    </div>

                    @if ($promo_campaign->isNotEmpty())
                        <div class="button-add-product">
                            <a class="btn btn-primary" href="{{ route('promo_create') }}">Tambah Promo</a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($promo_campaign->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Kode Promo</th>
                                            <th>Produk</th>
                                            <th>Kuota</th>
                                            <th>Status</th>
                                            <th>Min Transaksi</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal Awal</th>
                                            <th>Tanggal Akhir</th>
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
                                        @foreach ($promo_campaign as $promo)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <div style="display: flex; gap:10px;" class="btn-action">
                                                        <a class="btn btn-primary"
                                                            href="{{ route('promo_update', $promo->promo_code) }}">Edit</a>


                                                        <form action="{{ route('promo_delete', $promo->promo_code) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>{{ $promo->promo_code }}</td>
                                                <td>{{ $promo->product }}</td>
                                                <td>{{ $promo->quota }}</td>
                                                <td>
                                                    @if ($promo->status == 'Active')
                                                        <p class="text-success">Aktif </p>
                                                    @else
                                                        <p class="text-danger"> </p>Tidak aktif
                                                    @endif
                                                </td>
                                                <td>{{ $promo->min_transaction }}</td>
                                                <td>
                                                    <textarea cols="14" rows="2" readonly>
                                                        {{ $promo->description }}
                                                    </textarea>
                                                </td>
                                                <td>{{ $promo->start_date }}</td>
                                                <td>{{ $promo->end_date }}</td>
                                                <td>{{ $promo->created_at }}</td>
                                                <td>{{ $promo->created_by }}</td>
                                                <td>{{ $promo->updated_at }}</td>
                                                <td>{{ $promo->updated_by }}</td>
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
                                        <h3>Belum ada data Promo Campaign</h3>
                                        <p class="text-secondary">Tambah data promo</p>
                                        <a class="btn btn-primary" href="{{ 'promo_create' }}">Tambah Promo</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </main>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus data produk</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger" type="submit">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
