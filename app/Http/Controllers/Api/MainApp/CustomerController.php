<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use App\Models\ProductFavorite;
use App\Models\RedeemRewardModel;
use App\Models\RewardsModel;
use App\Models\TransactionModel;
use App\Models\VoucherCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomerController extends Controller
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

    public function profile(Request $request) {
        $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $customer = DB::table('v_customers')->where('customer_code', $CUSTOMER_LOGIN_SESSION)->first();
       $birth_date = Carbon::parse($customer->birth_date);

        return view('layouts.main_views.customer_views.profile', compact('customer', 'birth_date'));
    }

    public function menu() : View 
    {
        return view('layouts.main_views.customer_views.profile-menu');
    }


       public function history_transaction()
    {
        $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $history_transaction = DB::table('v_main_transactions')
        ->where('customer',$CUSTOMER_LOGIN_SESSION)
        ->orderBy('transaction_date', 'DESC')
        ->get();

        if(!$CUSTOMER_LOGIN_SESSION && !$history_transaction) {
            session()->flash('failed_message', 'Tidak ada data transaksi!');
            return redirect()->back();
        }

        return view('layouts.main_views.customer_views.history-transactions', compact('history_transaction'));
    }

    public function invoice(Request $request)
    {
         $invoice = DB::table('v_transaction')
        ->where('transaction_code', $request->transaction_code)
            ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)->get();
        
        if(!$invoice){
            session()->flash('failed_message', 'Tidak ada Invoice!');
            return redirect()->back();
        }


        return view('layouts.main_views.customer_views.invoice', compact('invoice', 'invoices'));
    }

    public function rewards_history(Request $request)
    {

    }


    public function rewards_catalogue(Request $request)
    {
        // $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        // $customer = DB::table('v_customers')->where('customer_code', $CUSTOMER_LOGIN_SESSION)->first();
        $rewards = DB::table('rewards')->where('status','7')->orderBy('created_at', 'DESC')->get();
        return view('layouts.main_views.customer_views.rewards-catalogue', compact('rewards'));
    }
   
    public function customer_voucher(Request $request) {
        $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $vouchers = DB::table('customer_vouchers as cv')
                ->leftJoin('voucher as v', 'cv.voucher', '=', 'v.voucher_code')
                ->where('customer',$CUSTOMER_LOGIN_SESSION)->get();
        return view('layouts.main_views.customer_views.customer-voucher', compact('vouchers'));
    }


    public function reward_detail(Request $request)
    {
        $reward = DB::table('rewards')->where('status','7')->where('rewards_code', $request->rewards_code)->first();

        if(!$reward){
             session()->flash('failed_message', 'Tidak ada Reward!');
            return redirect()->back();
        }

        return view('layouts.main_views.customer_views.reward-detail', compact('reward'));
    }


    public function change_password_layout() 
    {
        return view('layouts.main_views.customer_views.change-password');
    }

    public function update_customer_password(Request $request) 
    {
        $request->validate([
            'email' => 'required'
        ],
        [
            'email.required' => 'Alamat email harus diisi'
        ]
        );

        $customer_email = $request->email;
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $matching_customer_email = DB::table('customer')->where('customer_code', $customer_code)->where('email', $customer_email)->first();

        if(!$matching_customer_email){
            session()->flash('failed_message', 'Alamat email anda tidak sesuai!');
            return redirect()->back();
        }

        if($matching_customer_email)
        { 
            CustomerModel::where('customer_code', $customer_code)->update([
                'password' => Hash::make($request->password),
                'updated_at' => now()
            ]);
            session()->flash('message_success', 'Berhasil merubah kata sandi!');
            return redirect()->back();
        }
    }

    public function nonactive_account(Request $request){
        $auth = auth()->guard('customer')->user();
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        if($auth){
            CustomerModel::where('customer_code', $customer_code)->update([
                'status' => 8,
                'deleted_at' => now()
            ]);
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            session()->flash('message_success', 'Akun anda berhasil dihapus!');
            return redirect()->route('login_app');
        }
    }

    public function favorite_product(Request $request)
    {
        $auth_check = auth()->guard('customer')->user();
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        
        $favorite_exists = DB::table('products_favorite')->where('product_daily', $request->daily_code)
                ->where('customer_code', $customer_code)->first();

            if(!auth()->guard('customer')->check()) {
                    session()->flash('failed_message', 'Anda harus login untuk sukai produk ini');
                    return redirect()->route('home');
            }    

            if($auth_check){

                    if($favorite_exists){
                        session()->flash('message_success', 'Produk ini sudah anda sukai!');
                        return redirect()->back();
                    } 
                    ProductFavorite::create([
                        'product_daily'=> $request->daily_code,
                        'customer_code' => $customer_code,
                        'favorite' => 1,
                        'created_at' => now(),
                        'updated_at' => null
                    ]);
                    session()->flash('message_success', 'Berhasil menambahkan produk ke favorite!');
                    return redirect()->back();
            }
        
    }

    public function favorite_list(Request $request) 
    {
         $auth_check = auth()->guard('customer')->user();
         $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        if(!auth()->guard('customer')->check()) {
                    session()->flash('failed_message', 'Anda harus login untuk sukai produk ini');
                    return redirect()->route('home');
        }  

        
        // $product_favorite = DB::table('products_favorite as pf')
        // ->select('pf.id as favorite_id', 'vp.product_code', 'vp.variant_code','vp.product', 
        // 'vp.price','vp.discount', 'vp.price_after_discount', 'vp.variant_price','vp.variant_discount','vp.variant_price_after_discount')
        // ->leftJoin('v_daily_products as vp', function($join){
        //     $join->on('pf.product_code', '=', 'vp.product_code')
        //     ->where(function($q){
        //         $q->whereColumn('pf.variant_code', 'vp.variant_code')
        //         ->orWhereNull('pf.variant_code');
        //     });

        $product_favorite = DB::table('products_favorite as pf')
        ->select('pf.product_daily', 'vp.product_code', 'vp.variant_code','vp.product', 
        'vp.price','vp.discount', 'vp.price_after_discount', 'vp.variant_price','vp.variant_discount','vp.variant_price_after_discount')
        ->leftJoin('v_daily_products as vp', 'pf.product_daily', '=', 'vp.daily_code')->where('pf.customer_code', '=', $customer_code)->orderBy('pf.created_at', 'DESC')->get();



        // $variant_favorite = 
        return view('layouts.main_views.customer_views.favorite-customer', compact('product_favorite'));
    }

    public function notification(Request $request) {

        $auth_check = auth()->guard('customer')->user();
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
    
        $notifications = DB::table('v_customer_notification')->where('customer', $customer_code)->get();
        $user_register = DB::table('customer')->where('customer_code', $customer_code)->exists();
        return view('layouts.main_views.customer_views.notifications', compact('notifications', 'user_register'));
    }

    public function update_customer(Request $request)
    {
        $updated_at = now();
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
    
        CustomerModel::where('customer_code', $customer_code)->update([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'phone_number' => $request->phone_number,
            'updated_at' =>$updated_at
        ]);

        session()->flash('message_success', 'Berhasil perbarui data anda');
        return redirect()->back();

    }

    public function remove_favorite(Request $request, $id)
    {

        $customer_code =app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $favId = ProductFavorite::where('product_daily', $request->product_daily)
        ->where('customer_code', $customer_code)->first();

        if($favId){
            $favId->delete();
            session()->flash('message_success', 'Menghapus produk dari favorit anda.');
            return redirect()->back();
        }
    }

     public function rewards_customer_history(Request $request) {
        $customer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $rewards = DB::table('redeem_reward as rr')
            ->leftJoin('rewards as r', 'rr.reward', '=', 'r.rewards_code')
            ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
            ->where('customer', $customer)->orderBy('redeem_date', 'DESC')->get();
        return view('layouts.main_views.customer_views.rewards', compact('rewards'));
    }



    public function redeem_reward(Request $request)
    {
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
    
        $reward_point = $request->point;
        $reward_code = $request->reward_code;
        $customer_point = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->point;

        $result_point = $customer_point - $reward_point;
        
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $redeem_code = 'REDEEM' . $unique_code;

        $reward_exists = DB::table('rewards')->where('rewards_code', $reward_code)->first();
        if($reward_exists->quota == null || $reward_exists->quota == 0)
        {
           session()->flash('failed_message', 'Maaf, Kuota Reward ini sudah habis!');
            return redirect()->back(); 
        }

        if($reward_point > $customer_point){
            session()->flash('failed_message', 'Point anda tidak mencukupi untuk reward ini');
            return redirect()->back();
        }else{
           RedeemRewardModel::create([
                'redeem_code' => $redeem_code,
                'reward' => $reward_code,
                'customer' => $customer_code,
                'status' => 12,
                'redeem_date' => now(),
                'created_at' => now()
            ]);

            RewardsModel::where('rewards_code', $reward_code)->decrement('quota', 1);
        
            CustomerModel::where('customer_code', $customer_code)->update([
                'point' => $result_point
            ]);

            session()->flash('message_success', 'Berhasil Redeem Point!');
            return redirect()->back();
        }


    }


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
