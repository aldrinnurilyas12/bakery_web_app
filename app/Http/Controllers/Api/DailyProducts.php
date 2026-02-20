<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyProducts as DailyProductsModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DailyProducts extends Controller
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

        // if($time <=6  || $time>=8){
        //      session()->flash('failed_message', 'Waktu sudah lewat');
        //     return redirect()->back();
        // }

        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();


        $products = DB::table('v_production_products_main')->where('store', $store)->get();
        return view('layouts.main_pages.daily_products.create.products_create', compact('products', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'production' => 'required',
            'stock_available' => 'required'
        ],
        [
            'production.required' => 'Pilih produk dahulu',
            'stock_available.required' => 'Stok Produk harus diisi'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $daily_code = 'DAILY' . $unique_code;

        $production = $request->production;
        DailyProductsModel::create([
            'daily_code' => $daily_code,
            'production' => $production,
            'stock_available' => $request->stock_available,
            'status' => 4,
            'store' => $store,
            'created_at' => now(),
            'created_by' => $created_by
        ]);


        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
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
    public function edit(string $id, Request $request) 
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;

        $product = DB::table('v_daily_products')
            ->where('daily_code', $request->daily_code)
            ->first();

        if(!$product || $product->store_id != $user){
            session()->flash('failed_message', 'Maaf anda tidak bisa akses ini');
            return redirect()->back();
        }

        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();


       
        
        return view('layouts.main_pages.daily_products.edit.daily_products_edit', compact('product', 'status'));
    }

    public function edit_variant(string $id, Request $request) :View
    {
        $product = DB::table('v_daily_products')->where('variant_code', $request->variant_code)->first();
        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();
        
        return view('layouts.main_pages.daily_products.edit.daily_products_edit_variant', compact('product', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        
        DailyProductsModel::where('daily_code', $request->daily_code)->update([
            'status' => $request->status,
            'stock_available' => $request->stock_available,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
        return redirect()->route('dailyproducts_data');
    }


    public function nonactive_daily_product(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        DailyProductsModel::where('daily_code', $request->daily_code)->update([
            'status' => $request->status,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
        return redirect()->route('dailyproducts_data');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {

        $daily_product = DailyProductsModel::where('daily_code', $request->daily_code)->first();

        if($daily_product){
            $daily_product->delete();
        }
        session()->flash('message_success', 'Data Daily Produk berhasil dihapus!');
        return redirect()->route('dailyproducts_data');


    }

   public function filter(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);

        if($filter_forbidden_access){
            return redirect()->back();
        }

        $store = DB::table('store')->get();
        $filter = $request->filter;
        $daily_products = DB::table('v_daily_products');

        if ($filter !== 'all' && !empty($filter)) {
            $daily_products->where('store_code', $filter);
        }

        $daily_products = $daily_products->get();

        // dd($daily_products);
        return view('pages.dailyproduct-data', compact('daily_products', 'store'));
    }

    public function get_stock(Request $rq){
        $data = DB::table('production_products')
        ->select('actual_quantity', 'product', 'variant')
        ->where('status', 5)
        ->where('production_code', $rq->production_code)->first();

        return response()->json([
            'data' => $data,
            'message' => 'Stock product'
        ]);
    }
}
