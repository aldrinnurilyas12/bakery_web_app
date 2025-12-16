<div>
    <main>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Daily Produk</a>
                    </div>

                    @if ($daily_products->isNotEmpty())
                        <div class="button-add-product">
                            <a class="btn btn-primary" href="{{ route('daily_product_create') }}">Tambah Produk</a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($daily_products->isNotEmpty())

                            <div style="display: flex; flex-wrap: wrap; gap:10px;">
                                @foreach ($daily_products as $product)
                                    <div class="card bg-light text-black mb-4">
                                        <div style="height:max-content;display: flex;align-items: center; gap:10px;font-weight: bold;"
                                            class="card-body">
                                            <div style="display: flex; gap:10px;" class="image-content">
                                                @php
                                                    $product_image = DB::table('product_images')
                                                        ->where('product_code', $product->product_code)
                                                        ->first();

                                                @endphp
                                                @if ($product_image && $product_image->images)
                                                    <img width="90" height="90"
                                                        src="{{ 'storage/' . $product_image->images }}" alt="">
                                                @else
                                                    <p>-</p>
                                                @endif
                                                <div class="content-text">
                                                    <div style="width: 200px;" class="title-text">
                                                        <h5 style="font-size:15px; margin:0;">{{ $product->product }}
                                                        </h5>
                                                        <p
                                                            style="font-size: 12px; font-style:oblique;font-weight:normal; color:gray; margin-bottom:4px;">
                                                            {{ $product->category }}</p>
                                                        @if ($product->price_after_discount == 0)
                                                            <p class="price" style="margin: 0;">
                                                                {{ 'Rp' . number_format($product->price) }}</p>
                                                        @else
                                                            <p class="price" style="margin: 0;">
                                                                {{ 'Rp' . number_format($product->price_after_discount) }}
                                                            </p>
                                                            <div class="price-discount">
                                                                <small
                                                                    style="font-size: 13px;color:gray; font-weight:normal;text-decoration: line-through;">{{ 'Rp' . number_format($product->price) }}</small>
                                                                <small
                                                                    class="text-danger">-{{ $product->discount . '%' }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p
                                                        style="width: 150px;font-size: 13px;color:gray; font-weight: normal;display:flex;flex-wrap: wrap;margin:0;">
                                                        Point:
                                                        {{ $product->point }} &nbsp;
                                                        <span>Stok:
                                                            {{ $product->stock_available }}</span> &nbsp;
                                                        <span>Berat:
                                                            {{ $product->product_weight }}</span>
                                                    </p>
                                                    <div style="font-size: 13px; font-weight: 500;" class="date">


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <a class="small text-black"
                                                href="{{ route('dailyproduct_update', $product->product_code) }}">Edit</a>

                                            <form method="POST"
                                                action="{{ route('dailyproduct_delete', $product->product_code) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Foto</th>
                                            <th>Kode Produk</th>
                                            <th>Produk</th>
                                            <th>Status</th>
                                            <th>Point</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Harga Setelah Diskon</th>
                                            <th>Stok</th>
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
                                        @foreach ($daily_products as $product)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <div style="display: flex; gap:10px;" class="btn-action">
                                                        <a class="btn btn-primary"
                                                            href="{{ route('dailyproduct_update', $product->product_code) }}">Edit</a>


                                                        <form
                                                            action="{{ route('dailyproduct_delete', $product->product_code) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
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
                                                <td>
                                                    @if ($product->status == 'Ready')
                                                        <p class="text-success">Ready </p>
                                                    @else
                                                        <p class="text-danger">Kosong </p>
                                                    @endif
                                                </td>
                                                <td>{{ $product->point }}</td>
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


                                                <td>{{ $product->stock_available }}</td>
                                                <td>{{ $product->product_weight }}</td>
                                                <td>{{ $product->created_at }}</td>
                                                <td>{{ $product->created_by }}</td>
                                                <td>{{ $product->updated_at }}</td>
                                                <td>{{ $product->updated_by }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> --}}
                        @else
                            <div
                                style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                    <img width="70" height="70"
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div>
                                        <h3>Belum ada produk</h3>
                                        <p class="text-secondary">Tambah data produk anda</p>
                                        <a class="btn btn-primary" href="{{ 'dailyproduct_create' }}">Tambah Produk</a>
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
