<title>
    @yield('title', 'Kencana Bakery - Master Data Produk')</title>
<div>
    <main>

        <div style="font-size: 14px;" class="alert alert-info">
            <ul>
                <li>Data Master Produk hanya bisa dihapus jika Product belum masuk ke Master Data Product Daily & Produk
                    belum pernah dipakai transaksi</li>
            </ul>
        </div>
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
                                            <th>Product Daily</th>
                                            <th>Kategori</th>
                                            @if ($products->first()->product_variant)
                                                <th>Harga Variant</th>
                                            @endif
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
                                                    <div style="display: block;" class="btn-action">
                                                        <div style="display: flex; gap:10px; margin-bottom: 20px;"
                                                            class="flex-btn-add">
                                                            <a
                                                                href="{{ route('product_update', $product->product_code) }}"><i
                                                                    class="fas fa-edit"></i></a>

                                                            @if ($product->status == 'Ready')
                                                            @else
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#deleteModal{{ $product->product_code }}"><i
                                                                        class="fas fa-trash"></i></a>
                                                            @endif
                                                        </div>
                                                        @if ($product->product_variant == 'Y')
                                                            <div class="text-primary">
                                                                <a style="font-size:13px; color:rgb(49, 0, 243);width: 100%;"
                                                                    href="{{ route('add_product_variant', $product->product_code) }}">
                                                                    <i class="fa fa-plus"></i>variant
                                                                </a>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $product_image = DB::table('product_images')
                                                            ->where('product_code', $product->product_code)
                                                            ->first();

                                                        $product_variants = DB::table('product_variant as pv')
                                                            ->join('products as p', 'pv.product', '=', 'p.product_code')
                                                            ->where('product', $product->product_code)
                                                            ->get();

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
                                                    @if ($product->status == null)
                                                        <span class="text-danger">Tidak</span>
                                                    @else
                                                        <span class="text-success">Ya</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->category }}</td>

                                                @if ($product_variants->isNotEmpty())
                                                    <td>

                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                <th>Aksi</th>
                                                                <th>Tipe Variant</th>
                                                                <th>Harga Variant </th>
                                                                <th>Discount</th>
                                                                <th>Harga setelah discount</th>
                                                            </tr>

                                                            @foreach ($product_variants as $prd)
                                                                @if ($product->product_code == $prd->product)
                                                                    <tr>
                                                                        <td>


                                                                            <a
                                                                                href="{{ route('update_variant', $prd->variant_code) }}">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>


                                                                            <a href="#"
                                                                                onclick="openDeleteModal('{{ $prd->variant_code }}', '{{ $product->product }}', '{{ $prd->variant_type }}')">
                                                                                <i class="fas fa-trash"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td>{{ $prd->variant_type }}</td>
                                                                        <td>{{ 'Rp.' . number_format($prd->variant_price) }}
                                                                        </td>
                                                                        <td>
                                                                            @if ($prd->variant_discount == 0)
                                                                                -
                                                                            @else
                                                                                {{ $prd->variant_discount . '%' }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($prd->variant_price_after_discount == 0)
                                                                                -
                                                                            @else
                                                                                {{ 'Rp.' . number_format($prd->variant_price_after_discount) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                        </table>
                                                        {{-- <a class="text-info" href="#" data-toggle="modal"
                                                            data-target="#editStatus{{ $product->production_code }}">Ubah
                                                            Target</a> --}}

                                                    </td>
                                                @elseif($product->product_variant == null)
                                                    <td>
                                                        @if ($product->price)
                                                            {{ 'Rp.' . number_format($product->price) }}
                                                        @else
                                                            <small class="text-danger"> harga produk belum ada
                                                            </small>
                                                        @endif
                                                    </td>
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
                                                @else
                                                    <td>
                                                        @if ($product->price)
                                                            {{ 'Rp.' . number_format($product->price) }}
                                                        @else
                                                            <small class="text-danger"> harga untuk variant produk ini
                                                                belum diinput</small>
                                                            <br>
                                                            <br>
                                                            <form
                                                                action="{{ route('delete_variant_product', $product->product_code) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="text" name="product_code"
                                                                    value="{{ $product->product_code }}" hidden>
                                                                <button style="background: none; border:none;color:red;"
                                                                    type="submit"><i class="fa fa-trash"></i>Hapus
                                                                    Produk Variant</button>
                                                            </form>
                                                        @endif
                                                    </td>
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
                                                @endif

                                                <td>{{ $product->product_weight . ' ' . $product->product_weight_type }}
                                                </td>
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
                    @if ($product->product_variant)
                        <div class="modal-body">Apakah anda yakin ingin menghapus produk
                            {{ $product->product_code . ' - ' . $product->product }} ? <br>
                            Produk ini juga terdapat beberapa Variant.</div>
                    @else
                        <div class="modal-body">Apakah anda yakin ingin menghapus produk
                            {{ $product->product_code . ' - ' . $product->product }} ?</div>
                    @endif
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

    {{-- show Modal delete Variant --}}
    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Variant Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="deleteVariantBody">
                    <!-- Isi akan di-set via JS -->
                </div>
                <div class="modal-footer">
                    <form id="deleteVariantForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end --}}

</div>

{{-- end --}}

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

<script>
    function openDeleteModal(variantCode, productName, variantType) {
        // Set body
        document.getElementById('deleteVariantBody').innerText =
            `Apakah anda yakin ingin menghapus Variant Produk ${variantCode} - ${productName} - ${variantType}?`;

        // Set action form
        document.getElementById('deleteVariantForm').action = `/delete_variant/${variantCode}`;

        // Tampilkan modal (Bootstrap 4)
        $('#deleteVariantModal').modal('show');
    }
</script>
