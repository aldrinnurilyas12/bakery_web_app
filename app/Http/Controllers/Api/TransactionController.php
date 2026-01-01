<?php

namespace App\Http\Controllers\Api;

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

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $show_transaction = DB::table('v_transaction')
        ->select('transaction_code', DB::raw('GROUP_CONCAT(product_name) as product_name'),'customer_code','name','email', 'casheer' , 'quantity','grand_total', 'transaction_date')
        ->groupBy('transaction_code', 'customer_code','name','email','casheer','quantity','grand_total', 'transaction_date')
        ->orderBy('transaction_date', 'DESC')->get();

        $show_transaction_array_data =  $show_transaction->map(function ($transaction) {
            $product_names = explode(',', $transaction->product_name);
            if (count($product_names) > 2) {
                $transaction->product_name = array_slice($product_names, 0, 2);
                $transaction->product_name[] = 'dan lainnya';
            } else {
                $transaction->product_name = $product_names;
            }

            return $transaction;
        });
        return view('layouts.main_pages.transactions.transaction', compact('show_transaction', 'show_transaction_array_data'));
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
    public function transaction_create_layout(Request $request): View
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
         $category_data = DB::table('product_category as c')->select(DB::raw("REPLACE(c.category_name, ' ', '_') as 'category_name'"))
                ->join('products as p', 'c.id', '=', 'p.category_id')
                ->join('products_daily as pd', 'p.product_code', '=', 'pd.product_code')
                ->groupBy('c.category_name')->get();
        $all_products =  DB::table('v_daily_products')->where('status', 'Ready')->paginate(15);
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
        'product.*' => 'required|exists:products,product_code',
        'variant' => 'nullable|array',
        'variant.*' => 'nullable|exists:product_variant,variant_code',
        'quantity_per_product' => 'required|array',
        'quantity_per_product.*' => 'required|integer|min:1',
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $inv_date = Carbon::now()->format('Ymd');
        $transaction_code = 'INV' . $inv_date . $unique_code;

        $productCode = $request->product;
        $variantCode = $request->variant ?? [];
        $qtyProducts = $request->quantity_per_product;
        $casheer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
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
            'quantity' => $request->quantity,
            'total_amount' => $request->total_amount,
            'grand_total' => $request->grand_total,
            'casheer' => $casheer,
            'customer' => $request->customer,
            'status' => 5,
            'payment_type' => $request->payment_type,
            'payment_changes' => $request->payment_changes,
            'transaction_date' => now(),
            'created_by' => $casheer,
            'created_at' => now()
        ]);

     
        foreach ($productCode as $index => $productId) {
                $variantCodeValue = $variantCode[$index] ?? null;
               TransactionDetail::create([
                    'transaction_code' => $main_transaction->transaction_code,
                    'product' => $productId,
                    'variant' => $variantCodeValue,
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
    $transactionDetail = DB::table('transactions_detail')
        ->where('transaction_code', $main_transaction->transaction_code)
        ->get();

    $productPoints = DB::table('products_daily')
        ->whereNotNull('product_code')
        ->pluck('point', 'product_code');

    $variantProductPoints = DB::table('products_daily')
        ->whereNotNull('variant_code')
        ->pluck('point', 'variant_code');

    $customerTransaction = DB::table('transactions')
        ->where('customer', $main_transaction->customer)
        ->first();

    $customerPoint = DB::table('customer')
        ->where('customer_code', $main_transaction->customer)
        ->value('point') ?? 0;
    
    $totalPoints = 0;

    if ($customerTransaction) {

        foreach($transactionDetail as $detail) {

             if (!empty($detail->variant) && isset($variantProductPoints[$detail->variant])) {
                $totalPoints += $variantProductPoints[$detail->variant];
                continue;
            }

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
                                        ->orWhere('phone_number','LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%")->limit(3)->get();

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
