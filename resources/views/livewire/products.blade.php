<title>
    @yield('title', 'Kencana Bakery - Master Data Produk')</title>
<div>
    <main>
        @php
            $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
            $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager', 'Casheer']);
        @endphp
        <div style="font-size: 14px;" class="alert alert-info">
            <ul>
                <li>Data Master Produk hanya bisa dihapus jika Product belum masuk ke Master Data Product Daily & Produk
                    belum pernah dipakai transaksi</li>
                <li>Harga hanya bisa di input ketika HPP produk sudah ada</li>
            </ul>
        </div>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Item</a>
                    </div>

                    <div style="display: flex;gap:10px;" class="flex-content">

                        @if ($products->isNotEmpty())
                            @if ($module_documentation)
                                <div style="align-self: center; background: rgb(222, 222, 255);padding:8px; border-radius: 5px;"
                                    class="documentation-module">
                                    <a title="Dokumentasi Modul"
                                        href="{{ route('show_module_documentation', $module_documentation->url_path) }}">
                                        <i aria-label="Module Documentation" class="fa fa-file"></i>
                                    </a>
                                </div>
                            @endif
                            @if (!$user_permission_forbidden)
                                <div class="button-add-product">
                                    <a class="btn btn-primary" href="{{ route('product_create') }}">Tambah Item</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($products->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            @if (!$user_permission_forbidden)
                                                <th>Aksi</th>
                                            @endif
                                            <th>Detail Produk</th>
                                            @if ($products->first()->product_variant == 'Y')
                                                <th>Harga Variant</th>
                                            @endif
                                            <th>Harga</th>
                                            <th>Produk Point</th>
                                            <th>Review & Rating</th>
                                            <th>Akses oleh</th>
                                           
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
                                                    @php
                                                        $product_image = DB::table('product_images')
                                                            ->where('product_code', $product->product_code)
                                                            ->first();

                                                        $product_variants = DB::table('product_variant as pv')
                                                            ->leftjoin(
                                                                'products as p',
                                                                'pv.product',
                                                                '=',
                                                                'p.product_code',
                                                            )
                                                            ->leftjoin(
                                                                'variant_category as vc',
                                                                'pv.variant_type',
                                                                '=',
                                                                'vc.id',
                                                            )
                                                            ->select(
                                                                'p.product_code',
                                                                'p.product_name',
                                                                'pv.product as product',
                                                                'pv.variant_code',
                                                                'pv.variant_price',
                                                                'pv.variant_discount',
                                                                'pv.variant_price_after_discount',
                                                                'pv.price_effective_from',
                                                                'vc.name as variant_type',
                                                            )
                                                            ->where('pv.product', $product->product_code)
                                                            ->get();

                                                            $product_price_history = DB::table('product_price_history')
                                                                        ->pluck('product');

                                                    @endphp
                                                    @if ($product_image && $product_image->images)
                                                        <img width="100" height="100"
                                                            src="{{ 'storage/' . $product_image->images }}"
                                                            alt="">
                                                    @else
                                                        <p>-</p>
                                                    @endif
                                                </td>
                                                @if (!$user_permission_forbidden)
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <th>Edit</th>
                                                                    <td> <a
                                                                            href="{{ route('product_update', $product->product_code) }}"><i
                                                                                class="fas fa-edit"></i></a>

                                                                        @if ($product->transaction_status == 'N')
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#deleteModal{{ $product->product_code }}"><i
                                                                                    class="fas fa-trash"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Bill of Material </th>
                                                                    <td style="display:flex; justify-content: center;">
                                                                        @if ($product->bill_of_material == 'Y')
                                                                            <div class="show-bom">
                                                                                <a href="{{ route('bill-of-material', $product->product_code) }}"><i
                                                                                        class="fas fa-eye"></i></a>
                                                                            </div>
                                                                        @elseif($product->bill_of_material == 'N')
                                                                            <div class="text-primary">
                                                                                <a
                                                                                    href="{{ route('add_ingredients', $product->product_code) }}">
                                                                                    <i class="fa fa-plus-square"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                    @if($product->product_variant == 'Y')

                                                                    @else
                                                                        <tr>
                                                                            <th>Harga</th>
                                                                            <td style="display:flex; justify-content: center;">
                                                                                @if($product->hpp)
                                                                                    @if($product->price)
                                                                                        <a href="{{ route('product_price_update', $product->product_code) }}">
                                                                                            <i class="fas fa-edit"></i> ubah
                                                                                        </a>
                                                                                    @else
                                                                                        <a href="{{ route('product_price', $product->product_code) }}">
                                                                                            <i class="fa fa-plus-square"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                @else
                                                                                    <span>-</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    <tr>
                                                                        <th>Status Produk</th>
                                                                        <td>
                                                                            @if($product->product_status == 'Active')
                                                                            <span class="text-success">{{ $product->product_status ?: '-' }}</span>
                                                                                <a href="#" data-toggle="modal"
                                                                                 data-target="#changeStatus{{ $product->product_code }}"><i
                                                                                    class="fas fa-edit"></i>
                                                                                    ubah 
                                                                                </a>
                                                                            @else
                                                                             <span class="text-danger">{{ $product->product_status ?: '-' }}</span>
                                                                                 <a href="#" data-toggle="modal"
                                                                                     data-target="#changeStatus{{ $product->product_code }}"><i
                                                                                    class="fas fa-edit"></i>
                                                                                    ubah 
                                                                                </a>
                                                                            @endif

                                                                        </td>
                                                                    </tr>

                                                                    
                                                                    @if ($product->product_variant == 'Y')
                                                                        <tr>
                                                                            <th>Variant</th>
                                                                            @if ($product->product_variant == 'Y')
                                                                                <td>
                                                                                    @if ($product->product_variant == 'Y')
                                                                                        <div style="margin-bottom: 10px;text-align: center;"
                                                                                            class="text-primary">
                                                                                            <a style="font-size:13px;width: 100%;"
                                                                                                href="{{ route('add_product_variant', $product->product_code) }}">
                                                                                                <i class="fa fa-plus-square"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    @endif
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                @endif
                                               
                                                <td>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>SKU</th>
                                                                <td>{{ $product->product_code }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Nama Produk</th>
                                                                <td>{{ $product->product }}</td>
                                                            </tr>
                                                            

                                                            <tr>
                                                                <th>Kategori</th>
                                                                <td>{{ $product->category }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Tipe Produk</th>
                                                                <td>{{ $product->product_type }}</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <th>Berat Produk</th>
                                                                <td>{{ $product->product_weight . ' ' . $product->product_weight_type }}
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>

                                                @if ($product_variants->isNotEmpty())

                                                {{-- PERBAIKI BAGIAN INI --}}
                                                <td>

                                                        <table style="font-size: 14px; color:black;"
                                                            class="table table-bordered" id="dataTable" width="100%"
                                                            cellspacing="0">

                                                            <tr>
                                                                @if (!$user_permission_forbidden)
                                                                    <th>Aksi</th>
                                                                @endif
                                                                <th>Tipe Variant</th>
                                                                <th>Histori Harga</th>
                                                                <th>Harga Variant </th>
                                                                <th>Discount</th>
                                                                <th>Harga setelah discount</th>
                                                                
                                                            </tr>

                                                            @foreach ($product_variants as $prd)
                                                                @if ($product->product_code == $prd->product)
                                                                    <tr>
                                                                        @if (!$user_permission_forbidden)
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
                                                                        @endif

                                                                         <td>{{ $prd->variant_type }}</td>
                                                                        <td>
                                                                             @if($product_price_history->contains($product->product_code))
                                                                                <a
                                                                                    href="{{ route('product-price-history', [
                                                                                        'product_code' => $prd->product_code,
                                                                                        'variant' => $prd->variant_code,
                                                                                    ]) }}"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <span>-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ 'Rp.' . number_format($prd->variant_price) }}
                                                                        </td>
                                                                        @if ($prd->variant_discount)
                                                                            <td>
                                                                                {{ $prd->variant_discount . '%' }}
                                                                            </td>
                                                                            <td>
                                                                                {{ 'Rp.' . number_format($prd->variant_price_after_discount) }}
                                                                            </td>
                                                                        @elseif($prd->variant_discount == 0)
                                                                            <td>
                                                                               -
                                                                            </td>
                                                                            <td>
                                                                                -
                                                                            </td>
                                                                        @endif
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
                                                        <table style="font-size: 14px; color:black;"
                                                                        class="table table-bordered" id="dataTable" width="100%"
                                                                        cellspacing="0">
                                                                <tbody>
                                                                            
                                                                    <tr>
                                                                    <th>Harga</th>
                                                                        <td>
                                                                            @if ($product->price)
                                                                                {{ 'Rp.' . number_format($product->price) }}
                                                                            @else
                                                                                <span class="text-danger"> Harga produk belum ada
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>Diskon</th>
                                                                        <td>
                                                                            @if ($product->discount == 0)
                                                                                -
                                                                            @else
                                                                                {{ $product->discount . '%' }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>Harga setelah diskon</th>
                                                                        <td>
                                                                            @if ($product->price_after_discount == 0)
                                                                                -
                                                                            @else
                                                                                {{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>HPP</th>
                                                                            <td>
                                                                                @if($product->hpp)
                                                                                {{ 'Rp.'. number_format($product->hpp) }}
                                                                                @else
                                                                                <span class="text-danger">HPP belum ada untuk produk ini</span>
                                                                                @endif
                                                                            </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>Tanggal efektif harga</th>
                                                                            <td>
                                                                                {{ $product->price_effective_from ?: '-' }}
                                                                            </td>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                        </table>
                                                    </td>
                                                    
                                                @else
                                                    <td>
                                                        <table style="font-size: 14px; color:black;"
                                                                        class="table table-bordered" id="dataTable" width="100%"
                                                                        cellspacing="0">
                                                                <tbody>

                                                                    <tr>
                                                                    <th>Histori Harga</th>
                                                                        <td>
                                                                            @if($product_price_history->contains($product->product_code))
                                                                                <a
                                                                                    href="{{ route('product-price-history', [
                                                                                        'product_code' => $product->product_code,
                                                                                        'variant' => null,
                                                                                    ]) }}"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                            @else
                                                                                <span>-</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
    
                                                                        <tr>
                                                                        <th>Harga</th>
                                                                            <td>
                                                                                @if ($product->price)
                                                                                    {{ 'Rp.' . number_format($product->price) }}
                                                                                @else
                                                                                    @if($product->product_variant == 'Y')
                                                                                        <span class="text-danger"> Harga untuk
                                                                                            produk variant ini
                                                                                            belum diinput</span>
                                                                                        <br>
                                                                                        <br>
                                                                                        <form
                                                                                            action="{{ route('delete_variant_product', $product->product_code) }}"
                                                                                            method="POST">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <input type="text" name="product_code"
                                                                                                value="{{ $product->product_code }}"
                                                                                                hidden>
                                                                                            <button
                                                                                                style="background: none; border:none;color:red;"
                                                                                                type="submit"><i
                                                                                                    class="fa fa-trash"></i>Hapus
                                                                                                Produk Variant</button>
                                                                                        </form>
                                                                                    @else
                                                                                        <span class="text-danger"> Harga untuk
                                                                                            produk ini
                                                                                            belum diinput</span>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    
                                                                    @if ($product->discount)            
                                                                        <tr>
                                                                            <th>Diskon</th>
                                                                            <td>
                                                                                @if ($product->discount == 0)
                                                                                    -
                                                                                @else
                                                                                    {{ $product->discount . '%' }}
                                                                                @endif
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>Harga setelah diskon</th>
                                                                            <td>
                                                                                @if ($product->price_after_discount == 0)
                                                                                    -
                                                                                @else
                                                                                    {{ 'Rp.' . number_format($product->price_after_discount) }}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif

                                                                    <tr>
                                                                        <th>HPP</th>
                                                                        <td>
                                                                                @if($product->hpp)
                                                                                {{ 'Rp.'. number_format($product->hpp) }}
                                                                                @else
                                                                                <span class="text-danger">HPP belum ada untuk produk ini</span>
                                                                                @endif
                                                                            </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>Tanggal efektif harga</th>
                                                                            <td>
                                                                                {{ $product->price_effective_from ?: '-' }}
                                                                            </td>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                        </table>
                                                    </td>
                                                @endif

                                                @if ($product->point)
                                                    <td>
                                                        <table  class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>Point</th>
                                                                    <td>{{ $product->point }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>{{ $product->point_status }}</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Tanggal Mulai</th>
                                                                    <td>{{ \Carbon\Carbon::parse($product->point_start_date)->format('Y-m-d') }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th>Tanggal Akhir</th>
                                                                    <td>{{ \Carbon\Carbon::parse($product->point_end_date)->format('Y-m-d') }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                @else
                                                    @if ($user_permission_forbidden)
                                                    <td>-</td>
                                                    @else
                                                        <td><a class="btn btn-primary" href="{{ route('product_update', $product->product_code) }}"><i class="fa fa-plus-square"></i> Point</a></td>
                                                    @endif
                                                @endif

                                                <td>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Total Review</th>
                                                                <td>{{ $product->total_rating ?: '-' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Rata-rata Rating</th>
                                                                @if($product->rating > 0)
                                                                    <td> <img style="width:15px;height:15px;" src="{{ asset('assets\front_end\icons\star-icon.svg') }}" alt=""> &nbsp;{{ number_format($product->rating,1) ?: '-'}}</td>
                                                                @else
                                                                    <td>-</td>
                                                                @endif
                                                            </tr>

                                                             <tr>
                                                                <th>Aksi</th>
                                                                <td><a href="{{ route('product-review-detail', $product->product_code) }}" class="btn btn-primary">Detail</a> </td>
                                                            </tr>
                                                            

                                                        </tbody>
                                                    </table>
                                                </td>
                                                  

                                                <td>
                                                    <table  class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Dibuat pada</th>
                                                                <td>{{ $product->created_at }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Dibuat oleh</th>
                                                                <td>{{ $product->created_by }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Diubah pada</th>
                                                                <td>{{ $product->updated_at }}</td>
                                                            </tr>

                                                             <tr>
                                                                <th>Diubah oleh</th>
                                                                <td>{{ $product->updated_by }}</td>
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
                                        <h3>Belum ada produk</h3>
                                        @if (!$user_permission_forbidden)
                                            <p class="text-secondary">Tambah data produk anda</p>
                                            <a class="btn btn-primary" href="{{ 'product_create' }}">Tambah
                                                Item</a>
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

    @foreach ($products as $product)
        <div wire:ignore class="modal fade" id="deleteModal{{ $product->product_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $product->product_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data produk</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    @if ($product->product_variant == 'Y')
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



    @foreach ($products as $product)
        <div wire:ignore class="modal fade" id="changeStatus{{ $product->product_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $product->product_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah status produk &nbsp; <span style="font-weight: bold;">{{ $product->product }}</span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                      <form action="{{ route('update_status_product', $product->product_code) }}" method="POST">
                         @csrf
                        @method('PUT')
                        <div class="modal-body">

                            @if($product->product_status == 'Active')
                                <input name="product_status" value="8" type="checkbox" required> Nonaktifkan
                            @else
                                <input name="product_status" value="7" type="checkbox" required> Nonaktifkan
                            @endif

                        </div>
                  
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Simpan data</button>
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
