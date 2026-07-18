<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CentralStockProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $central_stock = DB::table('v_central_stock_products')->get();
        return view('layouts.main_pages.central_stock_products.central_stock_products', compact('central_stock'));
    }

    
    public function product_info($product, $variant = null)
    {

        $query = DB::table('production_products_detail as ppd')
        ->join('production_products as pp', 'ppd.production_code', '=', 'pp.production_code')
        ->join('products as p', 'ppd.product', '=', 'p.product_code')
        ->where('ppd.product', $product)
        ->where('pp.status', '=', 5);

        if($variant){
            $query->where('ppd.variant', $variant);
        }

        $detail_product = $query->get();
        return view('layouts.main_pages.central_stock_products.product-detail-info', compact('detail_product'));
    }

     public function product_info_distribution($product, $variant = null)
    {

        $query = DB::table('distribution_products_detail as dpd')
        ->join('distribution_products as dp', 'dpd.distribution', '=', 'dp.distribution_code')
        ->join('store as st', 'dpd.store', '=', 'st.store_code')
        ->join('products as p', 'dpd.product', '=', 'p.product_code')
        ->where('dpd.product', $product)
        ->where('dp.status', '=', 26);

        if($variant){
            $query->where('dpd.variant', $variant);
        }

        $total_distribution_item = $query->sum('quantity');

        $detail_product = $query->get();

       
        return view('layouts.main_pages.central_stock_products.distribution-detail-info', compact('detail_product', 'total_distribution_item'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
