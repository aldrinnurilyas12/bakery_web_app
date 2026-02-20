<?php

namespace App\Http\Controllers\Api;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\ItemsCategoryModel;
use App\Models\ProductsModel;
use App\Models\TransactionDetail;
use App\Models\TransactionDetailInformationModel;
use App\Models\TransactionModel;
use App\Models\TransactionsVouchers;
use App\Models\VoucherCustomer;
use App\Models\VoucherModel;
use App\Models\VouchersUsages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $show_transaction = DB::table('v_transaction')
        ->select('transaction_code', DB::raw('GROUP_CONCAT(product_name) as product_name'),'customer_code','name','email', 'casheer' , 'quantity_per_product','grand_total', 'transaction_date')
        ->groupBy('transaction_code', 'customer_code','name','email','casheer','quantity_per_product','grand_total', 'transaction_date')
        ->orderBy('transaction_date', 'DESC')->get();
        $main_transaction = DB::table('v_main_transactions')->orderBy('transaction_date', 'DESC')
        ->whereDate('transaction_date', now()->format('Y-m-d'))->paginate(500);

        

        // $show_transaction_array_data =  $show_transaction->map(function ($transaction) {
        //     $product_names = explode(',', $transaction->product_name);
        //     if (count($product_names) > 2) {
        //         $transaction->product_name = array_slice($product_names, 0, 2);
        //         $transaction->product_name[] = 'dan lainnya';
        //     } else {
        //         $transaction->product_name = $product_names;
        //     }

        //     return $transaction;
        // });

        return view('layouts.main_pages.transactions.transaction', compact('show_transaction', 'main_transaction'));
    }

    public function filter_transaction(Request $request) {


        if($request->filter_transaction){
           $query = DB::table('v_main_transactions')
            ->orderBy('transaction_date', 'DESC');

            if ($request->filter_transaction) {

                if ($request->filter_transaction == 'today') {
                    $query->whereDate('transaction_date', Carbon::today());
                }

                if ($request->filter_transaction == 'week') {
                    $query->whereBetween('transaction_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                }

                if ($request->filter_transaction == 'month') {
                    $query->whereMonth('transaction_date', Carbon::now()->month)
                        ->whereYear('transaction_date', Carbon::now()->year);
                }
            }

             if ($request->filter_transaction == 'month') {
                    $main_transaction = $query->paginate(400);
             }else {

                 $main_transaction = $query->get();
             }


            return view('layouts.main_pages.transactions.transaction', compact('main_transaction'));
        }




    }


    public function download_excel(Request $request)
    {
    $filter = $request->filter_transaction;

    $query = DB::table('v_main_transactions')
        ->orderBy('transaction_date', 'DESC');

    if ($filter === 'today') {
        $query->whereDate('transaction_date', Carbon::today());
        $date = Carbon::today()->format('d-m-Y');
    }

    if ($filter === 'week') {
        $query->whereBetween('transaction_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);

        $start = Carbon::now()->startOfWeek();
        $end   = Carbon::now()->endOfWeek();
        $date = $start->format('d-m-Y') . '_sd_' . $end->format('d-m-Y');
    }

    if ($filter === 'month') {
        $query->whereMonth('transaction_date', Carbon::now()->month)
              ->whereYear('transaction_date', Carbon::now()->year);
        $date = Carbon::now()->format('F_Y');
    }
      $filename = 'Data_transaksi_'.$date. '.xlsx';

     return Excel::download(new TransactionExport($filter), $filename);
}


    public function show_promo_code(Request $request) {
    $voucher_code = $request->promo_code;
    $customer = $request->customer;


    $show_voucher =DB::table('customer_vouchers as cv')
                    ->leftJoin('voucher as v', 'cv.voucher', '=', 'v.voucher_code')
                    ->select('v.voucher_code','v.discount', 'v.nominal')
                    ->where('cv.voucher', $voucher_code)
                    ->where('cv.customer', $customer)
                    ->where('cv.voucher_used', 'N')->first();

    if ($voucher_code) {
        return response()->json([
            'data' => $show_voucher,
            'message' => 'Data voucher ditemukan',
            'status' => 'success'
        ]);
    } 
}


    /**
     * Show the form for creating a new resource.
     */
    public function transaction_create_layout(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
         $category_data = DB::table('product_category as c')->select(DB::raw("REPLACE(c.category_name, ' ', '_') as 'category_name'"))
                ->join('products as p', 'c.id', '=', 'p.category_id')
                ->join('production_products as pp', 'p.product_code', '=', 'pp.product')
                ->join('products_daily as pd', 'pp.production_code', '=', 'pd.production')
                ->groupBy('c.category_name')->get();
        $all_products =  DB::table('v_daily_products')->where('status', 'Ready')->where('store_id', $store )->paginate(15);
        $payment_type = DB::table('payment_category')->get();

        $itemProducts = ProductsModel::with('category')->get();
        $promo_code = $request->promo_code;

        $show_promo = $this->show_promo_code($request);

        $casheer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik .'-'. app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name;


        // section cart:
        $cart_value = Session::get('cart', []);

        $qty = 0;

        foreach ($cart_value as $item) {

            $qty += $item['quantity'];
        }

        $total_products = 0;

        foreach ($cart_value as $item) {

            $total_products = $item['quantity'];
        }

        $price_total = 0;


        foreach ($cart_value as $item) {
            $price_total += $item['price'];
        }

        $grand_total = 0;

        foreach ($cart_value as $item) {
            $grand_total += $item['price'];
        }


        return view('layouts.main_pages.transactions.create.transaction_create', compact('total_products','show_promo','grand_total', 'price_total', 'qty', 'cart_value', 'all_products', 'category_data', 'itemProducts', 'payment_type', 'casheer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'product' => 'required|array',
        'product.*' => 'required|exists:products_daily,daily_code',
        'quantity_per_product' => 'required|array',
        'quantity_per_product.*' => 'required|integer|min:1',
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $inv_date = Carbon::now()->format('Ymd');
        $transaction_code = 'INV' . $inv_date . $unique_code;

        $productCode = $request->product;
        $qtyProducts = $request->quantity_per_product;
        $casheer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $customer = $request->customer;
        $voucher_code = $request->promo_code;
        $voucher_quota = DB::table('voucher')->where('voucher_code', $voucher_code)->value('quota');
        $voucherExpired = VoucherModel::where('voucher_code', $voucher_code)->where('status', 7)->value('end_date');
        
        // HITUNG TOTAL VOUCHER YANG SUDAH DIGUNAKAN
        $voucherQuotaUsedTotal = DB::table('transactions_voucher as vu')
                    ->leftJoin('voucher as v', 'v.voucher_code', '=', 'vu.voucher_code')
                    ->where('vu.voucher_code', $voucher_code)->where('vu.voucher_used', 'Y')->count('vu.voucher_code');


        $checkCustomerVoucherUsed =DB::table('transactions_voucher as tv')
                    ->leftJoin('transactions as t', 'tv.transaction_code', '=', 't.transaction_code')
                    ->where('tv.voucher_code', $voucher_code)
                    ->where('t.customer', $customer)->first();

        if($voucher_code){
            if($voucherExpired && Carbon::now()->gt(Carbon::parse($voucherExpired)) ){
                session()->flash('failed_voucher', 'Masa Berlaku Voucher sudah habis');
                return redirect()->back(); 
            }

            if($voucherQuotaUsedTotal >= $voucher_quota){
                session()->flash('failed_voucher', 'Kuota Voucher Sudah Digunakan semua');
                return redirect()->back(); 
                
            }

            if($checkCustomerVoucherUsed){
                session()->flash('failed_voucher', 'E-Voucher ini sudah digunakan');
                return redirect()->back(); 
            }
        }


        $main_transaction = TransactionModel::create([
            'transaction_code' => $transaction_code,
            'total_amount' => $request->total_amount,
            'grand_total' => $request->grand_total,
            'casheer' => $casheer,
            'customer' => $request->customer,
            'status' => 5,
            'store' => $store,
            'payment_type' => $request->payment_type,
            'payment_changes' => $request->payment_changes,
            'transaction_date' => now(),
            'created_by' => $casheer,
            'created_at' => now()
        ]);

     
        foreach ($productCode as $index => $productId) {
               TransactionDetail::create([
                    'transaction_code' => $main_transaction->transaction_code,
                    'product' => $productId,
                    'quantity_per_product' => $qtyProducts[$index],
                    'created_by' => $casheer,
                    'created_at' => now()
                ]);
        }

        if($voucher_code){

            TransactionsVouchers::create([
                'transaction_code' => $main_transaction->transaction_code,
                'voucher_code' => $voucher_code,
                'status' => 7,
                'voucher_used' => 'Y',
                'used_at' => now(),
                'created_at' => now(),
                'created_by' => $casheer,
                'created_at' => now()
            ]);

            VoucherCustomer::where('voucher', $voucher_code)->where('customer', $customer)->update([
                'voucher_used' => 'Y',
                'updated_at' => now()
            ]);
        }
        

    // PROSEDUR GET POINT FOR CUSTOMERS WHEN TRANSACTIONS :
    $transactionDetail = DB::table('transactions_detail as td')
        ->join('products_daily as pd', 'td.product', '=', 'pd.daily_code')
        ->join('production_products as pp', 'pd.production', '=', 'pp.production_code')
        ->where('transaction_code', $main_transaction->transaction_code)
        ->get();

    // FIX THIS CHANGE TO TABLE PRODUCTS_POINT
    $productPoints = DB::table('products_point')
        ->whereNotNull('product')->where('status', 7)
        ->pluck('point', 'product');

    $customerTransaction = DB::table('transactions')
        ->where('customer', $main_transaction->customer)
        ->first();

    $customerPoint = DB::table('customer')
        ->where('customer_code', $main_transaction->customer)
        ->value('point') ?? 0;
    
    $totalPoints = 0;

    if ($customerTransaction) {

        foreach($transactionDetail as $detail) {

            if (!empty($detail->product) && isset($productPoints[$detail->product])) {
                $totalPoints += $productPoints[$detail->product];
            }
        }

        DB::table('customer')
            ->where('customer_code', $main_transaction->customer)
            ->update(['point' => $totalPoints + $customerPoint
        ]);
    }

            

        // PROSEDUR PEMBAGIAN E-VOUCHER ke CUSTOMER 
        $getAmount = $main_transaction->grand_total;
        $get_voucher = DB::table('voucher')
            ->where('min_transaction','<=' , $getAmount)
            ->where('status', 7)
            ->where('voucher_type', 'regular')
            ->orderBy('min_transaction', 'desc')->first();
        

        if($get_voucher) {

            $voucherShared = VoucherCustomer::where('customer', $customer)
            ->where('voucher', $get_voucher->voucher_code)->exists();
            $voucherCustomer = DB::table('customer_vouchers as cv')
            ->where('voucher', $get_voucher->voucher_code)
            ->count();
            $voucher_quota =  $get_voucher->quota;
            $checkingQuotaVoucher = $voucherCustomer >= $voucher_quota;
            $voucherExpired = now()->greaterThan($get_voucher->end_date);

            if($customerTransaction){
                if(!$voucherShared) {
                    if($getAmount >= $get_voucher->min_transaction) {
                        if(!$checkingQuotaVoucher && $get_voucher){
                            if(!$voucherExpired) {
                                VoucherCustomer::create([
                                    'customer' => $main_transaction->customer,
                                    'voucher' => $get_voucher->voucher_code,
                                    'transaction' => $main_transaction->transaction_code,
                                    'status' => 7,
                                    'voucher_used' => 'N',
                                    'created_by' => $casheer,
                                    'created_at' => now()
                                ]);
                            }
                        }
                    }
                }
            }
        }

        Session::forget('cart');
        session()->flash('message_success', 'Transaksi berhasil!');
        return redirect()->route('invoice_detail', $main_transaction->transaction_code);
    }

    public function invoice(Request $request): View
    {
        $invoice = DB::table('v_transaction')
        ->where('transaction_code', $request->transaction_code)
            ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)->get();
        
        return view('layouts.main_pages.invoice.invoice', compact('invoice', 'invoices'));
    }

    public function show_customer(Request $request) 
    {
        $keyword = $request->keyword;
        $search = DB::table('customer as c')
        ->leftJoin('status_category as s', 'c.status', '=', 's.id')->where('customer_code','LIKE', "%{$keyword}%")
                                        ->orWhere('phone_number','LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%")->limit(5)->get();

        return response()->json($search);
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
