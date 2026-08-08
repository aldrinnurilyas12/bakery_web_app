<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Livewire\Products;
use App\Models\ProductsModel;
use App\Models\ProductImages;
use App\Models\ProductIngredients;
use App\Models\ProductIngredientsDetail;
use App\Models\ProductPointModel;
use App\Models\ProductsVariant;
use App\Models\MaterialUnitModel;
use App\Models\ProductPriceHistory;
use App\Models\VariantCategoryModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\UserLogActivity;

class ProductsController extends Controller
{
   

    public function create()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
       
        $product_types = DB::table('product_types')->get();
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $product_category = DB::table('product_category')->get();
        $raw_materials = DB::table('raw_material')->get();
        $unit_category = DB::table('material_unit_category')->whereIn('id', ['1', '2', '3', '7', '16'])->get();
        return view('layouts.main_pages.products.create.products_create', compact('product_category','unit_category', 'raw_materials', 'product_types'));
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
            'product_type' => 'required',
            'images' => 'required|image|mimes:jpg,png,jpeg|max:5000',
            'raw_material' => 'array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'weight' => 'array'
        ],
        [
            'product_name.required' => 'Nama Produk harus diisi',
            'category_id.required' => 'Kategori Produk harus diisi',
            'product_weight.required' => 'Berat Produk harus diisi',
            'product_weight_type.required' => 'Massa Produk harus diisi',
            'product_type.required' => 'Tipe produk harus diisi',
            'images.required' => 'Gambar/Foto Produk harus ada'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        
        $category = $request->category_id;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $product_code = 'PRD' . $category . $unique_code;
        $ingredients_code = 'INGR' . $unique_code;

        $data = ProductsModel::create([
                'product_code'=> $product_code,
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'product_weight' => $request->product_weight,
                'product_type' => $request->product_type,
                'product_weight_type' => $request->product_weight_type,
                'product_variant'  => $request->product_variant,
                'product_status' => 7,
                'description' => $request->description,
                'created_at' => now(),
                'created_by' => $created_by

        ]);

        if ($request->hasFile('images')) {
                $product_image = $request->file('images');
                $folderPath = 'product/' . $data->latest()->first()->id;
                $imagePath = $product_image->storeAs($folderPath, uniqid() . '.' . $product_image->getClientOriginalExtension(), 'public');

                ProductImages::create([
                    'product_code' => $product_code,
                    'images' => $imagePath,
                    'created_at' => now(),
                    'created_by' => $created_by
                            
                ]);
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

        UserLogActivity::log(
            module: 'Products',
            method_type: 'CREATE',
            description: "user create new products: {$data->product_code}"     
        );
        
        session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('products_data');
      
    }

    public function add_ingredients_layouts(Request $rq)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);

        $checking_status = DB::table('product_ingredients')->where('product', $rq->product_code)->first();

        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        if($checking_status){
            if($checking_status->status == 7){
                session()->flash('failed_message', 'Masih ada harga HPP yang aktif');
                return redirect()->back();
            }
        }
        $products = DB::table('products as p')->where('product_code', $rq->product_code)
        ->leftJoin('product_types as pt', 'p.product_type', '=', 'pt.id')->first();
        $raw_materials = DB::table('raw_material_ingredients_asset')->get();
        $material_unit = MaterialUnitModel::all();
        return view('layouts.main_pages.products.create.add_ingredients', compact('products', 'raw_materials', 'material_unit'));
    }


    public function save_ingredients(Request $rq)
    {
         $rq->validate([
            'raw_material' => 'array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'unit' => 'array',
            'hpp' => 'required'
        ],
        [
            'hpp.required' => 'HPP harus diisi'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $ingredients_code = 'INGR' . $unique_code;

        $raw_materials = $rq->raw_material;
        $qty = $rq->quantity;
        $unit = $rq->unit;
        $subtotal = $rq->subtotal;

        $checking_status = DB::table('product_ingredients')->where('product', $rq->product)->first();

        if($checking_status){
            if($checking_status->status == 7){
                session()->flash('failed_message', 'Masih ada harga HPP yang aktif');
                return redirect()->route('bill-of-material', $rq->product);
            }
        }

        $ingredient = ProductIngredients::create([
            'product' => $rq->product,
            'ingredients_code' => $ingredients_code,
            'hpp' => $rq->hpp,
            'status' => 7
        ]);

        foreach($raw_materials as $raw){
            ProductIngredientsDetail::create([
                'ingredients' => $ingredients_code,
                'raw_material' => $raw,
                'quantity' => (int) $qty[$raw] ?? 0,
                'unit' => $unit[$raw] ?? null,
                'subtotal' => $subtotal[$raw] ?? null
            ]);
        }

        UserLogActivity::log(
            module: 'Products',
            method_type: 'CREATE',
            description: "user create new ingredients products: {$rq->product}"      
        );

       session()->flash('message_success', 'Data ingredients produk berhasil disimpan!');
       return redirect()->route('bill-of-material', $rq->product);
    }

    public function bill_of_material(Request $rq)
    {
        $bill_of_material = DB::table('product_ingredients as pi')
        ->leftJoin('products as p', 'pi.product', '=', 'p.product_code')
        ->where('p.product_code', $rq->product)
        ->orderBy('pi.created_at', 'DESC')->get();
        $status = DB::table('status_category')->whereIn('id',['7', '8'])->get();
        return view('layouts.main_pages.products.bill_of_material', compact('bill_of_material', 'status'));
    }

    public function bill_of_material_detail(Request $rq)
    {
        $bill_of_material = DB::table('product_ingredients as pi')
        ->select('p.product_code','pi.ingredients_code', 'rm.material_name','pi.hpp', 'pid.quantity','pid.subtotal','muc.unit_name' ,'pid.created_at')
        ->leftJoin('product_ingredients_detail as pid', 'pi.ingredients_code', '=', 'pid.ingredients')
        ->leftJoin('products as p', 'pi.product', '=', 'p.product_code')
        ->leftJoin('raw_material as rm', 'pid.raw_material', '=', 'rm.material_code')
        ->leftJoin('material_unit_category as muc', 'pid.unit', '=', 'muc.id')
        ->leftJoin('status_category as sc', 'pi.status', '=', 'sc.id')
        ->where('pid.ingredients', $rq->ingredients_code)->get();

        return view('layouts.main_pages.products.bill_of_material_detail', compact('bill_of_material'));
    }

    public function update_status_bom(Request $rq)
    {
        DB::table('product_ingredients')->where('ingredients_code', $rq->ingredients_code)->update([
            'status' => $rq->status,
            'updated_at' => now()
        ]);

         session()->flash('message_success', 'Status berhasil diperbarui!');
         return redirect()->back();
    }

    public function save_product_variant(Request $request) {

        $request->validate([
            'variant_type' => 'required',
            'variant_price' => 'required',
            'price_effective_from' => 'required'
        ], 
        [
            'variant_type.required' => 'Tipe Variant harus diisi',
            'variant_price.required' => 'Harga Produk Variant harus diisi',
            'price_effective_from.required' => 'Tanggal harga efektif harus diisi'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);
        $variant_code = 'VARIANT' . $unique_code;
        $hpp = $request->hpp;
        $price = $request->variant_price;

         if($price < $hpp){
            session()->flash('failed_message', 'Harga produk tidak boleh lebih rendah dari HPP!');
            return redirect()->back();
        }

            ProductsVariant::create([
                'variant_code' => $variant_code,
                'product' => $request->product,
                'variant_price' => $request->variant_price,
                'variant_discount' => $request->variant_discount ?? 0,
                'variant_price_after_discount' => $request->variant_price_after_discount ?? 0,
                'price_effective_from' => $request->price_effective_from,
                'variant_type' => $request->variant_type,
                'created_at' => now()
            ]);

            ProductPriceHistory::create([
                'product' => $request->product,
                'variant' => $variant_code,
                'hpp' => $request->hpp,
                'price_before' =>$request->variant_price,
                'discount_before' => $request->variant_discount ?? 0,
                'price_after_discount_before' => $request->variant_price_after_discount,
                'business_effective_date_old' => $request->price_effective_from,
                'status' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ]);


            session()->flash('message_success', 'Data produk variant berhasil disimpan!');
            return redirect()->back();
    }

    public function update_product_variant(Request $request) {
        ProductsModel::where('product_code', $request->product_code)->update([
            'product_variant' => 'N',
            'updated_at' => now()
        ]);
        

        UserLogActivity::log(
            module: 'Products',
            method_type: 'UPDATE',
            description: "user update product variant: {$request->product_code}"      
        );
        session()->flash('message_success', 'Data produk variant berhasil diperbarui!');
        return redirect()->route('products_data');
    }

    public function update_variant_layout(Request $request) {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
    
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        $check_variant = DB::table('product_variant')->where('variant_code', $request->variant_code)->first();

        if(!$check_variant){
            session()->flash('failed_message', 'Data produk variant tidak ditemukan!');
            return redirect()->back();
        }


        $variant = DB::table('product_variant as pv')
                ->select('pv.variant_code',
                         'p.product',
                         'p.product_code',
                         'p.category',
                         'p.hpp',
                         'pv.variant_type',
                         'pv.variant_price',
                         'variant_discount',
                         'variant_price_after_discount',
                         'pv.price_effective_from')
                ->leftJoin('v_products as p', 'pv.product', '=', 'p.product_code')
                ->where('variant_code', $request->variant_code)->first();
        $variant_category_food = DB::table('variant_category')->whereIn('id', ['1', '2', '3'])->get();
        $variant_category_drink = DB::table('variant_category')->whereIn('id', ['4', '5'])->get();
        $business_effective_date = Carbon::parse($variant->price_effective_from);

        return view('layouts.main_pages.products.edit.edit_variant_product', compact('variant', 'variant_category_food', 'variant_category_drink', 'business_effective_date'));
    }

    public function edit_variant(Request $request) {

        $request->validate([
            'variant_price_after' => 'required'
        ],
        [
            'variant_price_after' => 'Harga variant produk harus diisi'
        ]);


        $check_variant = DB::table('product_variant')->where('variant_code', $request->variant_code)->first();
        $price = $request->variant_price_after;
        $hpp = $request->hpp;

        if(!$check_variant){
            session()->flash('failed_message', 'Data produk variant tidak ditemukan!');
            return redirect()->back();
        }

        if($price < $hpp){
             session()->flash('failed_message', 'Harga produk tidak boleh rendah dari HPP!');
            return redirect()->back();
        }

        DB::table('product_variant')->where('variant_code', $request->variant_code)->update([
            'variant_price' => $request->variant_price_after,
            'variant_discount' => $request->variant_discount_after,
            'variant_price_after_discount' => $request->variant_price_after_discount_after,
            'price_effective_from' => $request->price_effective_from_after
        ]);

        if($request->variant_price_after && $request->price_effective_from_after) {
            ProductPriceHistory::create([
                'product' => $request->product_code,
                'variant' => $request->variant_code,
                'price_after' => $request->variant_price_after,
                'price_before' =>$request->variant_price_before,
                'discount_after' => $request->variant_discount_after, 
                'discount_before' => $request->variant_discount_before,
                'price_after_discount_after' => $request->variant_price_after_discount_after,
                'price_after_discount_before' => $request->variant_price_after_discount_before,
                'business_effective_date_old' => $request->price_effective_from_before,
                'business_effective_date_new' => $request->price_effective_from_after,
                'status' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

         UserLogActivity::log(
            module: 'Products',
            method_type: 'UPDATE',
            description: "user update product variant: {$request->product_code}"      
        );

        session()->flash('message_success', 'Data produk variant berhasil disimpan!');
        return redirect()->route('products_data');
    }

    public function delete_variant(Request $request) {
       $variant_code = ProductsVariant::where('variant_code', $request->variant_code)->first();

        if($variant_code){
            $variant_code->delete();
        }

         UserLogActivity::log(
            module: 'Products',
            method_type: 'DELETE',
            description: "user delete product variant: {$variant_code}"      
        );

        session()->flash('message_success', 'Data produk variant berhasil dihapus!');
        return redirect()->back();
    }

    public function product_price_history($product_code, $variant = null)
    {
        $query = DB::table('product_price_history as pph')
                    ->select(
                        'p.product_name',
                        'p.product_code',
                        'pph.price_code',
                        'pph.price',
                        'pph.discount',
                        'pph.price_after_discount',
                        'pph.business_effective_date',
                        'sc.status_name',
                        'vc.name',
                        'pph.created_at',
                        'pph.updated_at'
                    )
                    ->leftJoin('products as p', 'pph.product', '=', 'p.product_code')
                    ->leftJoin('product_variant as pv', 'pph.variant', '=', 'pv.variant_code')
                    ->leftJoin('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
                    ->leftJoin('status_category as sc', 'pph.status', '=', 'sc.id')
                    ->where('pph.product', $product_code);


        if($variant){
            $query->where('pph.variant', $variant);
        }

        $current_price = (clone $query)->select('p.price', 'p.discount', 'p.price_after_discount', 'p.price_effective_from')->first();

        $product_price = (clone $query)->where('pph.status', 7)->orderBy('pph.created_at', 'DESC')->get();
        $old_product_price = (clone $query)->where('pph.status', 8)->orderBy('pph.created_at', 'DESC')->get();
        
       
        
        return view('layouts.main_pages.products.product_price_history', compact('product_price', 'old_product_price', 'current_price'));
    }

    /**
     * Display the specified resource.
     */
    public function product_review(Request $request)
    {
        $product = $request->product_code;

        $review = DB::table('product_reviews as pr')
                ->leftJoin('products as p', 'pr.product', '=', 'p.product_code')
                ->leftJoin('transactions as t', 'pr.transaction', '=', 't.transaction_code')
                ->leftJoin('customer as c', 't.customer', '=', 'c.customer_code')
                ->where('pr.product', $product)->get();

        $total_rating = DB::table('v_products')
            ->select('total_rating', 'rating')
            ->where('product_code', $product)->first();

        return view('layouts.main_pages.products.product-review', compact('review','total_rating'));
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
        $point_start_date = Carbon::parse($products->point_start_date);
        $point_end_date = Carbon::parse($products->point_end_date);
        $business_effective_date = Carbon::parse($products->price_effective_from);
        $product_type = DB::table('product_types')->get();

         $unit_category = DB::table('material_unit_category')->whereIn('id', ['1', '2', '3', '7', '16'])->get();
        return view('layouts.main_pages.products.edit.products_edit', compact('products','point','unit_category', 'products_category','product_type', 'status','business_effective_date' ,'product_images', 'point_start_date', 'point_end_date'));
        
    }

    /**
     * Update the specified resource in storage.
     */
   

    public function update(Request $request)
    {
     $request->validate([
            'product_name' =>'required',
            'category_id' =>'required',
            'product_weight' =>'required',
            'product_weight_type' =>'required',
            'images' => 'image|mimes:jpg,png,jpeg|max:5000'
        ],
        [
            'product_name.required' => 'Nama Produk harus diisi',
            'category_id.required' => 'Kategori Produk harus diisi',
            'product_weight.required' => 'Berat Produk harus diisi',
            'product_weight_type.required' => 'Massa Produk harus diisi'
        ]);

    $product_code = $request->product_code;
    $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
    $product_id = DB::table('v_products')->select('id')->where('product_code', $request->product_code)->first();
    $point = ProductPointModel::where('product', $product_code)->select('point')->first();

    // Update data produk utama
    $data =  DB::table('products')
        ->where('product_code', $product_code)
        ->update([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'price' => $request->price_after,
            'discount' => $request->discount_after,
            'price_after_discount' => $request->price_after_discount_after,
            'price_effective_from' => $request->price_effective_from_after,
            'product_weight' => $request->product_weight,
            'product_type' => $request->product_type,
            'product_weight_type' => $request->product_weight_type,
            'product_variant'  => $request->product_variant,
            'description' => $request->description,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

    // Upload dan simpan foto jika ada
        if ($request->hasFile('images')) {
                $product_image = $request->file('images');
                $folderPath = 'product/' . $product_id->id;
                $imagePath = $product_image->storeAs($folderPath, uniqid() . '.' . $product_image->getClientOriginalExtension(), 'public');

                ProductImages::create([
                    'product_code' => $product_code,
                    'images' => $imagePath,
                    'created_at' => now(),
                    'created_by' => $updated_by
                            
                ]);
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

        UserLogActivity::log(
            module: 'Products',
            method_type: 'UPDATE',
            description: "user update product: {$product_code}"      
        );
        

        if($request->product_variant == 'Y'){
            session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('add_product_variant', $product_code);
        }

        session()->flash('message_success', 'Data produk berhasil disimpan!');
        return redirect()->route('products_data');
    }

    public function update_status_product(Request $rq){
        DB::table('products')->where('product_code', $rq->product_code)->update([
            'product_status' => $rq->product_status
        ]);

        session()->flash('message_success', 'Data produk berhasil diperbarui!');
        return redirect()->back();
    }

    public function add_product_price_layout(Request $rq)
    {
        $product = DB::table('v_products as p')
        ->select('p.product_code', 'p.product', 'p.hpp')
        ->where('p.product_code', $rq->product_code)->first();

        $check_hpp = DB::table('v_products')->select('hpp')->where('product_code', $rq->product_code)->first();
       
        if($check_hpp->hpp == null){
            session()->flash('failed_message', 'HPP untuk produk ini belum ada!');
            return redirect()->back();
        }

        return view('layouts.main_pages.products.create.add_product_price', compact('product'));
    }


    public function add_product_price(Request $request)
    {

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        $product_code = $request->product_code;
        $hpp = $request->hpp;
        $price = $request->price;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $price_code = 'PRC' . now()->format('dmy'). $unique_code;
        $check_hpp = DB::table('v_products')->select('hpp')->where('product_code', $product_code)->first();
       
        if($check_hpp->hpp == null){
            session()->flash('failed_message', 'HPP untuk produk ini belum ada!');
            return redirect()->back();
        }


        if($request->product_variant == 'Y'){

            DB::table('products')->where('product_code', $product_code)->update([
                'product_variant' => $request->product_variant,
                'updated_at' => now(),
                'updated_by' => $updated_by
            ]);

            session()->flash('message_success', 'Data produk berhasil disimpan!');
            return redirect()->route('add_product_variant', $product_code);
        }


        if($price < $hpp){
            session()->flash('failed_message', 'Harga produk tidak boleh lebih rendah dari HPP!');
            return redirect()->back();
        }

        DB::table('products')->where('product_code', $product_code)->update([
            'price' => $request->price,
            'discount' => $request->discount,
            'price_after_discount' => $request->price_after_discount,
            'product_variant' => $request->product_variant,
            'price_effective_from' => $request->price_effective_from,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

         ProductPriceHistory::create([
                'product' => $request->product_code,
                'price_code' => $price_code,
                'variant' => null,
                'hpp' => $request->hpp,
                'price' =>$request->price,
                'discount' => $request->discount,
                'price_after_discount' => $request->price_after_discount,
                'business_effective_date' => $request->price_effective_from,
                'status' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ]);


        session()->flash('message_success', 'Data harga produk berhasil disimpan!');
        return redirect()->route('products_data');
    }

    public function update_product_price_layout(Request $rq)
    {

         $product = DB::table('v_products as p')
        ->where('p.product_code', $rq->product_code)->first();

        $business_effective_date = Carbon::parse($product->current_price_effective);
        return view('layouts.main_pages.products.edit.product_price_update', compact('product', 'business_effective_date'));
    }

    public function update_product_price(Request $rq)
    
    {
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $price_code = 'PRC' . now()->format('dmy'). $unique_code;

        DB::table('products')->where('product_code', $rq->product_code)->update([
            'price' => $rq->price,
            'discount' => $rq->discount,
            'price_after_discount' => $rq->price_after_discount,
            'updated_at' => now()
        ]);

        ProductPriceHistory::where('status', 7)
        ->where('product',$rq->product_code )
        ->update([
            'status' => 8
        ]);

        ProductPriceHistory::create([
                'product' => $rq->product_code,
                'price_code' => $price_code,
                'variant' => null,
                'hpp' => $rq->hpp,
                'price' =>$rq->price,
                'discount' => $rq->discount,
                'price_after_discount' => $rq->price_after_discount,
                'business_effective_date' => $rq->price_effective_from,
                'status' => 7,
                'created_at' => now(),
                'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data harga produk berhasil diperbarui!');
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
    
    
    public function destroy($product_code)
{
    // Ambil data produk
    $product = DB::table('products')
            ->where('product_code', $product_code)->first();
    $check_transaction = DB::table('v_products')
            ->where('transaction_status', 'Y')
            ->where('product_code', $product_code)->first();

    if (!$product) {
        abort(403, 'Data produk tidak ditemukan');
    }

    // Ambil gambar
    $product_image = DB::table('product_images')
        ->where('product_code', $product_code)
        ->first();

    if($check_transaction){
        session()->flash('failed_message', 'Produk tidak bisa dihapus, produk sudah ada di transaksi!');
        return redirect()->back();
    }

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
    ProductsModel::where('product_code', $product_code)->delete();


    UserLogActivity::log(
            module: 'Products',
            method_type: 'DELETE',
            description: "user delete product: {$product_code}"      
        );
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

            UserLogActivity::log(
            module: 'Products',
            method_type: 'DELETE',
            description: "user delete image product"      
        );


             session()->flash('delete_images', 'Sound Engine Berhasil dihapus!');
            return redirect()->back();
        
    }

}
