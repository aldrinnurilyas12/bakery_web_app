<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CentralStockProductsModel;
use App\Models\ProductionProduct;
use App\Models\ProductionProductDetailModel;
use App\Models\ProductWaste;
use App\Models\ProductWasteDetail;
use App\Models\RawMaterialUsages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\UserLogActivity;

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
            ->select(
                'vp.product_code',
                'vp.product as product_name',
                'vp.product_type',
                'pv.variant_code',
                'vc.name as category',
                'vp.product_variant',
                'vp.price'
            )
            ->leftJoin('product_variant as pv', 'vp.product_code', '=', 'pv.product')
            ->leftJoin('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
            ->where(function($q){
                $q->whereNull('vc.name')
                ->orWhereNotIn('vc.name', ['Coffee', 'Soft_Drinks']);
            })
            ->where('vp.price', '!=', 0)
            ->get();


        $production_hour = Carbon::now('Asia/Jakarta')->hour;
        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $position = $GLOBAL_ENV->position_name ?? null;

        $NOT_ALLOWED_USER = in_array($position, [
            'IT Developer',
            'Manager',
            'Supervisor',
            'Casheer'
        ]);

        if($NOT_ALLOWED_USER){
            session()->flash('failed_message', 'Tidak bisa akses!');
            return redirect()->back();
        }

        if($production_hour < 4){
            session()->flash('failed_message', 'Sistem belum buka!');
            return redirect()->back();
        }

        if($production_hour >=8){
            session()->flash('failed_message', 'Jam operasional sistem sudah tutup!');
            return redirect()->back();
        }


        $variant_category = DB::table('variant_category')->get();
        $raw_materials = DB::table('raw_material as rm')
        ->leftJoin('material_unit_category as muc', 'rm.purchase_unit', '=', 'muc.id')->get();
        $units = DB::table('material_unit_category')->get();
        return view('layouts.main_pages.production_products.create.production_create', compact('products', 'raw_materials', 'variant_category', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required|array',
            'product.*' => 'exists:products,product_code',
            'raw_material' => 'required|array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'production_type' => 'required',
            'quantity_used' => 'array',
            'unit' => 'array',
            'production_date' => 'required'
        ],
        [
            'product.required' => 'Pilih produk dahulu',
            'raw_material.required' => 'Bahan baku harus diisi',
            'production_type.required' => 'Tipe produksi harus diisi',
            'production_date.required' => 'Tanggal produksi produk harus diisi'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $date = now()->format('Ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $production_code = 'PRODUCTION'.'-'. $date . $unique_code;

        $rawMaterials = $request->raw_material;
        $quantities   = $request->quantity_used;
        $productInput = $request->product;
        $unit          = $request->unit;
        $qty_target = $request->qty_target_total ?? [];



        $production = ProductionProduct::create([
            'production_code' =>$production_code,
            'status' => 10,
            'production_type' =>$request->production_type,
            'production_date' =>$request->production_date,
            'created_by' =>$created_by,
            'created_at' => now()
        ]);

        foreach($productInput as $key => $productMany){
            ProductionProductDetailModel::create([
                'production_code' => $production->production_code,
                'product' =>$productMany,
                'variant' =>$request->variant[$key] ?? null,
                'qty_target_total' => (int) ($qty_target[$key] ?? 0)
            ]);
        }
        
        foreach($rawMaterials as $rawCode) {
            RawMaterialUsages::create([
                'production_code' => $production->production_code,
                'raw_material' => $rawCode,
                'quantity_used' =>(int) ($quantities[$rawCode] ?? 0),
                'unit' => (int) ($unit[$rawCode]),
                'created_by' => $created_by,
                'created_at' => now()
            ]);
        }

        UserLogActivity::log(
                module: 'Production Product',
                method_type: 'CREATE',
                description: "user create new production product: {$production->production_code}"      
        );

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


    public function get_ingredients(Request $rq){
        $product = $rq->product;
        $getData = DB::table('product_ingredients as pi')
            ->leftJoin('product_ingredients_detail as pid', 'pi.ingredients_code', '=', 'pid.ingredients')
            ->leftJoin('raw_material as rw', 'pid.raw_material', '=', 'rw.material_code')
            ->where('pi.product', $product)
            ->get();

        return response()->json([
            'data' => $getData,
            'message' => 'Data ingredients product' 
        ]);
    }


    public function production_detail(Request $request){
        $production = DB::table('v_production_products_detail')->where('production_code', $request->production_code)->get();
        $waste_category = DB::table('waste_category')->get();
        return view('layouts.main_pages.production_products.production_product_detail', compact('production', 'waste_category'));
    }

    public function production_detail_update(Request $rq)
    {
        $rq->validate([
            'actual_quantity' => 'required'
        ],
        [
            'actual_quantity.required' => 'Jumlah produk jadi harus diisi'

        ]);

        $production = $rq->production;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        // BUAT VALIDASI JIKA INPUT ACTUAL QUANTITY TIDAK MELEBIHAN DARI TARGET QUANTITY TABEL PRODUCTION_PRODUCT_DETAIL

        if($rq->actual_quantity > $rq->target_total){
            session()->flash('failed_message', 'Jumlah actual quantity melebihi total target!');
            return redirect()->back();
        }

        if($rq->waste_confirmation == 'yes'){
            ProductionProductDetailModel::where('id', $rq->id)->update([
                'actual_quantity' => $rq->actual_quantity
            ]);
            return redirect()->route('product-waste-production', $rq->production_id);
        }else{
            ProductionProductDetailModel::where('id', $rq->id)->update([
                'actual_quantity' => $rq->actual_quantity,
                'reject_quantity' =>(int) 0
            ]);
        }
        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->back();
    }


  

     function production_waste_create(Request $rq)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        
        $production = DB::table('production_products as pd')
        ->select('pd.production_code','ppd.id as production_detail_id','ppd.qty_target_total', 'ppd.actual_quantity', 'ppd.reject_quantity', 'p.product_name', 'ppd.product', 'ppd.variant', 'vc.name as variant_name')
        ->leftJoin('production_products_detail as ppd','pd.production_code', '=', 'ppd.production_code' )
        ->leftJoin('products as p', 'ppd.product', '=', 'p.product_code')
        ->leftJoin('product_variant as pv', 'ppd.variant', '=', 'pv.variant_code')
        ->leftJoin('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
        ->where('ppd.id', $rq->production_id)->first();   

        
        if($production->reject_quantity)
        {
            session()->flash('failed_message', 'Data sudah diinput!');
            return redirect()->route('production_products');
        }


         return view('layouts.main_pages.product_wastes.create.product_waste_production', compact('production'));
    }




    public function production_waste_save(Request $rq) {

        $rq->validate([
            'waste_type'  => 'required|array',
            'waste_type*'=> 'nullable|integer|min:0'
        ]);

        $production = $rq->production_code;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;


           ProductionProductDetailModel::where('id', $rq->production_detail_id)->update([
                'reject_quantity' => $rq->reject_quantity,
                'updated_at' => now()
            ]);
       
            $codeWastes = ProductWaste::create([
                    'production_code' => $production,
                    'waste_code' => $wasteCode,
                    'waste_date' => now(),
                    'reason' => $rq->reason,
                    'status' => 1,
                    'approved_by' => null,
                    'created_by' => $user,
            ]);

            foreach($rq->waste_type as $waste => $qty){
                if (!$qty || $qty <= 0) {
                    continue;
                }
                $waste_type = DB::table('waste_category')->select('waste_code')->where('waste_code', $waste)->first();

               $codeWastesDetail = ProductWasteDetail::create([
                    'waste_code' => $codeWastes->waste_code,
                    'product' => $rq->product_code,
                    'variant' => $rq->variant_code ?? null,
                    'waste_type' => $waste_type->waste_code,
                    'quantity' => $qty,
                ]);
            }

            UserLogActivity::log(
                module: 'Product Waste',
                method_type: 'CREATE',
                description: "user create production product waste: {$wasteCode}"      
            );
        session()->flash('message_success', 'Berhasil menyimpan data!');
        return redirect()->route('production-detail', $production);
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
            $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
            $rawMaterials = $request->raw_material;
            $quantities   = $request->quantity_used;

            ProductionProduct::where('production_code', $request->production_code)->update([
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

        UserLogActivity::log(
                module: 'Production Product',
                method_type: 'UPDATE',
                description: "user update production product: {$request->production_code}"      
        );
        
        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->route('production_products');
    }

    // public function update_target_production(Request $request) {

    //     $request->validate([
    //         'reject_quantity' => 'required',
    //         'actual_quantity' => 'required'
    //     ]);

    //     $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

    //     ProductionProduct::where('production_code', $request->production_code)->update([
    //         'reject_quantity' =>$request->reject_quantity,
    //         'actual_quantity' =>$request->actual_quantity,
    //         'reason_failed' => $request->reason_failed,
    //         'updated_by' =>$updated_by,
    //         'updated_at' => now()
    //     ]);

    //     session()->flash('message_success', 'Data Produksi Produk berhasil diperbarui!');
    //     return redirect()->back();
    // }

    public function update_production_reason(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        ProductionProduct::where('production_code', $request->production_code)->update([
            'description' =>$request->description,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Alasan produksi dibatalkan sudah disimpan');
        return redirect()->back();
    }

    public function update_production_status(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik; 
        
        
        $checking_production_available = false;

        if ($request->status == 5) {
            $checking_production_available = DB::table('production_products_detail')
                ->where('production_code', $request->production_code)
                ->where(function($q){
                    $q->whereNull('actual_quantity')
                    ->orWhereNull('reject_quantity');
                })
                ->exists();
        }

        $production_detail = DB::table('production_products_detail')
            ->where('production_code', $request->production_code)
            ->get();

        $check_null = DB::table('production_products_detail')
            ->where('production_code', $request->production_code)
            ->whereNull('actual_quantity')
            ->exists();


        if ($request->status == null) {
            session()->flash('failed_message', 'Status Produksi harus dipilih');
            return redirect()->back();
        }

        if ($checking_production_available) {
            session()->flash('failed_message', 'Masih ada produk yang belum diperbarui status jumlah produksinya');
            return redirect()->route('production-detail', $request->production_code);
        }


        if($request->status == 5){
            ProductionProduct::where('production_code', $request->production_code)->update([
                    'status' => $request->status,
                    'updated_by' =>$updated_by,
                    'updated_at' => now()
            ]);

            foreach($production_detail as $production){
                CentralStockProductsModel::create([
                    'production' => $production->production_code,
                    'product' => $production->product,
                    'variant' => $production->variant,
                    'qty_produced' => $production->actual_quantity,
                    'qty_available' => $production->actual_quantity,
                    'created_at' => $production->created_at
                ]);
            }  
        }else{
            ProductionProduct::where('production_code', $request->production_code)->update([
                    'status' => $request->status,
                    'updated_by' =>$updated_by,
                    'updated_at' => now()
            ]);
        }

        UserLogActivity::log(
                module: 'Production Product',
                method_type: 'UPDATE',
                description: "user update production product status: {$request->production_code}"      
        );
       
    
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

        UserLogActivity::log(
                module: 'Production Product',
                method_type: 'DELETE',
                description: "user delete production product: {$request->production_code}"      
        );
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


        $stores = DB::table('store')->get();
        $status = DB::table('status_category')->whereIn('id', ['2','3', '4', '5', '9', '10'])->get();
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;
        $rq_store = $request->store;
        

        if($request->filter_date){
           $production_products = DB::table('v_production_products')
            ->orderBy('production_date', 'DESC');

            if ($request->filter_date) {

                if ($request->filter_date == 'today') {
                    $production_products->whereDate('production_date', Carbon::today())
                    ->where('store_code', $rq_store ?? $store);
                }

                if ($request->filter_date == 'week') {
                    $production_products->whereBetween('production_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])->where('store_code', $rq_store ?? $store);
                }

                if ($request->filter_date == 'month') {
                    $production_products->whereMonth('production_date', Carbon::now()->month)
                        ->whereYear('production_date', Carbon::now()->year)
                          ->where('store_code', $rq_store ?? $store);
                }

                if($request->filter_date == 'year'){
                     $production_products->whereYear('production_date', Carbon::now()->year)
                          ->where('store_code', $rq_store ?? $store);
                 }
            }

           
            return view('pages.production_product',compact('production_products', 'stores','store', 'status'));
        }
        
    }



    // PRODUCT WASTE MODULE =============================== PRODUCT WASTES SECTION ======================================//////////////////

    public function product_waste(Request $request) 
    {

     $store_outlet = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
 
         $products = DB::table('product_wastes_detail as pwd')
            ->select('p.product_name', 'pwd.product')
            ->leftJoin('products as p', 'pwd.product', '=', 'p.product_code')
            ->distinct()
            ->get();
        $waste_category = DB::table('waste_category')->get();
        $waste_detail = DB::table('product_wastes_detail')
                ->select('product', 'waste_type',  DB::raw('SUM(quantity) as quantity'))
                ->groupBy('product', 'waste_type')
                ->get();

        foreach ($products as $prd) {
            $prd->waste = [];

            foreach ($waste_detail as $wd) {
                if ($wd->product == $prd->product) {
                    $prd->waste[$wd->waste_type] = $wd->quantity;
                }
            }
        }

        $store = DB::table('store')->get();
        // $product_wastes_detail = DB::table('v_product_wastes')->get();
        return view('layouts.main_pages.product_wastes.product_waste', compact( 'store', 'waste_category', 'products'));
    }

    public function product_waste_data(Request $rq)
    {
        $product_waste_production = DB::table('product_wastes as pw')
        ->where('pw.production_code', '!=', null)->get();

        $product_waste_distribution = DB::table('product_wastes as pw')
        ->where('pw.distribution', '!=', null)->get();

        $product_waste_product_daily = DB::table('product_wastes as pw')
        ->where('pw.product_daily', '!=', null)->get();

        return view('layouts.main_pages.product_wastes.product_waste_data', compact('product_waste_production', 'product_waste_distribution', 'product_waste_product_daily'));

    }


    // FIX THIS :
    // REQUEST DATE : 29/04/2026
    public function product_waste_detail(Request $rq)
    {


        $products = DB::table('product_wastes_detail as pwd')
            ->select('p.product_name', 'pwd.product')
            ->leftJoin('products as p', 'pwd.product', '=', 'p.product_code')
            ->where('pwd.waste_code', $rq->waste_code)
            ->distinct()
            ->get();

        // ambil semua detail waste
        $waste_detail = DB::table('product_wastes_detail')
            ->select('product', 'waste_type',  DB::raw('SUM(quantity) as quantity'))
            ->where('waste_code', $rq->waste_code)
            ->groupBy('product', 'waste_type')
            ->get();

         $wasteTypes = DB::table('product_wastes_detail')
            ->where('waste_code', $rq->waste_code)
            ->pluck('waste_type')
            ->unique();

        $waste_category = DB::table('waste_category')
        ->whereIn('waste_code', $wasteTypes)
        ->get();


        // 🔥 mapping ke masing-masing product
        foreach ($products as $prd) {
            $prd->waste = [];

            foreach ($waste_detail as $wd) {
                if ($wd->product == $prd->product) {
                    $prd->waste[$wd->waste_type] = $wd->quantity;
                }
            }
        }

       

        return view('layouts.main_pages.product_wastes.product_waste_detail', compact('products', 'waste_category', 'waste_detail'));
    }

    public function waste_create(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;
        // $product_daily = DB::table('products_daily as pd')->leftJoin('products as p', 'pd.product_code', '=', 'p.product_code')->where('store', $store)->where('status', 4)->get();
        $products_daily = DB::table('products_daily as pd')
                    ->leftJoin('distribution_products_detail as dpd', 'pd.distribution_store', '=', 'dpd.distribution_store_code')
                    ->leftJoin('products as p', 'dpd.product', '=', 'p.product_code')
                    ->where('pd.store', $store)
                    ->get();

                    // dd($products_daily);
         return view('layouts.main_pages.product_wastes.create.product_waste_create', compact('products_daily'));
    }



    public function product_waste_save(Request $request)
    {
        $request->validate([
            'attachment_files' => 'image|mimes:jpg,png,jpeg|max:5000',
            'waste_type'  => 'required|array',
            'waste_type*'=> 'nullable|integer|min:0',
            'product_daily' => 'required'
        ],
        [
            'product_daily.required' => 'Product Daily harap dipilih'
        ]);

        $product_daily = $request->product_daily;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;
        $approval = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;


           if ($request->hasFile('attachment_files')) {
                $productwasteFile = $request->file('attachment_files');
                $folderPath = 'dailyproduct_reject_file/' . $request->product_daily;
                $productwastePath = $productwasteFile->storeAs($folderPath, uniqid() . '.' . $productwasteFile->getClientOriginalExtension(), 'public');

                $codeWastes = ProductWaste::create([
                   'product_daily' => $request->product_daily,
                    'attachment_files' => $productwastePath,
                    'waste_code' => $wasteCode,
                    'waste_date' => now(),
                    'reason' => $request->reason,
                    'status' => 1,
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
                    'product' => $request->product_code,
                    'variant' => $request->variant_code ?? null,
                    'waste_type' => $waste_type->waste_code,
                    'quantity' => $qty,
                ]);
             }
        }else{
                $codeWastes = ProductWaste::create([
                    'product_daily' => $product_daily,
                    'waste_code' => $wasteCode,
                    'waste_date' => now(),
                    'reason' => $request->reason,
                    'status' => 1,
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
                    'product' => $request->product_code,
                    'variant' => $request->variant_code ?? null,
                    'waste_type' => $waste_type->waste_code,
                    'quantity' => $qty,
                ]);
             }
        }

        UserLogActivity::log(
                module: 'Product Waste',
                method_type: 'CREATE',
                description: "user create new product waste: {$codeWastes->waste_code}"      
        );

        session()->flash('message_success', 'Data Produk Waste berhasil disimpan!');
        return redirect()->route('product-wastes');

    }


    function waste_distribution_create(Request $rq)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        
        $distribution = DB::table('distribution_products_detail as dpd')
        ->leftJoin('products as p','dpd.product', '=', 'p.product_code' )
        ->leftJoin('product_variant as pv', 'dpd.variant', '=', 'pv.variant_code')
        ->leftJoin('variant_category as vc', 'pv.variant_type', '=', 'vc.id')
        ->where('distribution_store_code', $rq->distribution_store_code)->first();
     
        if($distribution->reject_quantity)
        {
            session()->flash('failed_message', 'Data sudah diinput!');
            return redirect()->route('distribution_products.index');
        }elseif($distribution->received_quantity == null){
             session()->flash('failed_message', 'Harap lengkapi konfirmasi data distribusi dahulu!');
            return redirect()->route('distribution_products.index');
        }


         return view('layouts.main_pages.product_wastes.create.product_waste_distribution', compact('distribution'));
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
            UserLogActivity::log(
                module: 'Product Waste',
                method_type: 'DELETE',
                description: "user delete product waste: {$request->waste_code}"      
            );
            $delete->delete();
        }
        session()->flash('message_success', 'Data Produk Waste berhasil dihapus!');
        return redirect()->route('product-wastes');
    }
}
