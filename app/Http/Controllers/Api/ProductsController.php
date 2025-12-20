<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(): View
    // {
    //     // $products = DB::table('v_products')->get();
    //     // return view('livewire.products', compact('products'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
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
            'product_name' => 'required',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:5000'
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
        
            session()->flash('message_success', 'Data produk berhasil disimpan!');
            return redirect()->route('products_data');
      
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
        $authenticatedUser = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $products = DB::table('v_products')->where('product_code', $request->product_code)->first();

        $product_images = DB::table('product_images')->where('product_code', $request->product_code)->select('id','images' )->get();
        $products_category = DB::table('product_category')->get();
        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();
        $expired_date = Carbon::parse($products->expired_date);
        return view('layouts.main_pages.products.edit.products_edit', compact('products', 'products_category', 'status', 'expired_date', 'product_images'));
        
    }

    /**
     * Update the specified resource in storage.
     */
   

    public function update(Request $request, $product_code)
{
    $request->validate([
        'images.*' => 'image|mimes:jpg,png,jpeg|max:5000',
        'product_name' => 'required',
        'category_id' => 'required',
        'price' => 'required',
        // tambahkan validasi lain sesuai kebutuhan
    ]);

    $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
    $product_id = DB::table('v_products')->select('id')->where('product_code', $request->product_code)->first();
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

    session()->flash('message_success', 'Data produk berhasil disimpan!');
    return redirect()->route('products_data');
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
