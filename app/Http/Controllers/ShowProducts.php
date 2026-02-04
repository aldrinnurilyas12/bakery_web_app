<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowProducts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

     public function show_products() {
        $products = DB::table('v_daily_products as vp')
                    ->leftJoin('product_images as pi', 'vp.product_code', '=', 'pi.product_code')
                    ->where('status', 'Ready')
                ->get();

        $category_data = DB::table('product_category as c')->select('c.id' , DB::raw("REPLACE(c.category_name, ' ', '_') as 'category_name'"))
                ->join('products as p', 'c.id', '=', 'p.category_id')
                ->join('products_daily as pd', 'p.product_code', '=', 'pd.product_code')
                ->groupBy('c.id','c.category_name')->get();
        return response()->json([
            'products' => $products,
            'category_products' => $category_data,
            'message' => 'Data products!'
        ]);
    }

    public function product_detail(Request $request) 
    {
        $product_detail = DB::table('v_daily_products as vp')
                    ->leftJoin('product_images as pi', 'vp.product_code', '=', 'pi.product_code')
                    ->where('vp.product_code', $request->product_code)
                    ->first();

        return response()->json([
            'product' => $product_detail,
            'message' => 'Product Detail'
        ]);
    }

    public function show_promos() {
        $promo = DB::table('v_promos')->limit(6)->get();

        return response()->json([
            'data' => $promo,
            'message' => 'data promo'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
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
