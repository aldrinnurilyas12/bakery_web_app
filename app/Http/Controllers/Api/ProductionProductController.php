<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionProduct;
use App\Models\ProductWaste;
use App\Models\ProductWasteDetail;
use App\Models\RawMaterialUsages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductionProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (int) date('H');
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        // if($time >=8  && $time <=5){
        //     session()->flash('failed_message', 'Jadwal input data Produksi Produk sudah lewat');
        //     return redirect()->back();
        // }


        $products = DB::table('v_products as vp')
        ->whereNotIn('category',['Coffee', 'Soft_Drinks'])->get();
        $variant_category = DB::table('variant_category')->get();
        $raw_materials = DB::table('raw_material')->get();
        return view('layouts.main_pages.production_products.create.production_create', compact('products', 'raw_materials', 'variant_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'raw_material' => 'required|array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'target_total' => 'required',
            'production_type' => 'required',
            'quantity_used' => 'array',
            'production_date' => 'required'
        ],
        [
            'product.required' => 'Pilih produk dahulu',
            'raw_material.required' => 'Bahan baku harus diisi',
            'target_total.required' => 'Target total harus diisi',
            'production_type.required' => 'Tipe produksi harus diisi',
            'production_date.required' => 'Tanggal produksi produk harus diisi'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $production_code = 'PRODUCTION' . $unique_code;

        $rawMaterials = $request->raw_material;
        $quantities   = $request->quantity_used;



        $production = ProductionProduct::create([
            'production_code' =>$production_code,
            'product' =>$request->product,
            'variant' =>$request->variant,
            'target_total' =>$request->target_total,
            'status' => 10,
            'store' => $store,
            'production_type' =>$request->production_type,
            'production_date' =>$request->production_date,
            'created_by' =>$created_by,
            'created_at' => now()
        ]);
        
        foreach($rawMaterials as $rawCode) {
            RawMaterialUsages::create([
                'production_code' => $production->production_code,
                'quantity_used' =>(int) ($quantities[$rawCode] ?? 0),
                'raw_material' => $rawCode,
                'created_by' => $created_by,
                'created_at' => now()
            ]);
        }

        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->route('production_products');
    }

    public function get_variant(Request $request)
    {
        $product = $request->product;
        $getData = DB::table('product_variant as pv')
                ->join('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
                ->select('pv.id', 'vc.name')->distinct()
                ->where('pv.product', $product)
                ->get();

        return response()->json([
            'data' => $getData,
            'message' => 'Data variant type'
        ]);
    }

    public function get_variant_code(Request $request)
    {
        $product = $request->product;
        $variant_type = $request->variant;
        $getData = DB::table('product_variant as pv')->select('pv.id', 'vc.name', 'pv.variant_code')
                ->leftJoin('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
                ->where('pv.product', $product)->where('vc.name', $variant_type)
                ->first();

        return response()->json([
            'data' => $getData,
            'message' => 'Data variant code'
        ]);
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
    public function edit(string $id, Request $request)
    {  
        date_default_timezone_set('Asia/Jakarta');
        $time = (int) date('H');
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        } 

        //  if($time >=8){
        //     session()->flash('failed_message', 'Jadwal input data Produksi Produk sudah lewat');
        //     return redirect()->back();
        // }
        $production = DB::table('v_production_products')
        ->where('production_code', $request->production_code)->first();
        $products = DB::table('v_products')->get();
        $raw_materials = DB::table('raw_material')->get();
        $production_date = Carbon::parse($production->production_date);
        $status = DB::table('status_category')->whereIn('id', ['2','3','4', '5', '9', '10'])->get();
        return view('layouts.main_pages.production_products.edit.production_edit', compact('production','products','raw_materials','production_date','status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product' => 'required',
            'raw_material' => 'required|array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'target_total' => 'required',
            'production_type' => 'required',
            'quantity_used' => 'required|array',
            'quantity_used.*' => 'required|numeric|min:1',
            'production_date' => 'required'
        ],
        [
            'product.required' => 'Pilih produk dahulu',
            'raw_material.required' => 'Bahan baku harus diisi',
            'target_total.required' => 'Target total harus diisi',
            'production_type.required' => 'Tipe produksi harus diisi',
            'production_date.required' => 'Tanggal produksi produk harus diisi',
            'quantity_used.*.required' => 'Jumlah bahan baku tidak boleh kosong',
            'quantity_used.*.numeric' => 'Jumlah bahan baku harus berupa angka',
            'quantity_used.*.min' => 'Jumlah bahan baku minimal 1',
        ]);
        

        DB::transaction(function() use ($request){
            $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
            $rawMaterials = $request->raw_material;
            $quantities   = $request->quantity_used;

            ProductionProduct::where('production_code', $request->production_code)->update([
                'product' =>$request->product,
                'target_total' =>$request->target_total,
                'production_type' =>$request->production_type,
                'production_date' =>$request->production_date,
                'updated_by' =>$updated_by,
                'updated_at' => now()
            ]);

            foreach($request->raw_material  as $rawCode) {
                DB::table('raw_material_usages')->updateOrInsert(
                  [ 
                    'production_code' => $request->production_code,
                    'raw_material' => $rawCode,
                  ],
                  [
                        'quantity_used' =>$request->quantity_used[$rawCode] ?? 0,
                        'updated_by' => $updated_by,
                        'updated_at' => now()
                  ]
                );
            }
        });
        
        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->route('production_products');
    }

    public function update_target_production(Request $request) {

        $request->validate([
            'reject_quantity' => 'required',
            'actual_quantity' => 'required'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        ProductionProduct::where('production_code', $request->production_code)->update([
            'reject_quantity' =>$request->reject_quantity,
            'actual_quantity' =>$request->actual_quantity,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data Produksi Produk berhasil diperbarui!');
        return redirect()->back();
    }

    public function update_production_reason(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        ProductionProduct::where('production_code', $request->production_code)->update([
            'description' =>$request->description,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Alasan produksi dibatalkan sudah disimpan');
        return redirect()->back();
    }

    public function update_production_status(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;        
        ProductionProduct::where('production_code', $request->production_code)->update([
            'status' => $request->status,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);
        
        session()->flash('message_success', 'Data Produksi Produk berhasil diperbarui!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $production = ProductionProduct::where('production_code', $request->production_code)->first();
        if($production){
            $production->delete();
        }
        session()->flash('message_success', 'Data Produksi Produk berhasil dihapus!');
        return redirect()->route('production_products');
    }

    public function filter_production(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);

        if($filter_forbidden_access){
            return redirect()->back();
        }


        $store = DB::table('store')->get();
        $status = DB::table('status_category')->whereIn('id', ['2','3', '4', '5', '9', '10'])->get();
        $filter = $request->fstore;

        $productionProduct = DB::table('v_production_products');
        if ($filter !== 'all' && !empty($filter)) {
            $productionProduct->where('store_code', $filter);
        }
        $production_products = $productionProduct->get();
        return view('pages.production_product',compact('production_products', 'store', 'status'));
    }



    // PRODUCT WASTE MODULE

    public function product_waste(Request $request) 
    {

     $store_outlet = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
    $product_wastes = DB::table('product_wastes as pw')
    // relasi ke production
    ->leftJoin('production_products as pp', 'pw.production_code', '=', 'pp.production_code')
    ->leftJoin('products as prod', 'pp.product', '=', 'prod.product_code')
    ->leftJoin('store as st', 'pp.store', '=', 'st.id')

    // relasi ke product daily
    ->leftJoin('products_daily as pd', 'pw.product_daily', '=', 'pd.daily_code')
    ->leftJoin('products as prod_daily', 'pd.product_code', '=', 'prod_daily.product_code')
    ->leftJoin('store as sto', 'pd.store', '=', 'sto.id')

    // relasi tambahan
    ->leftJoin('status_category as sc', 'pw.status', '=', 'sc.id')
    ->select([
        'pw.*',

        // production
        'pp.production_code',
        'prod.product_code as production_product_code',
        'prod.product_name as product_name',
        'st.store_name as production_store', 

        // daily
        'pd.daily_code',
        'prod_daily.product_code as daily_product_code',
        'prod_daily.product_name as daily_product_name',
        'sto.store_name as daily_store', 

        // status & store
        'sc.status_name as status_name',
        
    ])->where('pd.store', $store_outlet)->orWhere('pp.store', $store_outlet)
    ->get();

        $store = DB::table('store')->get();
        $product_wastes_detail = DB::table('v_product_wastes')->get();
        return view('layouts.main_pages.product_wastes.product_waste', compact('product_wastes', 'product_wastes_detail', 'store'));
    }

    public function waste_create(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $product_daily = DB::table('products_daily as pd')->leftJoin('products as p', 'pd.product_code', '=', 'p.product_code')->where('store', $store)->where('status', 4)->get();
        $production = DB::table('production_products as pp')->leftJoin('products as p', 'pp.product', '=', 'p.product_code')
        ->where('store', $store)
        ->get();
         return view('layouts.main_pages.product_wastes.create.product_waste_create', compact('production', 'product_daily'));
    }

    public function product_waste_save(Request $request)
    {
        $request->validate([
            'waste_type'  => 'required|array',
            'waste_type*'=> 'nullable|integer|min:0'
        ]);

        $production_code = $request->production_code;
        $product_daily = $request->product_daily;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;

        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;


        if($production_code && $product_daily){
            session()->flash('failed_message', 'Hanya diperbolehkan satu kategori');
            return redirect()->back();
        }

       $codeWastes = ProductWaste::create([
                'production_code' => $production_code,
                'product_daily' => $product_daily,
                'waste_code' => $wasteCode,
                'waste_date' => now(),
                'status' => 13,
                'approved_by' => null,
                'created_by' => $user,
        ]);

        foreach($request->waste_type as $waste => $qty){

            if (!$qty || $qty <= 0) {
                 continue;
            }
            $waste_type = DB::table('waste_category')->select('waste_code')->where('waste_code', $waste)->first();

            ProductWasteDetail::create([
                'waste_code' => $codeWastes->waste_code,
                'waste_type' => $waste_type->waste_code,
                'quantity' => $qty,
            ]);

        }

        session()->flash('message_success', 'Data Produk Waste berhasil disimpan!');
        return redirect()->route('product-wastes');

    }

    public function filter_wastes(Request $request){
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);

        if($filter_forbidden_access){
            return redirect()->back();
        }


        $filter = $request->filter;

        $product_wastes = DB::table('product_wastes as pw')
            // relasi ke production
            ->leftJoin('production_products as pp', 'pw.production_code', '=', 'pp.production_code')
            ->leftJoin('products as prod', 'pp.product', '=', 'prod.product_code')
            ->leftJoin('store as st', 'pp.store', '=', 'st.id')

            // relasi ke product daily
            ->leftJoin('products_daily as pd', 'pw.product_daily', '=', 'pd.daily_code')
            ->leftJoin('products as prod_daily', 'pd.product_code', '=', 'prod_daily.product_code')
            ->leftJoin('store as sto', 'pd.store', '=', 'sto.id')

            // relasi tambahan
            ->leftJoin('status_category as sc', 'pw.status', '=', 'sc.id')
            ->select([
                'pw.*',

                // production
                'pp.production_code',
                'prod.product_code as production_product_code',
                'prod.product_name as product_name',
                'st.store_name as production_store', 
                'pp.store as production_store_id',

                // daily
                'pd.daily_code',
                'prod_daily.product_code as daily_product_code',
                'prod_daily.product_name as daily_product_name',
                'sto.store_name as daily_store', 
                'pd.store as daily_store_id',

                // status & store
                'sc.status_name as status_name',
                
        ]);


        $store = DB::table('store')->get();
        $product_wastes_detail = DB::table('v_product_wastes')->get();

        
        if ($filter !== 'all' && !empty($filter)) {
            $product_wastes->where('pp.store', $filter)->orWhere('pd.store', $filter);
        }


        $product_wastes = $product_wastes->get();

         return view('layouts.main_pages.product_wastes.product_waste', compact('product_wastes', 'product_wastes_detail', 'store'));
    }

    public function product_waste_update(Request $request){
        $product_wastes = DB::table('product_wastes_detail as pwd')
        ->leftJoin('product_wastes as pw', 'pwd.waste_code', '=', 'pw.waste_code')
        ->leftJoin('production_products as pp', 'pw.production_code', '=', 'pp.production_code')
        ->leftJoin('products as p', 'pp.product', '=', 'p.product_code')
        ->where('pwd.waste_code', $request->waste_code)->get()->keyBy('waste_code');

        $main_product_wastes = DB::table('product_wastes_detail as pwd')
        ->leftJoin('product_wastes as pw', 'pwd.waste_code', '=', 'pw.waste_code')
        ->leftJoin('production_products as pp', 'pw.production_code', '=', 'pp.production_code')
        ->leftJoin('products as p', 'pp.product', '=', 'p.product_code')
        ->where('pwd.waste_code', $request->waste_code)->first();
        return view('layouts.main_pages.product_wastes.edit.product_waste_update', compact('product_wastes', 'main_product_wastes'));
    }

    public function waste_delete(Request $request) 
    {
        $delete = ProductWaste::where('waste_code', $request->waste_code)->first();

        if($delete){
            $delete->delete();
        }
        session()->flash('message_success', 'Data Produk Waste berhasil dihapus!');
        return redirect()->route('product-wastes');
    }
}
