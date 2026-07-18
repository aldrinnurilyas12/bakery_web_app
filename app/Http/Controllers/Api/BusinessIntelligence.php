<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;

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


        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $position = $GLOBAL_ENV->position_name ?? null;

        $NOT_ALLOWED_USER = in_array($position, [
            'Casheer'
        ]);

        if($NOT_ALLOWED_USER){
            session()->flash('failed_message', 'Tidak bisa akses!');
            return redirect()->back();
        }

        // Transactions:

        $total_transaction = DB::table('v_main_transactions')
        ->select('transaction_code')
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->count();

        $prev_month_transactions = DB::table('v_main_transactions')
        ->select('transaction_code')
        ->whereMonth('transaction_date', now()->subMonth()->month)
        ->whereYear('transaction_date', now()->subMonth()->year)
        ->count();

        $total_transaction_diff = 0;
        $mom_transaction = 0;
        $total_revenue_diff = 0;
        $mom_revenue = 0;
       

        if($prev_month_transactions > 0){
            $mom_transaction = (($total_transaction - $prev_month_transactions) / $prev_month_transactions * 100); 
            $total_transaction_diff = $total_transaction - $prev_month_transactions;
        }


        // Revenue

        $total_revenue = DB::table('v_main_transactions')
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('grand_total');

        $prev_month_revenue = DB::table('v_main_transactions')
        ->whereMonth('transaction_date', now()->subMonth()->month)
        ->whereYear('transaction_date', now()->subMonth()->year)
        ->sum('grand_total');

        if($prev_month_revenue > 0){
            $mom_revenue = (($total_revenue - $prev_month_revenue) / $prev_month_revenue * 100);
            $total_revenue_diff = $total_revenue - $prev_month_revenue;
        }


        // Customer total

        $total_customer = DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->select('customer_code')->count();

        $prev_customer =  DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->select('customer_code')->count();

        if($prev_customer > 0){
            $mom_customer = (($total_customer - $prev_customer) / $prev_customer * 100);
            $total_customer_diff = $total_customer - $prev_customer;
        }else{
            $mom_customer = $total_customer;
            $total_customer_diff = $total_customer - $prev_customer;
        }   




        $total_product = DB::table('products')->select('product_code')->count();
        $total_category = DB::table('product_category as pc')
            ->join('products as p', 'pc.id', '=', 'p.category_id')
            ->distinct('pc.id')
            ->count('pc.id');
        $stores = DB::table('store')->where('status', 7)->get();
        

        $total_transaction_line_chart = DB::table('v_main_transactions')
        ->selectRaw('MONTH(transaction_date) as month, COUNT(transaction_code) as total')
        ->whereYear('created_at', now()->year)
        ->groupByRaw('MONTH(transaction_date)')
        ->orderByRaw('MONTH(transaction_date)')
        ->get();

        $total_revenue_bar_chart = DB::table('v_main_transactions')
        ->selectRaw('MONTH(transaction_date) as month, SUM(grand_total) as total_revenue')
        ->whereYear('created_at', now()->year)
        ->groupByRaw('MONTH(transaction_date)')
        ->orderByRaw('MONTH(transaction_date)')
        ->get();

        $total_transaction_member_nonmember = DB::table('v_main_transactions')
        ->selectRaw("
            COUNT(
                CASE
                    WHEN customer IS NULL
                    THEN transaction_code
                END
            ) AS total_nonmember,

            COUNT(
                CASE
                    WHEN customer IS NOT NULL
                    THEN transaction_code
                END
            ) AS total_member
        ")
        ->first();




        $revenue_products = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->select(
                DB::raw('MONTH(t.transaction_date) as month'),
                'p.product_name',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.price * td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_revenue
                ")
            )
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'p.product_name'
            )
            ->orderBy(DB::raw('MONTH(t.transaction_date)'))
            ->get();

        $total_sales_category = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->join('product_category as pc', 'p.category_id', '=', 'pc.id')
            ->select(
                DB::raw('MONTH(t.transaction_date) as month'),
                'pc.category_name',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_sales_category
                ")
            )
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'pc.category_name'
            )
            ->orderBy(DB::raw('MONTH(t.transaction_date)'))
        ->get();


       
        $total_sales_payment_method = DB::table('v_main_transactions as t')
            ->join('payment_category as pc', 't.payment_type', '=', 'pc.payment_category')
            ->select(
                'pc.payment_category',
                DB::raw("
                    SUM(t.grand_total) as total_revenue_payment_method
                ")
            )
            ->groupBy(
                'pc.payment_category'
            )
            ->get();


        $top_sales_products = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->select(
                'p.product_name',
                DB::raw("
                    SUM(td.price * td.quantity_per_product) as total_revenue
                "),
                DB::raw("
                    SUM(td.quantity_per_product) as total_sales
                ")
            )
            ->where('t.transaction_type', 'SALE')
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'p.product_name'
            )
            ->orderBy(DB::raw("
                    SUM(td.quantity_per_product) 
                "), 'DESC')
            ->limit(10)->get();

            
        

        
        

       
        $labels = [];
        $data = [];
        $products_revenue = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar',
            4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep',
            10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];



        $products_data = DB::table('products')->select('product_name')->get();
        $category_products = DB::table('product_category')->select('category_name')->get();
        $payment_category = DB::table('payment_category')->select('payment_category')->get();

        // Total Transactions :

        foreach ($total_transaction_line_chart as $item) {
            $labels[] = $monthNames[$item->month];
            $data[] = $item->total;
        }

        // revenue by month

        foreach ($total_revenue_bar_chart as $item) {
            $labels_revenue[] = $monthNames[$item->month];
            $revenue_data[] = $item->total_revenue;
        }

        // Total Revenue Products:

        foreach ($products_data as $product) {
            $labels_products[] = $product->product_name;

            $revenue = $revenue_products
                ->where('product_name', $product->product_name)
                ->sum('total_revenue');

            $products_revenue[] = (float) $revenue;
        }

        // Product Category Pie Chart

        foreach ($category_products as $ctg) {
            $labels_category[] = $ctg->category_name;

            $revenue = $total_sales_category
                ->where('category_name', $ctg->category_name)
                ->sum('total_sales_category');

            $category_total[] = (float) $revenue;
        }

        // total transaction member vs non member:
         $labels_member = [
            'Member',
            'Non Member'
        ];

        $transaction_member = [
            (int) $total_transaction_member_nonmember->total_member,
            (int) $total_transaction_member_nonmember->total_nonmember,
        ];


        // Total revenue by Payment Method:

        foreach ($payment_category as $ctg) {
            $labels_paymethod[] = $ctg->payment_category;

            $revenue = $total_sales_payment_method
                ->where('payment_category', $ctg->payment_category)
                ->sum('total_revenue_payment_method');

            $paycategory_total[] = (float) $revenue;
        }



        // HeatMap transaction:

        $heatmap = DB::table('v_main_transactions')
        ->selectRaw("
            WEEKDAY(transaction_date) as weekday,
            HOUR(transaction_date) as hour,
            COUNT(transaction_code) as total
        ")
        ->whereMonth('transaction_date', date('m'))
        ->whereYear('transaction_date', date('Y'))
        ->whereRaw("HOUR(transaction_date) BETWEEN 8 AND 21")
        ->groupByRaw("WEEKDAY(transaction_date), HOUR(transaction_date)")
        ->get();

        $hours = range(8, 21);

        $data_heatmap = [];

        for ($day = 0; $day <= 6; $day++) {
            foreach ($hours as $hour) {

                $trx = $heatmap
                    ->where('weekday', $day)
                    ->where('hour', $hour)
                    ->first();

                $data_heatmap[] = [
                    'x' => $hour,
                    'y' => $day,
                    'v' => $trx ? $trx->total : 0
                ];
            }
        }

        return view('layouts.main_pages.business_intelligence.data_analytics.data_analytics', compact('total_transaction', 'total_transaction_diff','total_revenue', 'total_revenue_diff','mom_revenue','mom_transaction','mom_customer', 'total_customer_diff', 
        'total_product', 'total_customer', 'total_category', 'stores', 
        'labels', 'data', 'products_revenue', 'labels_products', 'labels_category',
        'labels_revenue','revenue_data', 'category_total', 'paycategory_total', 
        'labels_paymethod', 'top_sales_products', 'transaction_member', 'labels_member', 'data_heatmap'));
    }

    public function filter_dashboard(Request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;

        // Periode bulan sebelumnya
        $prev_start_date = Carbon::parse($start_date)
            ->subMonth()
            ->format('Y-m-d');

        $prev_end_date = Carbon::parse($end_date)
            ->subMonth()
            ->format('Y-m-d');

        $total_transaction = DB::table('v_main_transactions')->select('transaction_code')

            ->where('store_code', $store_select)
            ->whereDate('transaction_date', '>=', $start_date)
            ->whereDate('transaction_date', '<=', $end_date)->count();


        $prev_month_transactions = DB::table('v_main_transactions')
            ->select('transaction_code')

            ->whereDate('transaction_date', '>=', $prev_start_date)
            ->whereDate('transaction_date', '<=', $prev_end_date)
            ->count();

        $total_transaction_diff = 0;
        $mom_transaction = 0;

        if ($prev_month_transactions > 0) {
            $total_transaction_diff = 
                $total_transaction - $prev_month_transactions;

            $mom_transaction = 
            ($total_transaction_diff / $prev_month_transactions) * 100;
        }


        $total_revenue = DB::table('v_main_transactions')

            ->where('store_code', $store_select)
            ->whereDate('transaction_date', '>=', $start_date)
            ->whereDate('transaction_date', '<=', $end_date)
            ->sum('grand_total');

        $prev_month_revenue = DB::table('v_main_transactions')

            ->where('store_code', $store_select)
            ->whereDate('transaction_date', '>=', $prev_start_date)
            ->whereDate('transaction_date', '<=', $prev_end_date)
            ->sum('grand_total');

        $total_revenue_diff = 0;
        $mom_revenue = 0;

        if ($prev_month_revenue > 0) {
            $total_revenue_diff = 
                $total_revenue - $prev_month_revenue;

            $mom_revenue = 
                ($total_revenue_diff / $prev_month_revenue) * 100;
        }


        $total_customer = DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->select('customer_code')->count();

        $prev_customer =  DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereDate('created_at', '>=', $prev_start_date)
        ->whereDate('created_at', '<=', $prev_end_date)
        ->select('customer_code')->count();
        
        $total_customer_diff = 0;
        $mom_customer = 0;

        if($prev_customer > 0){
           $total_customer_diff = $total_customer - $prev_customer;
           $mom_customer = ($total_customer_diff / $prev_customer) * 100;
        }else{
            $mom_customer = $total_customer;
            $total_customer_diff = $total_customer;
        }   


        $total_product = DB::table('products')->select('product_code')->count();
        $total_category = DB::table('product_category as pc')
            ->join('products as p', 'pc.id', '=', 'p.category_id')
            ->distinct('pc.id')
            ->count('pc.id');
        $stores = DB::table('store')->where('status', 7)->get();

        $total_transaction_line_chart = DB::table('v_main_transactions')
        ->selectRaw('MONTH(transaction_date) as month, COUNT(transaction_code) as total')
        ->where('store_code', $store_select)
        ->whereDate('transaction_date', '>=', $start_date)
        ->whereDate('transaction_date', '<=', $end_date)
        ->groupByRaw('MONTH(transaction_date)')
        ->orderByRaw('MONTH(transaction_date)')
        ->get();

        $revenue_products = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->select(
                DB::raw('MONTH(t.transaction_date) as month'),
                'p.product_name',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.price * td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_revenue
                ")
            )
            ->where('t.transaction_type', 'SALE')
            ->where('t.store_code', $store_select)
            ->whereDate('t.transaction_date', '>=', $start_date)
            ->whereDate('t.transaction_date', '<=', $end_date)
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'p.product_name'
            )
            ->orderBy(DB::raw('MONTH(t.transaction_date)'))
            ->get();

        
        $total_sales_category = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->join('product_category as pc', 'p.category_id', '=', 'pc.id')
            ->select(
                DB::raw('MONTH(t.transaction_date) as month'),
                'pc.category_name',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_sales_category
                ")
            )
            ->where('t.transaction_type', 'SALE')
            ->where('t.store_code', $store_select)
            ->whereDate('t.transaction_date', '>=', $start_date)
            ->whereDate('t.transaction_date', '<=', $end_date)
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'pc.category_name'
            )
            ->orderBy(DB::raw('MONTH(t.transaction_date)'))
            ->get();

        $total_sales_payment_method = DB::table('v_main_transactions as t')
            ->join('payment_category as pc', 't.payment_type', '=', 'pc.id')
            ->select(
                'pc.payment_category',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN t.grand_total
                            ELSE 0
                        END
                    ) as total_revenue_payment_method
                ")
            )
            ->where('t.transaction_type', 'SALE')
            ->where('t.store_code', $store_select)
            ->whereDate('t.transaction_date', '>=', $start_date)
            ->whereDate('t.transaction_date', '<=', $end_date)
            ->groupBy(
                'pc.payment_category'
            )
            ->get();

         $top_sales_products = DB::table('products as p')
            ->leftJoin('transactions_detail as td', 'p.product_code', '=', 'td.product')
            ->leftJoin('v_main_transactions as t', 'td.transaction_code', '=', 't.transaction_code')
            ->select(
                'p.product_name',
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.price * td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_revenue
                "),
                DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.quantity_per_product
                            ELSE 0
                        END
                    ) as total_sales
                ")
            )
            ->where('t.transaction_type', 'SALE')
            ->where('t.store_code', $store_select)
            ->whereDate('t.transaction_date', '>=', $start_date)
            ->whereDate('t.transaction_date', '<=', $end_date)
            ->groupBy(
                DB::raw('MONTH(t.transaction_date)'),
                'p.product_name'
            )
            ->orderBy(DB::raw("
                    SUM(
                        CASE
                            WHEN t.transaction_type = 'SALE'
                            THEN td.quantity_per_product
                            ELSE 0
                        END
                    ) 
                "), 'DESC')
            ->limit(10)->get();


        $labels = [];
        $data = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar',
            4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep',
            10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $products_data = DB::table('products')->select('product_name')->get();
        $category_products = DB::table('product_category')->select('category_name')->get();
         $payment_category = DB::table('payment_category')->select('payment_category')->get();

         $total_revenue_bar_chart = DB::table('v_main_transactions')
        ->selectRaw('MONTH(transaction_date) as month, SUM(grand_total) as total_revenue')
        ->whereDate('transaction_date', '>=', $start_date)
        ->whereDate('transaction_date', '<=', $end_date)
        ->groupByRaw('MONTH(transaction_date)')
        ->orderByRaw('MONTH(transaction_date)')
        ->get();

        // Filter by Total Transaction:
        foreach ($total_transaction_line_chart as $item) {
            $labels[] = $monthNames[$item->month];
            $data[] = $item->total;
        }

        // Filter by Revenue Products
         foreach ($products_data as $product) {
            $labels_products[] = $product->product_name;

            $revenue = $revenue_products
                ->where('product_name', $product->product_name)
                ->sum('total_revenue');

            $products_revenue[] = (float) $revenue;
        }

        $labels_revenue = [];
        $revenue_data = [];
        
        foreach ($total_revenue_bar_chart as $item) {
            $labels_revenue[] = $monthNames[$item->month];
            $revenue_data[] = $item->total_revenue;
        }

        // Filter by Sales by Category:
         foreach ($category_products as $ctg) {
            $labels_category[] = $ctg->category_name;

            $revenue = $total_sales_category
                ->where('category_name', $ctg->category_name)
                ->sum('total_sales_category');

            $category_total[] = (float) $revenue;
        }

        // Filter Revenue by Payment Method:

         foreach ($payment_category as $ctg) {
            $labels_paymethod[] = $ctg->payment_category;

            $revenue = $total_sales_payment_method
                ->where('payment_category', $ctg->payment_category)
                ->sum('total_revenue_payment_method');

            $paycategory_total[] = (float) $revenue;
        }

        $total_transaction_member_nonmember = DB::table('v_main_transactions')
        ->selectRaw("
            COUNT(
                CASE
                    WHEN customer IS NULL
                    THEN transaction_code
                END
            ) AS total_nonmember,

            COUNT(
                CASE
                    WHEN customer IS NOT NULL
                    THEN transaction_code
                END
            ) AS total_member
        ")
        ->first();


         $labels_member = [
            'Member',
            'Non Member'
        ];

        $transaction_member = [
            (int) $total_transaction_member_nonmember->total_member,
            (int) $total_transaction_member_nonmember->total_nonmember,
        ];

        $heatmap = DB::table('v_main_transactions')
        ->selectRaw("
            WEEKDAY(transaction_date) as weekday,
            HOUR(transaction_date) as hour,
            COUNT(transaction_code) as total
        ")
        ->whereMonth('transaction_date', date('m'))
        ->whereYear('transaction_date', date('Y'))
        ->whereRaw("HOUR(transaction_date) BETWEEN 8 AND 21")
        ->groupByRaw("WEEKDAY(transaction_date), HOUR(transaction_date)")
        ->get();

        $hours = range(8, 21);

        $data_heatmap = [];

        for ($day = 0; $day <= 6; $day++) {
            foreach ($hours as $hour) {

                $trx = $heatmap
                    ->where('weekday', $day)
                    ->where('hour', $hour)
                    ->first();

                $data_heatmap[] = [
                    'x' => $hour,
                    'y' => $day,
                    'v' => $trx ? $trx->total : 0
                ];
            }
        }


        return view('layouts.main_pages.business_intelligence.data_analytics.data_analytics', compact('total_transaction', 'mom_transaction','total_revenue'
        ,'mom_revenue','total_transaction_diff', 'total_revenue_diff', 'total_product', 'total_customer','mom_customer', 
        'total_customer_diff', 'total_category', 'stores', 'labels', 'data', 'total_transaction_line_chart'
        , 'products_revenue', 'labels_products', 'labels_category', 'category_total', 'paycategory_total','labels_member',
         'labels_paymethod','labels_revenue','revenue_data', 'top_sales_products', 'transaction_member', 'data_heatmap', 'heatmap'));
    }

    // Customer Data Analytics

     public function data_analytics_customer()
    {


        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $position = $GLOBAL_ENV->position_name ?? null;

        $NOT_ALLOWED_USER = in_array($position, [
            'Casheer'
        ]);

        if($NOT_ALLOWED_USER){
            session()->flash('failed_message', 'Tidak bisa akses!');
            return redirect()->back();
        }

        // total customer :
         $new_customer = DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->select('customer_code')->count();

        $prev_customer =  DB::table('customer')
        ->where('account_email_verified', 'Y')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->select('customer_code')->count();

        if($prev_customer > 0){
            $mom_customer = (($new_customer - $prev_customer) / $prev_customer * 100);
            $total_customer_diff = $new_customer - $prev_customer;
        }else{
            $mom_customer = $new_customer;
            $total_customer_diff = $new_customer - $prev_customer;
        }   


        $active_customer = DB::table('customer')
        ->where('status', 7)->where('account_email_verified','Y')
        ->select('customer_code')->count();

        $total_customer = DB::table('customer')
        ->select('customer_code')->count();

        $nonactive_customer = DB::table('customer')
        ->where('status', 8)
        ->select('customer_code')->count();

        $rfm_data = DB::table('v_rfm_analysis')->get();

        // Horizontal Chart customer spent money

        // $customer_data = DB::table('customer')->get();

        $customer_revenue = DB::table('v_main_transactions')
        ->selectRaw('name as customer, SUM(grand_total) as total_spent')
        ->groupByRaw('name')
        ->get();

        foreach ($customer_revenue as $item) {
            $labels_customer[] = $item->customer;
            $revenue_customer[] = $item->total_spent;
        }

        $sub = DB::table('v_main_transactions')
            ->selectRaw("
                customer,
                COUNT(*) as total_transaction
            ")
            ->groupBy('customer');

        $total_transaction_segment = DB::query()
            ->fromSub($sub, 't')
            ->selectRaw("
                CASE
                    WHEN total_transaction BETWEEN 10 AND 20 THEN 'Champions'
                    WHEN total_transaction BETWEEN 8 AND 15 THEN 'Loyal Customers'
                    WHEN total_transaction BETWEEN 2 AND 4 THEN 'Potential Loyalist'
                    ELSE 'Risk Churn'
                END as segment,
                COUNT(*) as total_customer
            ")
            ->groupBy('segment')
            ->get();

        $segment_labels = [];
        $segment_values = [];

        foreach ($total_transaction_segment as $row) {
            $segment_labels[] = $row->segment;
            $segment_values[] = $row->total_customer;
        }


        return view('layouts.main_pages.business_intelligence.data_analytics.customer_data_analytics',compact('total_customer', 'mom_customer', 
        'total_customer_diff', 'active_customer', 'nonactive_customer',
         'new_customer', 'rfm_data','labels_customer', 'revenue_customer', 'segment_labels', 'segment_values'));

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

            ->where('store_code', $store_select)->get();
        
        $store_name = DB::table('store')->where('store_code', $store_select)->first();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.transaction_pdf',
            compact('transaction', 'start_date', 'end_date', 'store_name')
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

         $store_name = DB::table('store')->where('store_code', $store_select)->first();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.products_daily_pdf',
            compact('products_daily', 'start_date', 'end_date', 'store_select', 'store_name')
        );

       return $pdf->download('products_daily_'. $print_date . '.pdf');
    }

     public function exportPdfProductionProduct(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;
       $print_date = now()->format('dmy');

        $production_product = DB::table('v_production_products_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();

         $store_name = DB::table('store')->where('store_code', $store_select)->first();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.production_product_pdf',
            compact('production_product', 'start_date', 'end_date', 'store_name')
        );

       return $pdf->download('production_product_'. $print_date . '.pdf');
    }


    public function exportPdfDistributionProduct(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_select = $request->store;
       $print_date = now()->format('dmy');

        $distribution_product = DB::table('v_distribution_detail')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->get();
         $store_name = DB::table('store')->where('store_code', $store_select)->first();

        $pdf = Pdf::loadView(
            'layouts.main_pages.business_intelligence.reports.pdf.distribution_product_pdf',
            compact('distribution_product', 'start_date', 'end_date', 'store_name')
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
