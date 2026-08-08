<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use App\Models\CustomerRecoveryAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerLogActivities;

class HomePageController extends Controller
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
    public function alert_info_layout()
    {
        return view('layouts.main_views.home.maintenance_info');
    }

    public function homepage(Request $request) :View 
    {
        $auth_check = auth()->guard('customer')->user();

        if($auth_check){
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        }
        $activeCategory = $request->query('category', null);
        $productsQuery = DB::table('v_daily_products')
        ->whereNotNull('price')
        ->where('status', 'Ready');

        $productsPromo = DB::table('v_daily_products')
            ->where(function ($query) {
                $query->where('discount', '<>', 0)
                    ->orWhere('variant_discount', '<>', 0);
            })
            ->whereNotNull('price')
            ->where('status', 'Ready')
            ->where('store_id', 1);


        $promo_bundling = DB::table('v_promo_bundling')->where('status', 7)->get();

      
        $all_product = DB::table('promo_bundling_detail as pbd')
                    ->select('pbd.quantity', 'pbd.bundling_code', 'p.product_name', 'p.product_code', 'vdp.stock_available')
                    ->leftJoin('products as p', 'pbd.product', '=', 'p.product_code')
                    ->leftJoin('v_daily_products as vdp', 'pbd.product', '=', 'vdp.product_code')
                    ->where('vdp.store_code', 'STb68d9a')
                    ->get();
        
        

        // Filter berdasarkan kategori jika ada
        if ($activeCategory) {
            // pastikan kolom 'category' ada di v_daily_products
            $productsQuery->where('vp.category', $activeCategory);
            $productsPromo->where('vp.category', $activeCategory);
        }

        $products_promo= $productsPromo->where('store_id', 1)->get();
        $products = $productsQuery->where('store_id', 1)->get();

    
        $category_products = DB::table('product_category as c')
            ->select('c.id','c.icon', DB::raw("REPLACE(c.category_name, ' ', '_') as category_name",))
            ->join('products as p', 'c.id', '=', 'p.category_id')
            ->leftJoin('distribution_products_detail as ppd', 'p.product_code', '=', 'ppd.product')
            ->join('products_daily as pd', 'ppd.distribution_store_code', '=', 'pd.distribution_store')
            ->groupBy('c.id', 'c.category_name')
            ->get();

        $promos = DB::table('v_promos')
            ->where('status', 'Active')
            ->limit(3)
            ->get();

        $store =  DB::table('store')->get();

        $notif_customer = 0;

        if($auth_check && $customer_code){
            $notif_customer = DB::table('customer_notifications')
            ->where('customer', $customer_code)
            ->where('is_read', 'N')->count();
        }

        
        return view('layouts.main_views.home.home', compact(
            'products',
            'products_promo',
            'category_products',
            'promos',
            'store',
            'activeCategory',
            'promo_bundling',
            'all_product',
            'notif_customer'
        ));
    }

    public function filter_store(Request $request)
    {
        $store_id = $request->store;
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $activeCategory = $request->query('category', null);
        $productsQuery = DB::table('v_daily_products')->where('status', 'Ready');
        $productsPromo = DB::table('v_daily_products')
            ->where(function ($query) {
                $query->where('discount', '<>', 0)
                    ->orWhere('variant_discount', '<>', 0);
            })
            ->where('status', 'Ready');

        // Filter berdasarkan kategori jika ada
        if ($activeCategory) {
            // pastikan kolom 'category' ada di v_daily_products
            $productsQuery->where('vp.category', $activeCategory);
            $productsPromo->where('vp.category', $activeCategory);
        }

        $store =  DB::table('store')->get();

         $promo_bundling = DB::table('v_promo_bundling')->where('status', 7)->get();

         $all_product = DB::table('promo_bundling_detail as pbd')
                    ->select('pbd.quantity', 'pbd.bundling_code', 'p.product_name', 'p.product_code', 'vdp.stock_available')
                    ->leftJoin('products as p', 'pbd.product', '=', 'p.product_code')
                    ->leftJoin('v_daily_products as vdp', 'pbd.product', '=', 'vdp.product_code')
                    ->where('vdp.store_code', $store_id)
                    ->get();

        $category_products = DB::table('product_category as c')
            ->select('c.id','c.icon', DB::raw("REPLACE(c.category_name, ' ', '_') as category_name",))
            ->join('products as p', 'c.id', '=', 'p.category_id')
            ->join('distribution_products_detail as pp', 'p.product_code', '=', 'pp.product')
            ->join('products_daily as pd', 'pp.distribution_store_code', '=', 'pd.distribution_store')
            ->groupBy('c.id', 'c.category_name')
            ->get();

        $promos = DB::table('v_promos')
            ->where('status', 'Active')
            ->limit(3)
            ->get();

        if($customer_code){
            $notif_customer = DB::table('customer_notifications')
            ->where('customer', $customer_code)
            ->where('is_read', 'N')->count();
        }



        if($store_id){
            $products = $productsQuery->where('store_code', $store_id)->get();
            $products_promo= $productsPromo->where('store_code', $store_id)->get();
            if($products->isEmpty()){
                 session()->flash('product_not_found', 'Tidak ada Produk di Store ini');
                 return redirect()->back();
            }
        }else{
            $products = $productsQuery->get();
            $products_promo= $productsPromo->get();
        }

   
        
        return view('layouts.main_views.home.home', compact(
            'products',
            'products_promo',
            'promo_bundling',
            'category_products',
            'all_product',
            'promos',
            'store',
            'activeCategory',
            'notif_customer'
        ));
    }

    public function product_detail(Request $request)
    {

        $code = $request->route('slug');
        $customer = false;

        if(auth()->guard('customer')->user()){
            $customer = auth()->guard('customer')->user()->customer_code;
        }
        // coba sebagai product_code dulu
        $product = DB::table('v_daily_products')
            ->where('status', 'Ready')
            ->where(function ($query) use ($code) {
                $query->where('slug', $code);
            })
            ->first();

        $review = DB::table('product_reviews as pr')
            ->select('pr.review', 'pr.rating', 'pr.review_date','pr.hidden_name', 'c.name', 'vdp.slug', 'pr.created_at')
            ->join('products as p', 'pr.product', '=', 'p.product_code')
            ->leftjoin('transactions as t', 'pr.transaction', '=', 't.transaction_code')
            ->leftjoin('customer as c','t.customer', '=', 'c.customer_code')
            ->join('v_daily_products as vdp', 'p.product_code', '=', 'vdp.product_code')
            ->where('vdp.slug', $code)
            ->distinct()
            ->orderBy('pr.created_at', 'DESC')->get();

         $view_product_exists = DB::table('customers_log_activities')
                ->where('product', $product->product_code)
                ->where('variant', $product->variant_code)
                ->where('customer', $customer)
                ->where('category', 'View Product')->first();

        $total_product_view = DB::table('customers_log_activities')
                ->where('category', 'View Product')
                ->where('product', $product->product_code)->count();
    

        if(auth()->guard('customer')->user()){
            if(!$view_product_exists){
                CustomerLogActivities::log(
                    customer: $customer,
                    product: $product->product_code,
                    variant: $product->variant_code,
                    category: 'View Product',
                    description: "Customer View Product"  
                );
            }
        }
        

        // cek kalau tidak ditemukan
        if (!$product) {
            session()->flash('failed_message', 'Product tidak ditemukan');
            return redirect()->back();
        }
       

        return view('layouts.main_views.products.product_detail', compact('product', 'review', 'total_product_view'));
    }

    public function promo_detail(Request $request)
    {
        $bundling = $request->bundling_code;
        
        $promo_bundling = DB::table('v_promo_bundling')->where('bundling_code', $bundling)->first();

        $all_product = DB::table('promo_bundling_detail as pbd')
                    ->select('pbd.quantity', 'pbd.bundling_code', 'p.product_name', 'p.product_code', 'vdp.stock_available')
                    ->leftJoin('products as p', 'pbd.product', '=', 'p.product_code')
                    ->leftJoin('v_daily_products as vdp', 'pbd.product', '=', 'vdp.product_code')
                    ->where('vdp.store_code', 'STb68d9a')
                    ->get();
        
        
        // cek kalau tidak ditemukan
        if (!$promo_bundling) {
            session()->flash('failed_message', 'Promo tidak ditemukan');
            return redirect()->back();
        }
       

        return view('layouts.main_views.products.promo_detail', compact('promo_bundling', 'all_product'));
    }


    public function promo_campaign()
    {
        $promo_campaign = DB::table('promo_campaign as pc')
        ->leftJoin('promo_campaign_images as pi', 'pc.promo_code', '=', 'pi.promo_code')
        ->where('status', 7)->get();
         return view('layouts.main_views.customer_views.promo-campaign', compact('promo_campaign'));
    }

    public function promo_campaign_detail(Request $request){
        $promo_campaign = DB::table('promo_campaign as pc')
        ->select('pc.promo_code', 'pc.promo_name', 'pc.min_transaction', 'pc.quota', 'pc.description as promo_description', 'pi.images', 'pc.start_date', 'pc.end_date')
        ->leftJoin('promo_campaign_images as pi', 'pc.promo_code', '=', 'pi.promo_code')
        ->where('pc.status', 7)
        ->where('pc.promo_code', $request->promo_code)->first();

        if(!$promo_campaign){
            session()->flash('failed_message', 'Tidak ada Promo!');
            return redirect()->back();
        }

        
         return view('layouts.main_views.customer_views.promo-detail', compact('promo_campaign'));
    }

    public function product_search(Request $request){

        $search = $request->input('search');
        $product = DB::table('v_daily_products')->select('product_code', 'product', 'price', 'price_after_discount', 'discount', 'variant_price', 'variant_code')->distinct()->where('product', 'like', '%' . $search . '%')
        ->orWhere('category','like', '%' . $search . '%')->paginate();
        if ($search) {
             $product = DB::table('v_daily_products')->select('product_code', 'product', 'price', 'price_after_discount', 'discount', 'variant_price', 'variant_code', 'variant_discount', 'slug')->distinct()->where('product', 'like', '%' . $search . '%')
        ->orWhere('category','like', '%' . $search . '%')->paginate();
        } else {
             $product = DB::table('v_daily_products')->select('product_code', 'product', 'price', 'price_after_discount', 'discount', 'variant_price', 'variant_code', 'variant_discount', 'slug')->distinct()->where('product', 'like', '%' . $search . '%')
        ->orWhere('category','like', '%' . $search . '%')->paginate();
        }

         return view('layouts.main_views.customer_views.product-search', compact('product'));
    }

    public function nonactive_account_information(String $email)
    {

        $info_account = DB::table('customer')->where('email', $email)->select('deleted_at')->first();
        $checkAccountActive = DB::table('customer')->where('email', $email)->select('status')->first();

       

        if(!$info_account) {
            session()->flash('failed_message', 'Akun tidak ditemukan atau akun mungkin masih aktif!');
            return redirect()->route('login_app');
        }
        $inactive_date = $info_account->deleted_at;

        if($inactive_date == null){
            session()->flash('failed_message', 'Akun tidak ditemukan atau akun mungkin masih aktif!');
            return redirect()->route('login_app');
        }

         if($checkAccountActive->status == 7){
            return redirect()->route('login_app');
        }


        return view('layouts.main_views.customer_views.account_recovery.recovery_account_main', compact('inactive_date'));
    }


    public function outlet_list(){

        $store = DB::table('store')->where('status', 7)->get();
        return view('layouts.main_views.customer_views.outlet_list', compact('store'));
    }

    public function privacy_policy(){
        return view('layouts.main_views.customer_views.privacy_policy');
    }


    public function recovery_account_request(Request $rq){
        return view('layouts.main_views.customer_views.account_recovery.request');
    }

    public function recovery_account_verification(Request $request){

        $token_link = $request->token_link_recovery_account;
        $customer_data = DB::table('customer as c')
                    ->leftjoin('customer_recovery_account as cra', 'c.customer_code', '=', 'cra.customer')
                    ->where('cra.status', 8)
                    ->orderBy('cra.expired_at', 'DESC')
                    ->first();


        $checkTokenExpired = DB::table('customer_recovery_account')
                ->where('token_link', $token_link)
                ->where('status', 27)
                ->whereNull('used_at')->first();

        $checkTokenUsed = DB::table('customer_recovery_account')
                ->where('token_link', $token_link)
                ->where('status', 7)
                ->whereNotNull('used_at')->first();

        if($checkTokenExpired){
             session()->flash('failed_message', 'Token sudah expired!');
            return redirect()->route('login_app');
        }

        if($checkTokenUsed){
             session()->flash('message_success', 'Token sudah terpakai!');
            return redirect()->route('login_app');
        }

        return view('layouts.main_views.customer_views.account_recovery.request_verification_recovery_account', compact('token_link', 'customer_data'));
    }


    public function recovery_account_verification_save(Request $request){

        $token = $request->token_link;
        $email = $request->email;

        // dd($token);

        $checkTokenUsed = DB::table('customer_recovery_account')
            ->where('token_link', $token)
            ->where('status', 7)
            ->whereNotNull('used_at')->first();


        $checkTokenExpired = DB::table('customer_recovery_account')
                ->where('token_link', $token)
                ->where('status', 27)
                ->whereNull('used_at')->first();
        
        $checkTokenExists = DB::table('customer_recovery_account')
            ->where('token_link', $token)->first();

        
        if(!$checkTokenExists){
            session()->flash('failed_message', 'Token tidak ada!');
            return redirect()->route('login_app');
        }

        if($checkTokenUsed){
            session()->flash('failed_message', 'Token anda sudah terpakai!');
            return redirect()->route('login_app');
        }


        if($checkTokenExpired){
            session()->flash('failed_message', 'Token anda sudah Expired, segera Kirim Kembali pemulihan akun!');
            return redirect()->route('login_app');
        }



       $token = DB::table('customer as c')
            ->join('customer_recovery_account as cra', 'c.customer_code', '=', 'cra.customer')
            ->where('cra.token_link', $token)
            ->where('cra.expired_at', '>', now())
            ->where('c.email', $email)
            ->update([
                'c.status' => 7,
                'c.reactivated_at' => now(),
                'cra.status' => 7,
                'cra.used_at' => now()
            ]);

        

         if(!$token){
            session()->flash('failed_message', 'Token anda sudah Expired, segera Kirim Kembali pemulihan akun!');
            return redirect()->route('login_app');
        }

        session()->flash('message_success', 'Akun anda berhasil dipulihkan Silahkan Login Kembali!');
        return redirect()->route('login_app');
        
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
