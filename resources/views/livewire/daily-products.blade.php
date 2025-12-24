 <title>@yield('title', 'Kencana Bakery - Produk Daily')</title>
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
                                                         src="{{ url('storage/' . $product_image->images) }}"
                                                         alt="">
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

                                             <a class="btn btn-danger" href="#" data-toggle="modal"
                                                 data-target="#deleteModal{{ $product->product_code }}">Hapus</a>
                                         </div>
                                     </div>
                                 @endforeach
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
                                         <a class="btn btn-primary" href="{{ 'dailyproduct_create' }}">Tambah
                                             Produk</a>
                                     </div>
                                 </div>
                             </div>
                         @endif

                     </div>
                 </div>

             </div>
         </div>
     </main>

     @foreach ($daily_products as $product)
         <div wire:ignore class="modal fade" id="deleteModal{{ $product->product_code }}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel{{ $product->product_code }}" aria-hidden="true">
             <div class="modal-dialog" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Hapus data daily produk</h5>
                         <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">×</span>
                         </button>
                     </div>
                     <div class="modal-body">Apakah anda yakin ingin menghapus produk {{ $product->product }} ?</div>
                     <div class="modal-footer">
                         <form method="POST" action="{{ route('dailyproduct_delete', $product->product_code) }}">
                             @csrf
                             @method('DELETE')
                             <button class="btn btn-danger" type="submit">Hapus</button>
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
