<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

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

   
    public function main_reports_layouts()
    {
        $store = DB::table('store')->get();
        $transaction = false;
        $products_daily =false;
        $production_products = false;
        $distribution_products = false;
        return view('layouts.main_pages.business_intelligence.reports.main_reports',compact('store', 'transaction', 'products_daily', 'production_products', 'distribution_products'));
    }

    public function generate_reports(Request $request)
    {
        $store = DB::table('store')->get();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;


        $transaction = DB::table('v_main_transactions')
            ->whereDate('transaction_date', '>=', $start_date)
            ->whereDate('transaction_date', '<=', $end_date)
            ->where('transaction_type', 'SALE')
            ->where('store_code', $store_select)->get();
        
        $products_daily = DB::table('v_daily_products')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('store_code', $store_select)->get();

        $production_products = DB::table('v_production_products_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        $distribution_products = DB::table('v_distribution_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
        
        $stores = DB::table('store')->where('store_code', $store_select)->first();


        return view('layouts.main_pages.business_intelligence.reports.main_reports',compact('store','stores', 'transaction', 'products_daily', 'production_products', 'distribution_products'));
    }

    public function exportPdfTransaction(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;
       $print_date = now()->format('dmy');

        $transaction = DB::table('v_main_transactions')
            ->whereDate('transaction_date', '>=', $start_date)
            ->whereDate('transaction_date', '<=', $end_date)
            ->where('transaction_type', 'SALE')
            ->where('store_code', $store_select)->get();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.transaction_pdf',
            compact('transaction', 'start_date', 'end_date', 'store_select')
        );

       return $pdf->download('transaction_'. $print_date . '.pdf');
    }

    public function exportPdfProductDaily(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;
       $print_date = now()->format('dmy');

        $products_daily = DB::table('v_daily_products')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('store_code', $store_select)->get();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.products_daily_pdf',
            compact('products_daily', 'start_date', 'end_date', 'store_select')
        );

       return $pdf->download('products_daily_'. $print_date . '.pdf');
    }

     public function exportPdfProductionProduct(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
       $print_date = now()->format('dmy');

        $production_product = DB::table('v_production_products_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.production_product_pdf',
            compact('production_product', 'start_date', 'end_date')
        );

       return $pdf->download('production_product_'. $print_date . '.pdf');
    }


    public function exportPdfDistributionProduct(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
       $print_date = now()->format('dmy');

        $distribution_product = DB::table('v_distribution_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.distribution_product_pdf',
            compact('distribution_product', 'start_date', 'end_date')
        );

       return $pdf->download('distribution_product_'. $print_date . '.pdf');
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
