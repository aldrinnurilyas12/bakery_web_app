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

        $products = DB::table('v_products as vp')->select('vp.product_code', 'vp.product')
            ->leftJoin('products_daily as dp', 'vp.product_code', '=', 'dp.product_code')
            ->where('dp.product_code', null)->get();
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
            'stock_available' => $request->stock_available,
            'status' => $request->status,
            'point' => $request->point,
            'created_at' => now(),
            'created_by' => $created_by
        ]);

        session()->flash('message_success', 'Data Daily Produk berhasil disimpan!');
        return redirect()->route('dailyproducts_data');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        
        DailyProductsModel::where('product_code', $request->product_code)->update([
            'status' => $request->status,
            'point' => $request->point,
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

        DB::table('products_daily')->where('product_code', $request->product_code)->delete();

        session()->flash('message_success', 'Data Daily Produk berhasil dihapus!');
        return redirect()->route('dailyproducts_data');


    }
}
