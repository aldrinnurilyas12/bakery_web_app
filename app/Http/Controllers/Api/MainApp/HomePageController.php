<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
    public function create()
    {
        //
    }

    public function homepage(Request $request) :View 
    {
        $activeCategory = $request->query('category', null);
        $productsQuery = DB::table('v_daily_products')->where('status', 'Ready');
        $productsPromo = DB::table('v_daily_products')
            ->where(function ($query) {
                $query->where('discount', '<>', 0)
                    ->orWhere('variant_discount', '<>', 0);
            })
            ->where('status', 'Ready')
            ->where('store_id', 1);

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
        
        return view('layouts.main_views.home.home', compact(
            'products',
            'products_promo',
            'category_products',
            'promos',
            'store',
            'activeCategory'
        ));
    }

    public function filter_store(Request $request)
    {
        $store_id = $request->store;
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
            'category_products',
            'promos',
            'store',
            'activeCategory'
        ));
    }

    public function product_detail(Request $request)
    {

       $code = $request->route('code');

        // coba sebagai product_code dulu
        $product = DB::table('v_daily_products')
            ->where('status', 'Ready')
            ->where(function ($query) use ($code) {
                $query->where('product_code', $code)
                    ->orWhere('variant_code', $code);
            })
            ->first();

        // cek kalau tidak ditemukan
        if (!$product) {
            session()->flash('failed_message', 'Product tidak ditemukan');
            return redirect()->back();
        }
       

        return view('layouts.main_views.products.product_detail', compact('product'));
    }

    public function promo_campaign()
    {
        $promo_campaign = DB::table('promo_campaign as pc')
        ->leftJoin('promo_campaign_images as pi', 'pc.promo_code', '=', 'pi.promo_code')->where('status', 7)->get();
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
             $product = DB::table('v_daily_products')->select('product_code', 'product', 'price', 'price_after_discount', 'discount', 'variant_price', 'variant_code')->distinct()->where('product', 'like', '%' . $search . '%')
        ->orWhere('category','like', '%' . $search . '%')->paginate();
        } else {
             $product = DB::table('v_daily_products')->select('product_code', 'product', 'price', 'price_after_discount', 'discount', 'variant_price', 'variant_code')->distinct()->where('product', 'like', '%' . $search . '%')
        ->orWhere('category','like', '%' . $search . '%')->paginate();
        }

         return view('layouts.main_views.customer_views.product-search', compact('product'));
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
