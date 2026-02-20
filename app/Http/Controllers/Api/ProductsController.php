<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Livewire\Products;
use App\Models\ProductsModel;
use App\Models\ProductImages;
use App\Models\ProductPointModel;
use App\Models\ProductsVariant;
use App\Models\VariantCategoryModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
   

    public function create()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $product_category = DB::table('product_category')->get();
        return view('layouts.main_pages.products.create.products_create', compact('product_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
   
      public function store(Request $request)
    {
        $request->validate([
            'product_name' =>'required',
            'category_id' =>'required',
            'product_weight' =>'required',
            'product_weight_type' =>'required',
            'product_variant' => 'required',
            'expired_date' =>'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:5000'
        ],
        [
            'product_name.required' => 'Nama Produk harus diisi',
            'category_id.required' => 'Kategori Produk harus diisi',
            'product_weight.required' => 'Berat Produk harus diisi',
            'product_weight_type.required' => 'Massa Produk harus diisi',
            'expired_date.required' => 'Tanggal Kadaluarsa Produk harus diisi',
            'images.required' => 'Gambar/Foto Produk harus ada'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        
        
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);
        $product_code = 'PR' .$request->category_id. $unique_code;

        $data = ProductsModel::create([
                'product_code'=> $product_code,
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'discount' => $request->discount,
                'price_after_discount' => $request->price_after_discount,
                'product_weight' => $request->product_weight,
                'product_weight_type' => $request->product_weight_type,
                'product_variant'  => $request->product_variant,
                'description' => $request->description,
                'expired_date' => $request->expired_date,
                'created_at' => now(),
                'created_by' => $created_by

        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $folderPath = 'product/' . $data->latest()->first()->id;
                $imagePath = $image->storeAs($folderPath, uniqid() . '.' . $image->getClientOriginalExtension(), 'public');

                ProductImages::create([
                    'product_code' => $product_code,
                    'images' => $imagePath,
                    'created_at' => now(),
                    'created_by' => $created_by
                            
                ]);
            }

        }

        ProductPointModel::create([
            'product' =>$data->product_code,
            'point' => $request->point,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 7
        ]);

        if($request->product_variant == 'Y'){
            session()->flash('message_success', 'Data produk berhasil disimpan!');
            return redirect()->route('add_product_variant', $data->product_code);
        }
        
        session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('products_data');
      
    }

    public function save_product_variant(Request $request) {

        $request->validate([
            'variant_type' => 'required',
            'variant_price' => 'required'
        ], 
        [
            'variant_type.required' => 'Tipe Variant harus diisi',
            'variant_price.required' => 'Harga Produk Variant harus diisi'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);
        $variant_code = 'VARIANT' . $unique_code;

            ProductsVariant::create([
                'variant_code' => $variant_code,
                'product' => $request->product,
                'variant_price' => $request->variant_price,
                'variant_discount' => $request->variant_discount,
                'variant_price_after_discount' => $request->variant_price_after_discount,
                'variant_type' => $request->variant_type,
                'created_at' => now()
            ]);


            session()->flash('message_success', 'Data produk variant berhasil disimpan!');
            return redirect()->back();
    }

    public function update_product_variant(Request $request) {
        ProductsModel::where('product_code', $request->product_code)->update([
            'product_variant' => null
        ]);
         session()->flash('message_success', 'Data produk variant berhasil dihapus!');
        return redirect()->route('products_data');
    }

    public function update_variant_layout(Request $request) {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }


        $variant = DB::table('product_variant as pv')
                ->leftJoin('v_products as p', 'pv.product', '=', 'p.product_code')
                ->where('variant_code', $request->variant_code)->first();
        return view('layouts.main_pages.products.edit.edit_variant_product', compact('variant'));
    }

    public function edit_variant(Request $request) {
        DB::table('product_variant')->where('variant_code', $request->variant_code)->update([
            'variant_price' => $request->variant_price,
            'variant_discount' => $request->variant_discount,
            'variant_price_after_discount' => $request->variant_price_after_discount
        ]);

        session()->flash('message_success', 'Data produk variant berhasil disimpan!');
        return redirect()->route('products_data');
    }

    public function delete_variant(Request $request) {
       $variant_code = ProductsVariant::where('variant_code', $request->variant_code)->first();

        if($variant_code){
            $variant_code->delete();
        }

        session()->flash('message_success', 'Data produk variant berhasil dihapus!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function product_update_layout(Request $request, $product_code)
    
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $authenticatedUser = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $products = DB::table('v_products')->where('product_code', $request->product_code)->first();
        $point = ProductPointModel::where('product', $product_code)->select('point')->first();
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $product_images = DB::table('product_images')->where('product_code', $request->product_code)->select('id','images' )->get();
        $products_category = DB::table('product_category')->get();
        $expired_date = Carbon::parse($products->expired_date);
        $point_start_date = Carbon::parse($products->point_start_date);
        $point_end_date = Carbon::parse($products->point_end_date);
        return view('layouts.main_pages.products.edit.products_edit', compact('products','point', 'products_category', 'status', 'expired_date', 'product_images', 'point_start_date', 'point_end_date'));
        
    }

    /**
     * Update the specified resource in storage.
     */
   

    public function update(Request $request, $product_code)
    {
     $request->validate([
            'product_name' =>'required',
            'category_id' =>'required',
            'price' =>'required',
            'product_weight' =>'required',
            'product_weight_type' =>'required',
            'expired_date' =>'required',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:5000'
        ],
        [
            'product_name.required' => 'Nama Produk harus diisi',
            'category_id.required' => 'Kategori Produk harus diisi',
            'price.required' => 'Harga Produk harus diisi',
            'product_weight.required' => 'Berat Produk harus diisi',
            'product_weight_type.required' => 'Massa Produk harus diisi',
            'expired_date.required' => 'Tanggal Kadaluarsa Produk harus diisi',
        ]);

    $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
    $product_id = DB::table('v_products')->select('id')->where('product_code', $request->product_code)->first();
    $point = ProductPointModel::where('product', $product_code)->select('point')->first();

    // Update data produk utama
    $data =  DB::table('products')
        ->where('product_code', $product_code)
        ->update([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'discount' => $request->discount,
            'price_after_discount' => $request->price_after_discount,
            'product_weight' => $request->product_weight,
            'product_weight_type' => $request->product_weight_type,
            'product_variant'  => $request->product_variant,
            'description' => $request->description,
            'expired_date' => $request->expired_date,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

    // Upload dan simpan foto jika ada
        if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $folderPath = 'product/' . $product_id->id;
                    $imagePath = $image->storeAs($folderPath, uniqid() . '.' . $image->getClientOriginalExtension(), 'public');

                    ProductImages::create([
                        'product_code' => $product_code,
                        'images' => $imagePath,
                        'created_at' => now(),
                        'created_by' => $updated_by
                            
                    ]);
                }

        }

        if($point == null)
        {
            ProductPointModel::create([
                'product' => $product_code,
                'point' => $request->point,
                'status' => 7,
                'start_date' => $request->start_date,
                'end_date' =>$request->end_date
            ]);
        }else{
            ProductPointModel::where('product', $product_code)->update([
                'point' => $request->point,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' =>$request->end_date
            ]);
        }
        

        if($request->product_variant == 'Y'){
            session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('add_product_variant', $product_code);
        }

        session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('products_data');
    }

    public function add_product_variant_layout(Request $request)  {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        $variant_category_bakery = VariantCategoryModel::whereNotIn('id' , ['4', '5'])->get();
        $variant_category_drinks = VariantCategoryModel::whereIn('id' , ['4', '5'])->get();

        $products =DB::table('v_products')->where('product_code', $request->product_code)->first();
        return view('layouts.main_pages.products.create.products_variant_create', compact('products', 'variant_category_bakery', 'variant_category_drinks'));
    }


    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy($product_code)
{
    // Ambil data produk
    $product = DB::table('products')->where('product_code', $product_code)->first();

    if (!$product) {
        abort(403, 'Data produk tidak ditemukan');
    }

    // Ambil gambar
    $product_image = DB::table('product_images')
        ->where('product_code', $product_code)
        ->first();

    // Hapus file gambar (jika ada)
    if ($product_image && $product_image->images) {
        $dropPicture = public_path('storage/' . $product_image->images);

        if (file_exists($dropPicture)) {
            unlink($dropPicture);
        }

        // hapus record image dari DB
        DB::table('product_images')->where('product_code', $product_code)->delete();
    }

    // Hapus produk dari DB
    DB::table('products')->where('product_code', $product_code)->delete();

    session()->flash('message_success', 'Data produk berhasil dihapus!');
    return redirect()->back();
}

    public function delete_images(Request $request, $id)
    {
            $product_image = ProductImages::find($id);

            // Hapus file gambar (jika ada)
            if ($product_image->images) {
                $dropPicture = public_path('storage/' . $product_image->images);

                if (file_exists($dropPicture)) {
                    unlink($dropPicture);
                }

                DB::table('product_images')->where('id', $request->id)->delete();
            }


             session()->flash('delete_images', 'Sound Engine Berhasil dihapus!');
            return redirect()->back();
        
    }

}
