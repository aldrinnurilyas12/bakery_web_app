<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyProducts as DailyProductsModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
    public function create() : View
    {

        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();

        $products = DB::table('v_show_available_products')->get();
        return view('layouts.main_pages.daily_products.create.products_create', compact('products', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stock_available' => 'required'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        DailyProductsModel::create([
            'product_code' => $request->product_code,
            'variant_code' => $request->variant_code,
            'stock_available' => $request->stock_available,
            'status' => 4,
            'point' => $request->point,
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
    public function edit(string $id, Request $request) :View
    {
        $product = DB::table('v_daily_products')->where('product_code', $request->product_code)->first();
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
        
        DailyProductsModel::where('product_code', $request->product_code)->update([
            'status' => $request->status,
            'point' => $request->point,
            'stock_available' => $request->stock_available,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
        return redirect()->route('dailyproducts_data');
    }


     public function update_variant(Request $request, string $id)
    {

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        
        DailyProductsModel::where('variant_code', $request->variant_code)->update([
            'status' => $request->status,
            'point' => $request->point,
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
        DailyProductsModel::where('product_code', $request->product_code)->update([
            'status' => $request->status,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
        return redirect()->route('dailyproducts_data');
    }

    public function nonactive_daily_variant(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        DailyProductsModel::where('variant_code', $request->variant_code)->update([
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

        $daily_product = DailyProductsModel::where('product_code', $request->product_code)->first();

        if($daily_product){
            $daily_product->delete();
        }
        session()->flash('message_success', 'Data Daily Produk berhasil dihapus!');
        return redirect()->route('dailyproducts_data');


    }


    public function delete_variant(string $id, Request $request)
    {

        $daily_product = DailyProductsModel::where('variant_code', $request->variant_code)->first();

        if($daily_product){
            $daily_product->delete();
        }
        session()->flash('message_success', 'Data Daily Produk berhasil dihapus!');
        return redirect()->route('dailyproducts_data');


    }
}
