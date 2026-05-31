<?php

namespace App\Http\Controllers\Api;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Mail\GetVoucherInfoCustomer;
use App\Models\FraudTransactions;
use App\Models\FraudTransactionTimeline;
use App\Models\ItemsCategoryModel;
use App\Models\ProductsModel;
use App\Models\RedeemRewardModel;
use App\Models\TransactionDetail;
use App\Models\TransactionDetailInformationModel;
use App\Models\TransactionModel;
use App\Models\TransactionsVouchers;
use App\Models\VoucherCustomer;
use App\Models\VoucherModel;
use App\Models\VoucherRedeem;
use App\Models\VouchersUsages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\UserLogActivity;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $USER_STORE = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code; 


        $show_transaction = DB::table('v_transaction')
        ->select('transaction_code', DB::raw('GROUP_CONCAT(product_name) as product_name'),'customer_code','name','email', 'casheer' , 'quantity_per_product','grand_total', 'transaction_date')
        ->groupBy('transaction_code', 'customer_code','name','email','casheer','quantity_per_product','grand_total', 'transaction_date')
        ->orderBy('transaction_date', 'DESC')->get();

        $main_transaction = DB::table('v_main_transactions')->where('store_code', $USER_STORE)->orderBy('transaction_date', 'DESC')
        ->whereDate('transaction_date', now()->format('Y-m-d'))->paginate(500);

        $show_items = DB::table('transactions_detail as td')
                                    ->leftJoin('transactions as t', 'td.transaction_code', '=', 't.transaction_code')
                                    ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
                                    ->leftJoin('product_variant as pv', 'td.variant', '=', 'pv.variant_code')
                                    ->get();

        $transaction_with_items = DB::table('transactions_detail as td')
             ->leftJoin('transactions as t', 'td.transaction_code', '=', 't.transaction_code')
             ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
             ->select('td.transaction_code')
             ->distinct()
             ->pluck('td.transaction_code')
             ->toArray();

        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name, [
            'Supervisor',
            'Manager',
            ]);

        $stores = DB::table('store')->get();
       

        return view('layouts.main_pages.transactions.transaction', compact('show_transaction', 'main_transaction', 'show_items','transaction_with_items', 'session_user', 'user_permission_forbidden', 'stores'));
    }

    public function filter_transaction(Request $request) {

        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        $show_items = DB::table('transactions_detail as td')
                                    ->leftJoin('transactions as t', 'td.transaction_code', '=', 't.transaction_code')
                                    ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
                                    ->leftJoin('product_variant as pv', 'td.variant', '=', 'pv.variant_code')
                                    ->get();

        $transaction_with_items = DB::table('transactions_detail as td')
             ->leftJoin('transactions as t', 'td.transaction_code', '=', 't.transaction_code')
             ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
             ->select('td.transaction_code')
             ->distinct()
             ->pluck('td.transaction_code')
             ->toArray();

        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name, [
            'Supervisor',
            'Manager',
            ]);

        $stores = DB::table('store')->get();

        $rq_store = $request->store;

        if($request->filter_transaction){
           $query = DB::table('v_main_transactions')
            ->orderBy('transaction_date', 'DESC');

            if ($request->filter_transaction) {

                if ($request->filter_transaction == 'today') {
                    $query->whereDate('transaction_date', Carbon::today())
                    ->where('store_code', $rq_store ?? $store);
                }

                if ($request->filter_transaction == 'week') {
                    $query->whereBetween('transaction_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])->where('store_code', $rq_store ?? $store);
                }

                if ($request->filter_transaction == 'month') {
                    $query->whereMonth('transaction_date', Carbon::now()->month)
                        ->whereYear('transaction_date', Carbon::now()->year)
                          ->where('store_code', $rq_store ?? $store);
                }

                if($request->filter_transaction == 'year'){
                     $query->whereYear('transaction_date', Carbon::now()->year)
                          ->where('store_code', $rq_store ?? $store);
                 }
            }
            


            // Mengatur data sebanyak 400
             if ($request->filter_transaction == 'month' || $request->filter_transaction == 'year' ) {
                    $main_transaction = $query->paginate(500);
             }else {

                 $main_transaction = $query->get();
             }


            return view('layouts.main_pages.transactions.transaction', compact('main_transaction', 'show_items', 'transaction_with_items', 'user_permission_forbidden', 'stores', 'session_user'));
        }




    }

    public function download_pdf(Request $request, $id)
    {
        $transaction_code = $request->transaction_code;
         $invoice = DB::table('v_transaction')
                ->where('transaction_code', $request->transaction_code)
                ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)->get();
        $pdf = Pdf::loadView('layouts.main_pages.invoice.invoice', compact('invoices', 'invoice'));
        return $pdf->download('invoice_'. $transaction_code . '.pdf');
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
                    ->select('v.voucher_code','v.discount', 'v.nominal', 'cv.customer', 
                    'cv.voucher_used','cv.status', 'v.end_date')
                    ->leftJoin('voucher as v', 'cv.voucher', '=', 'v.voucher_code')
                    ->leftJoin('customer as c','cv.customer', '=', 'c.customer_code')
                    ->where('cv.customer_voucher_code', $voucher_code)
                    ->first();
    
                    
    if(!$show_voucher){
       return response()->json([
            'message' => 'E-Voucher tidak ditemukan!',
            'status' => 'voucher_not_found'
        ]);

    }


    if (Carbon::parse($show_voucher->end_date)->lt(now())) {
        return response()->json([
            'message' => 'E-Voucher Expired!',
            'status' => 'voucher_expired'
        ]);
    }

     if($show_voucher->customer !== $customer){
        return response()->json([
            'message' => 'E-Voucher invalid!',
            'status' => 'voucher_not_matching'
        ]);
    }


    if($show_voucher->voucher_used === 'Y' && $show_voucher->status === 8 ){
        return response()->json([
            'message' => 'E-Voucher invalid!',
            'status' => 'voucher_used'
        ]);
    }

        return response()->json([
            'data' => $show_voucher,
            'message' => 'Data voucher ditemukan',
            'status' => 'success'
        ]);
    
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
                ->join('distribution_products_detail as pp', 'p.product_code', '=', 'pp.product')
                ->join('products_daily as pd', 'pp.distribution_store_code', '=', 'pd.distribution_store')
                ->groupBy('c.category_name')->get();
        $all_products =  DB::table('v_daily_products')->where('status', 'Ready')->where('store_id', $store )->paginate(15);
        $payment_type = DB::table('payment_category')->get();

        $itemProducts = ProductsModel::with('category')->get();
        $promo_code = $request->promo_code;

        $show_promo = $this->show_promo_code($request);

        $casheer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik .'-'. app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->name;


        // OPERATIONAL HOURS

        $transaction_hour = Carbon::now('Asia/Jakarta')->hour;
        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $position = $GLOBAL_ENV->position_name ?? null;

        $NOT_ALLOWED_USER = in_array($position, [
            'Manager',
            'Supervisor'
        ]);

        if($NOT_ALLOWED_USER){
            session()->flash('failed_message', 'Tidak bisa akses!');
            return redirect()->back();
        }

        if($transaction_hour < 8){
            session()->flash('failed_message', 'Sistem belum buka!');
            return redirect()->back();
        }

        if($transaction_hour >=21){
            session()->flash('failed_message', 'Jam operasional sistem sudah tutup!');
            return redirect()->back();
        }
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

    // MASTER MODULE TRANSACTIONS:

    public function store(Request $request)
    {
       $request->validate([
        'product' => 'required|array',
        'product.*' => 'required|exists:products,product_code',
        'variant' => 'nullable|array',
        'variant.*' => 'nullable|string',
        'product_price' => 'array',
        'quantity_per_product' => 'required|array',
        'quantity_per_product.*' => 'required|integer|min:1',
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $inv_date = Carbon::now()->format('Ymd');
        $transaction_code = 'INV' . $inv_date . $unique_code;
        

        $casheer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $IT_GUY = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->position_name == 'IT Developer';
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;
        $store_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        $productCode = $request->product;
        $variants = $request->variant ?? [];
        $price = $request->product_price;
        $qtyProducts = $request->quantity_per_product;
        $customer = $request->customer;
        $voucher_code = $request->promo_code;
        $codeVoucher = $request->code_voucher;
        $payment_type = $request->payment_type;
        $voucher_quota = DB::table('voucher')->where('voucher_code', $codeVoucher)->value('quota');
        $voucherExpired = VoucherModel::where('voucher_code', $voucher_code)->where('status', 7)->value('end_date');
        $date = now()->format('Ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $customerVoucher = 'VOUCHER'. $date . $unique_code;
        $outlet_operational = now()->hour;
        
        // HITUNG TOTAL VOUCHER YANG SUDAH DIGUNAKAN
        $voucherQuotaUsedTotal = DB::table('transactions_voucher as vu')
                    ->leftJoin('voucher as v', 'v.voucher_code', '=', 'vu.voucher_code')
                    ->where('vu.voucher_code', $codeVoucher)->where('vu.voucher_used', 'Y')->count('vu.voucher_code');

        // perbaiki bagian ini
        $checkCustomerVoucherUsed =DB::table('transactions_voucher as tv')
                    ->leftJoin('transactions as t', 'tv.transaction_code', '=', 't.transaction_code')
                    ->where('tv.customer_voucher', $codeVoucher)
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

        if(empty($payment_type)){
            session()->flash('failed_voucher', 'Pilih dahulu metode pembayaran');
            return redirect()->back();
        }

        $main_transaction = TransactionModel::create([
            'transaction_code' => $transaction_code,
            'total_amount' => $request->total_amount,
            'grand_total' => $request->grand_total,
            'casheer' => $casheer,
            'customer' => $request->customer,
            'status' => 5,
            'store' => $store_code,
            'payment_type' => $request->payment_type,
            'payment_changes' => $request->payment_changes,
            'transaction_date' => now(),
            'created_by' => $casheer,
            'created_at' => now()
        ]);

     
        foreach ($productCode as $index => $productId) {
             $transaction_detail =  TransactionDetail::create([
                    'transaction_code' => $main_transaction->transaction_code,
                    'product' => $productId,
                    'variant' => $variants[$index] ?? null,
                    'price' => $price[$index],
                    'quantity_per_product' => $qtyProducts[$index],
                    'created_by' => $casheer,
                    'created_at' => now()
                ]);
        }


        // FRAUD TRANSACTIONS IDENTIFICATION => RULE -> OUTSIDE_OPERATIONAL_HOURS
        // fraud status info = 1 => Fraud, 2 => NotFraud, 3 => fraud_detected

        // fraud => empty payment_method
        if(empty($payment_type)){
              $fraud =  FraudTransactions::create([
                    'fraud_code' => 'FRD'. '-' . $inv_date . '-' . $unique_code,
                    'transaction' => $transaction_code,
                    'fraud_type' => 8,
                    'fraud_status_info' => '3',
                    'severity_level' => 'medium',
                    'status' => 22,
                    'created_at' => now()
                ]);

                FraudTransactionTimeline::create([
                    'fraud' => $fraud->fraud_code,
                    'status' => $fraud->status
                ]);
        }

        // fraud => empty product 
        if (empty($productCode) && empty($qtyProducts))
            {
              $fraud = FraudTransactions::create([
                    'fraud_code' => 'FRD'. '-' . $inv_date . '-' . $unique_code,
                    'transaction' => $transaction_code,
                    'fraud_type' => 6,
                    'fraud_status_info' => '3',
                    'severity_level' => 'high',
                    'status' => 22,
                    'created_at' => now()
                ]);

                FraudTransactionTimeline::create([
                    'fraud' => $fraud->fraud_code,
                    'status' => $fraud->status
                ]);
        }

        // fraud => outside operational outlet hour
        if($outlet_operational > 22 || $outlet_operational < 8){
            if($IT_GUY){    
              $fraud = FraudTransactions::create([
                    'fraud_code' => 'FRD'. '-' . $inv_date . '-' . $unique_code,
                    'transaction' => $transaction_code,
                    'fraud_type' => null,
                    'fraud_status_info' => '3',
                    'severity_level' => null,
                    'status' => 24 ,
                    'notes' => 'Sedang testing/maintenance system',
                    'it_testing' => 'Y',
                    'it_testing_by' => $user,
                    'created_at' => now()
                ]);

            }else{
                 $fraud = FraudTransactions::create([
                    'fraud_code' => 'FRD'. '-' . $inv_date . '-' . $unique_code,
                    'transaction' => $transaction_code,
                    'fraud_type' => 1,
                    'fraud_status_info' => '3',
                    'severity_level' => 'high',
                    'status' => 22,
                    'created_at' => now()
                ]);

                FraudTransactionTimeline::create([
                    'fraud' => $fraud->fraud_code,
                    'status' => $fraud->status
                ]);
            }
        }



        if($voucher_code){
            VoucherCustomer::where('customer_voucher_code', $voucher_code)->where('customer', $customer)->update([
                'voucher_used' => 'Y',
                'status' => 8,
                'updated_at' => now()
            ]);

          $redeemVoucher =  VoucherRedeem::create([
                'voucher_code' => $codeVoucher,
                'customer_voucher' => $voucher_code,
                'customer' => $customer,
                'redeem_date' => now(),
                'casheer' => $casheer,
                'status' => 17,
                'store' => $store_code,
                'created_at' => now(),
                'created_by' => $casheer
            ]);

            TransactionsVouchers::create([
                'transaction_code' => $main_transaction->transaction_code,
                'voucher_code' => $codeVoucher,
                'customer_voucher' => $redeemVoucher->customer_voucher,
                'status' => 8,
                'voucher_used' => 'Y',
                'used_at' => now(),
                'created_at' => now(),
                'created_by' => $casheer
            ]);
        }
        
    
    // PROSEDUR GET POINT FOR CUSTOMERS WHEN TRANSACTIONS :
    
        $totalPoints = DB::table('transactions_detail as td')
                ->join('products_point as pp', function($join){
                    $join->on('td.product', '=', 'pp.product')
                    ->where('pp.status', 7);
                })
                ->where('td.transaction_code', $main_transaction->transaction_code)
                ->selectRaw('SUM(pp.point * td.quantity_per_product) as total')
                ->value('total') ?? 0;
        
        $check_point = DB::table('point_member_transactions')
                        ->where('status', 7)
                        ->orderBy('created_at', 'DESC')
                        ->first();

        $get_point = $check_point ? $check_point->point : 0;

         DB::table('customer')
        ->where('customer_code', $main_transaction->customer)
        ->increment('point', $totalPoints + $get_point);


        $customerTransaction = DB::table('transactions')
        ->where('customer', $main_transaction->customer)
        ->first();
                

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
            $customer_email = DB::table('customer')->where('customer_code', $customer)->first();
           

            // TOLONG BUATKAN ATAU HAPUS VALIDASI JIKA CUSTOMER MELAKUKAN TRANSAKSI > 1 MAKA DAPAT VOUCHER

            if($customerTransaction){
                    if($getAmount >= $get_voucher->min_transaction) {
                        if(!$checkingQuotaVoucher && $get_voucher){
                            if(!$voucherExpired) {
                                VoucherCustomer::create([
                                    'customer' => $main_transaction->customer,
                                    'voucher' => $get_voucher->voucher_code,
                                    'customer_voucher_code' =>$customerVoucher,
                                    'transaction' => $main_transaction->transaction_code,
                                    'status' => 7,
                                    'voucher_used' => 'N',
                                    'created_by' => $casheer,
                                    'created_at' => now()
                                ]);

                                Mail::to($customer_email->email)->sendNow(new GetVoucherInfoCustomer([
                                    'name' => $customer_email->name,
                                    'voucher_code' => $get_voucher->voucher_code,
                                    'voucher_name' => $get_voucher->voucher_name,
                                    'email' => $customer_email->email
                                ]));
                            }
                        }
                    }
            }
        }

        UserLogActivity::log(
                module: 'Transaction',
                method_type: 'CREATE',
                description: "user create new transaction: {$main_transaction->transaction_code}"      
        );

        Session::forget('cart');
        session()->flash('message_success', 'Transaksi berhasil!');
        return redirect()->route('invoice_detail', $main_transaction->transaction_code);
    }

    public function invoice(Request $request)
    {
        $invoice = DB::table('v_transaction')
        ->where('transaction_code', $request->transaction_code)
            ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)->get();

        if(!$invoice || !$invoice){

            session()->flash('failed_message', 'Invoice tidak ada!');
            return redirect()->back();

        }
        
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
