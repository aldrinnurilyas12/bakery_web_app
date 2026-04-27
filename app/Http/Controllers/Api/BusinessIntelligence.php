<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BusinessIntelligence extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function data_analytics_layouts()
    {
        return view('layouts.main_pages.business_intelligence.data_analytics.data_analytics');
    }

    public function sales_performance(Request $rq)
    {
        $products_sales = DB::table('v_product_sales_performance')->get();
        return view('layouts.main_pages.business_intelligence.sales_performance', compact('products_sales'));
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
