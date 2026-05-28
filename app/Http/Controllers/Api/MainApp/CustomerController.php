<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use App\Models\ProductFavorite;
use App\Models\RedeemRewardModel;
use App\Models\RewardsModel;
use App\Models\RewardsStoreModel;
use App\Models\TransactionModel;
use App\Models\VoucherCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Facades\File;

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

    public function generate_qr_code(Request $request)
    {
        
        $date = Carbon::now()->format('ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $customer_code =  $request->customer_code;
        $customer_data_qr_code  = [
            'customer_code' => $customer_code,
            'name' => $request->name
        ];
        
        $folderPath = 'qr_customer';
         if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
    
        $fileName = uniqid() . '.svg';
        $qrCodePath = $folderPath . '/' . $fileName;
       
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $svgOutput = $writer->writeString(json_encode($customer_data_qr_code));

        Storage::disk('public')->put($qrCodePath, $svgOutput);

        if($customer_code){
            CustomerModel::where('customer_code', $customer_code)->update([
                'qr_code' => $qrCodePath,
                'updated_at' => now()
            ]);
        }else{
            session()->flash('failed_message', 'Gagal update data!');
            return redirect()->back();
        }

        session()->flash('message_success', 'Berhasil generate kode QR!');
        return redirect()->back();
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

        // FOR INSIGHT TO CHART

        $defaultPeriode =[
            '1-7' => 0,
            '8-14' => 0
        ];

        $transactions = DB::table('v_insight_transaction')
        ->where('customer', $CUSTOMER_LOGIN_SESSION)->get();

        foreach($transactions as $trx){
            $defaultPeriode[$trx->periode] = $trx->total_grand;
        }

        $labels = array_keys($defaultPeriode);
        $data = array_values($defaultPeriode);


        return view('layouts.main_views.customer_views.history-transactions', compact('history_transaction', 'labels', 'data'));
    }

    public function invoice(Request $request)
    {
        $customer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
         $invoice = DB::table('v_transaction')
        ->where('transaction_code', $request->transaction_code)
        ->where('customer_code', $customer)
            ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)
        ->where('customer_code', $customer)->get();
        
        if(!$invoice || !$invoices){
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
        $rewards = DB::table('rewards as r')
            ->join('rewards_store as rs', 'r.rewards_code', '=', 'rs.reward')
            ->select('r.rewards_code','r.rewards_name','r.point','r.images','r.end_date','r.start_date',DB::raw('SUM(rs.stock) as total_stock'), 'r.created_at')
            ->where('rs.status', 7)
            ->groupBy('r.rewards_code','r.rewards_name','r.point','r.images','r.end_date','r.start_date', 'r.created_at')
            ->orderBy('r.created_at', 'DESC')->get();
        return view('layouts.main_views.customer_views.rewards-catalogue', compact('rewards'));
    }
   
    public function customer_voucher(Request $request) {
        $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $vouchers = DB::table('customer_vouchers as cv')
                ->leftJoin('voucher as v', 'cv.voucher', '=', 'v.voucher_code')
                ->where('customer',$CUSTOMER_LOGIN_SESSION)->where('cv.voucher_used', 'N')->orderBy('cv.created_at', 'DESC')->get();

            $vouchers_used = DB::table('customer_vouchers as cv')
                ->leftJoin('voucher as v', 'cv.voucher', '=', 'v.voucher_code')
                ->where('customer',$CUSTOMER_LOGIN_SESSION)->where('cv.voucher_used', 'Y')->orderBy('cv.created_at', 'DESC')->get();

        return view('layouts.main_views.customer_views.customer-voucher', compact('vouchers', 'vouchers_used'));
    }


    public function reward_detail(Request $request)
    {
        $reward = DB::table('v_rewards as r')
            ->select('r.rewards_code','r.rewards_name','r.point','r.images','r.end_date','r.start_date',DB::raw('SUM(r.total_available) as total_stock'), 'r.created_at')
            ->where('r.status_name', 'Active')
            ->where('r.rewards_code', $request->rewards_code)
            ->groupBy('r.rewards_code','r.rewards_name','r.point','r.images','r.end_date','r.start_date', 'r.created_at')
            ->orderBy('r.created_at', 'DESC')->first();

        if(!$reward){
            session()->flash('failed_message', 'Tidak ada Reward!');
            return redirect()->back();
        }

        if($reward->end_date < now()){
            session()->flash('failed_message', 'Rewards sudah tidak ada!');
            return redirect()->back();
        }


         $reward_store = DB::table('rewards_store as rs')
        ->leftJoin('store as s', 'rs.store', '=','s.store_code')->select('s.store_code','s.store_name')
        ->where('rs.reward', $reward->rewards_code)->get();

        return view('layouts.main_views.customer_views.reward-detail', compact('reward', 'reward_store'));
    }

    public function download_pdf_cust(Request $request, $id)
    {
        $transaction_code = $request->transaction_code;
         $invoice = DB::table('v_transaction')
                ->where('transaction_code', $request->transaction_code)
                ->first();
        $invoices = DB::table('v_transaction')->where('transaction_code', $request->transaction_code)->get();
        $pdf = Pdf::loadView('layouts.main_views.customer_views.invoice', compact('invoices', 'invoice'));
        return $pdf->download('invoice_'. $transaction_code . '.pdf');
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
        
        $favorite_exists = DB::table('products_favorite')->where('product', $request->product)->where('variant', $request->variant)
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
                        'product'=> $request->product,
                        'variant' => $request->variant,
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
        ->select('p.product_code', 'pv.variant_code','p.product_name as product', 
        'p.price','p.discount', 'p.price_after_discount', 'pv.variant_price','pv.variant_discount','pv.variant_price_after_discount')
        ->leftJoin('products as p', 'pf.product', '=', 'p.product_code')
        ->leftJoin('product_variant as pv','pf.variant', '=', 'pv.variant_code')
        ->where('pf.customer_code', '=', $customer_code)->orderBy('pf.created_at', 'DESC')->get();



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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
            'phone_number' => 'required'
        ],
        [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Alamat email harus diisi',
            'address.required' => 'Masukan Alamat anda',
            'birth_date.required' => 'Tanggal lahir harus diisi',
            'phone_number.required' => 'No.Handphone harus diisi'
        ]);

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
        $favId = ProductFavorite::where('product', $request->product)->where('variant', $request->variant)
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
            ->leftJoin('rewards_store as rs','rr.reward', '=', 'rs.reward_store_code')
            ->leftJoin('rewards as r', 'rs.reward', '=', 'r.rewards_code')
            ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
            ->join('store as st', 'rs.store', '=', 'st.store_code')
            ->where('customer', $customer)->where('rr.status', 11)->orderBy('redeem_date', 'DESC')->get();

            $unclaimed_rewards = DB::table('redeem_reward as rr')
            ->leftJoin('rewards_store as rs','rr.reward', '=', 'rs.reward_store_code')
            ->leftJoin('rewards as r', 'rs.reward', '=', 'r.rewards_code')
            ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
            ->join('store as st', 'rs.store', '=', 'st.store_code')
            ->where('customer', $customer)->where('rr.status', 12)->orderBy('redeem_date', 'DESC')->get();
     
        return view('layouts.main_views.customer_views.rewards', compact('rewards', 'unclaimed_rewards'));
    }



    public function redeem_reward(Request $request)
    {
        $request->validate([
            'pickup_schedule' => 'required',
            'store' => 'required'
        ],
        [
            'pickup_schedule.required' => 'Jadwal pengambilan harus diisi',
            'store.required' => 'Pilih dahulu store'
        ]);

        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
    
        $reward_point = $request->point;
        $reward_code = $request->reward_code;
        $reward_code_store = $request->reward;
        $customer_point = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->point;
        $result_point = $customer_point - $reward_point;
        
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $redeem_code = 'REDEEM' . $unique_code;

        $reward_exists = DB::table('v_rewards')
            ->where('reward_store_code', $reward_code_store)->first();
       
        if($reward_exists->total_available == null || $reward_exists->total_available == 0)
        {
           session()->flash('failed_message', 'Maaf, Kuota Reward ini sudah habis!');
            return redirect()->back(); 
        }

        if($reward_exists->end_date < now())
        {
           session()->flash('failed_message', 'Maaf, Masa berlaku Reward ini sudah habis!');
            return redirect()->back(); 
        }

        if($reward_point > $customer_point){
            session()->flash('failed_message', 'Point anda tidak mencukupi untuk reward ini');
            return redirect()->back();
        }else{
           RedeemRewardModel::create([
                'redeem_code' => $redeem_code,
                'reward' => $reward_code_store,
                'customer' => $customer_code,
                'status' => 12,
                'pickup_schedule' => $request->pickup_schedule,
                'redeem_date' => now(),
                'created_at' => now()
            ]);

            
            // RewardsStoreModel::where('reward_store_code', $reward_code_store)->decrement('stock', 1);
        
            CustomerModel::where('customer_code', $customer_code)->update([
                'point' => $result_point
            ]);

            session()->flash('message_success', 'Berhasil Redeem Reward!');
            return redirect()->route('rewards-history');
        }


    }

    public function get_stock(Request $request)
    {
        $data = DB::table('v_rewards')
        ->select('total_available', 'reward_store_code')
        ->where('rewards_code', $request->rewards_code)
        ->where('store_code', $request->store)->first();

        return response()->json([
            'data' => $data,
            'message' => 'Data Rewards Store'

        ]);
    }

    public function get_insight(Request $rq)
    {
        $defaultPeriode =[
            '1-7' => 0,
            '8-14' => 0,
            '15-akhir' => 0
        ];

        $customer =  app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $data_trasactions = DB::table('v_insight_transaction')
        ->where('customer', $customer)->get();

        foreach($data_transactions as $transaction){
            $defaultPeriode[$transaction->periode] = $transaction->total_grand;
        }

        $labels = array_keys($defaultPeriode);
        $data = array_values($defaultPeriode);

        return view('layouts.main_views.customer_views.history-transactions', compact('data', 'labels'));

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
