<title>@yield('title', 'Kencana Bakery - Master Data Produk')</title>
<div>
    <main>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Item</a>
                    </div>

                    @if ($products->isNotEmpty())
                        <div class="button-add-product">
                            <a class="btn btn-primary" href="{{ route('product_create') }}">Tambah Item</a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($products->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Foto</th>
                                            <th>Kode Produk</th>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Harga Setelah Diskon</th>
                                            <th>Berat</th>
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
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <div style="display: flex; gap:10px;" class="btn-action">
                                                        <a href="{{ route('product_update', $product->product_code) }}"><i
                                                                class="fas fa-edit"></i></a>

                                                        <a href="#" data-toggle="modal"
                                                            data-target="#deleteModal{{ $product->product_code }}"><i
                                                                class="fas fa-trash"></i></a>


                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $product_image = DB::table('product_images')
                                                            ->where('product_code', $product->product_code)
                                                            ->first();

                                                    @endphp
                                                    @if ($product_image && $product_image->images)
                                                        <img width="100" height="100"
                                                            src="{{ 'storage/' . $product_image->images }}"
                                                            alt="">
                                                    @else
                                                        <p>-</p>
                                                    @endif
                                                </td>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $product->product }}</td>
                                                <td>{{ $product->category }}</td>
                                                <td>{{ 'Rp.' . number_format($product->price) }}</td>
                                                <td>
                                                    @if ($product->discount == 0)
                                                        -
                                                    @else
                                                        {{ $product->discount . '%' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($product->price_after_discount == 0)
                                                        -
                                                    @else
                                                        {{ 'Rp.' . number_format($product->price_after_discount) }}
                                                    @endif
                                                </td>
                                                <td>{{ $product->product_weight }}</td>
                                                <td>{{ $product->created_at }}</td>
                                                <td>{{ $product->created_by }}</td>
                                                <td>{{ $product->updated_at }}</td>
                                                <td>{{ $product->updated_by }}</td>
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
                                        <h3>Belum ada produk</h3>
                                        <p class="text-secondary">Tambah data produk anda</p>
                                        <a class="btn btn-primary" href="{{ 'product_create' }}">Tambah Item</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </main>

    @foreach ($products as $product)
        <div wire:ignore class="modal fade" id="deleteModal{{ $product->product_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $product->product_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus produk
                        {{ $product->product_code . ' - ' . $product->product }} ?</div>
                    <div class="modal-footer">
                        <form action="{{ route('master_products.destroy', $product->product_code) }}" method="POST">
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
