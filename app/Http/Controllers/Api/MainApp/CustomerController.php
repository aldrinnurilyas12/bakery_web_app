<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use App\Models\CustomerFeedback;
use App\Models\CustomerFeedbackDetail;
use App\Models\CustomerModel;
use App\Models\ProductFavorite;
use App\Models\ProductReviews;
use App\Models\RedeemRewardModel;
use App\Models\RewardsModel;
use App\Models\RewardsStoreModel;
use App\Models\TransactionModel;
use App\Models\VoucherCustomer;
use App\Services\CustomerNotification;
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
        
        $date = Carbon::now()->format('Ymd');
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



        $products_detail = DB::table('transactions_detail as td')
            ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
            ->join('product_images as pi', 'p.product_code', '=', 'pi.product_code')->get();
            

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


        return view('layouts.main_views.customer_views.history-transactions', compact('history_transaction', 'labels', 'data', 'products_detail'));
    }



    public function filter_transaction(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $CUSTOMER_LOGIN_SESSION = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $history_transaction = DB::table('v_main_transactions')
        ->where('customer',$CUSTOMER_LOGIN_SESSION)
        ->whereDate('transaction_date', '>=', $start_date)
        ->whereDate('transaction_date', '<=', $end_date)
        ->orderBy('transaction_date', 'DESC')
        ->get();

        $products_detail = DB::table('transactions_detail as td')
            ->leftJoin('products as p', 'td.product', '=', 'p.product_code')
            ->join('product_images as pi', 'p.product_code', '=', 'pi.product_code')->get();
            

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


        return view('layouts.main_views.customer_views.history-transactions', compact('history_transaction', 'labels', 'data', 'products_detail'));
    }

    public function invoice(Request $request)
    {
        $customer = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
       
         $invoice = DB::table('v_main_transactions')
        ->where('transaction_code', $request->transaction_code)
         ->where('customer', $customer)
        ->first();
        

       $hasNonBundling = DB::table('v_transaction')
        ->where('transaction_code', $request->transaction_code)
         ->where('customer_code', $customer)
        ->whereNull('promo_bundling')
        ->exists();

        if ($hasNonBundling) {
            // Jika ada non bundling, tampilkan non bundling
            $invoices = DB::table('v_transaction')
                ->where('transaction_code', $request->transaction_code)
                 ->where('customer_code', $customer)
                ->whereNull('promo_bundling')
                ->get();
        } else {
            // Berarti semua item adalah bundling
            $invoices = DB::table('v_transaction')
                ->where('transaction_code', $request->transaction_code)
                 ->where('customer_code', $customer)
                ->whereNotNull('promo_bundling')
                ->get();
        }

        
        if(!$invoice || !$invoices){
            return redirect()->back();
        }



        return view('layouts.main_views.customer_views.invoice_pdf', compact('invoice', 'invoices'));
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
            ->select('r.rewards_code','r.rewards_name','r.point', 'r.status_name','r.images','r.end_date','r.start_date',DB::raw('SUM(r.stock) as total_stock'), 'r.stock', 'r.created_at')
            ->where('r.rewards_code', $request->rewards_code)
            ->groupBy('r.rewards_code','r.rewards_name','r.point', 'r.status_name','r.images', 'r.stock','r.end_date','r.start_date', 'r.created_at')
            ->orderBy('r.created_at', 'DESC')->first();
       

        if(!$reward){
            session()->flash('failed_message', 'Reward ini tidak ditemukan!');
            return redirect()->back();
        }

        if (Carbon::parse($reward->end_date)->lt(now()) && $reward->status_name == 'Inactive') {
            session()->flash('failed_message', 'Masa berlaku reward ini sudah berakhir!');
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
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ],
        [
            'email.required' => 'Alamat email harus diisi',
            'password.required' => 'Kata sandi harus diisi',
            'confirm_password.required' => 'Konfirmasi kata sandi harus diisi'
        ]
        );

        $customer_email = $request->email;
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $matching_customer_email = DB::table('customer')->where('customer_code', $customer_code)->where('email', $customer_email)->first();

        if(!$matching_customer_email){
            session()->flash('failed_message', 'Alamat email anda tidak sesuai!');
            return redirect()->back()->withInput();
        }

        if($confirm_password != $password){
            session()->flash('failed_message', 'Konfirmasi Kata sandi tidak sesuai!');
            return redirect()->back()->withInput();
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

        $product_favorite = DB::table('products_favorite as pf')
        ->select('p.product_code', 'pv.variant_code','p.product_name as product', 
        'p.price','p.discount', 'p.price_after_discount', 'pv.variant_price','pv.variant_discount','pv.variant_price_after_discount',
          DB::raw("
            LOWER(
                TRIM(BOTH '_' FROM
                    REGEXP_REPLACE(
                        p.product_name,
                        '[^A-Za-z0-9]+',
                        '_'
                    )
                )
            ) AS slug
        "))
        ->leftJoin('products as p', 'pf.product', '=', 'p.product_code')
        ->leftJoin('product_variant as pv','pf.variant', '=', 'pv.variant_code')
        ->where('pf.customer_code', '=', $customer_code)->orderBy('pf.created_at', 'DESC')->get();



        // $variant_favorite = 
        return view('layouts.main_views.customer_views.favorite-customer', compact('product_favorite'));
    }

    public function notification(Request $request) {

        $auth_check = auth()->guard('customer')->user();
        $customer_code = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->customer_code;
    
        $notifications = DB::table('customer_notifications as cn')
                    ->select('cn.title', 'cn.message', 'cn.is_read','cn.category','cn.created_at', 'cnd.transaction as transaction_code', 'cnd.reward', 'cnd.voucher')
                    ->leftJoin('notification_categories as nc', 'cn.category', '=', 'nc.id')
                    ->leftJoin('customer_notifications_detail as cnd', 'cn.id', '=', 'cnd.notif')
                    ->where('cn.customer', $customer_code)
                    ->orderBy('cn.created_at', 'DESC')
                    ->get();



        $user_register = DB::table('customer')->where('customer_code', $customer_code)->exists();
        return view('layouts.main_views.customer_views.notifications', compact('notifications', 'user_register'));
    }

     public function download_invoice_pdf(Request $request)
    {
     
       $print_date = now()->format('dmy');

        $invoice = DB::table('v_main_transactions')
            ->where('transaction_code', $request->transaction_code)
            ->first();

        $invoices = DB::table('v_transaction as vt')
            ->where('transaction_code', $request->transaction_code)
            ->get();

        if(!$invoice || !$invoice){

            session()->flash('failed_message', 'Invoice tidak ada!');
            return redirect()->back();

        }
    

        $pdf = Pdf::loadView(
            'layouts.main_pages.invoice.invoice_pdf',
            compact('invoice', 'invoices')
        );

       return $pdf->download($request->transaction_code .'-'. $print_date . '.pdf');
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
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);


        $redeem_code = 'REDEEM' . $unique_code;
        $reward_point = $request->point;
        $reward_code = $request->reward_code;
        $reward_code_store = $request->reward;
        $customer_point = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer()->point;
        $quantity = $request->quantity;

        $totalPoint = $reward_point * $quantity;
        $result_point = $customer_point - $totalPoint;



        $reward_exists = DB::table('v_rewards')
            ->where('reward_store_code', $reward_code_store)->first();
       
        if($reward_exists->stock == null || $reward_exists->stock == 0)
        {
           session()->flash('failed_message', 'Maaf, Kuota Reward ini sudah habis!');
            return redirect()->back(); 
        }

        if($reward_exists->end_date < now())
        {
           session()->flash('failed_message', 'Maaf, Masa berlaku Reward ini sudah habis!');
            return redirect()->back(); 
        }

        if($totalPoint > $customer_point){
            session()->flash('failed_message', 'Point anda tidak mencukupi untuk reward ini');
            return redirect()->back();
        }

           RedeemRewardModel::create([
                'redeem_code' => $redeem_code,
                'reward' => $reward_code_store,
                'customer' => $customer_code,
                'status' => 12,
                'pickup_schedule' => $request->pickup_schedule,
                'quantity' => $request->quantity,
                'redeem_date' => now(),
                'created_at' => now()
            ]);

            
            RewardsStoreModel::where('reward_store_code', $reward_code_store)->decrement('stock', $quantity);
        
            CustomerModel::where('customer_code', $customer_code)->update([
                'point' => $result_point
            ]);

            // Buat notifikasi ke email


            CustomerNotification::log(
                customer: $customer_code,
                title: 'Redeem Reward Berhasil!',
                message:'Terima kasih telah Redeem Reward',
                category: 4,
                is_read: 'N',
                reward: $redeem_code
            );

            session()->flash('message_success', 'Berhasil Redeem Reward!');
            return redirect()->route('rewards-history');
        


    }

    public function get_stock(Request $request)
    {
        $data = DB::table('v_rewards')
        ->select('stock', 'reward_store_code')
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

    public function product_review_save(Request $request){

        $request->validate([
            'product_code' => 'required|array',
            'product_code.*' => 'required',
            'variant_code' => 'nullable|array',
            'variant_code.*' => 'nullable|string',
            'review' => 'array|required',
            'rating' => 'array|required',
            'transaction_code' => 'array',
            'feedback' => 'array',
            'feedback_type' => 'array'
        ]);

        $transaction = $request->transaction;
        $check_transaction = DB::table('transactions')->where('transaction_code', $transaction)->first();
        $review_available = DB::table('product_reviews')->where('transaction', $transaction)->first();
        $transaction_code = $request->transaction_code;
        $product_code = $request->product_code;
        $variant_code = $request->variant_code ?? [];
        $transaction_code = $request->transaction_code ?? [];
        $feedbackType = $request->feedback_type;
        $review = $request->review;
        $rating = $request->rating;
        $date = now()->format('Ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $feedbackCode = 'FEEDBACK-'. $date . '-'. $transaction;

        if(!$check_transaction){
            session()->flash('failed_message', 'Tidak ada transaksi!');
            return redirect()->back();
        }

        if($review_available){
            session()->flash('failed_message', 'Anda sudah review ini!');
            return redirect()->back();
        }


        foreach($product_code as $index => $productId){
            ProductReviews::create([
                'transaction' => $transaction_code[$index],
                'product' => $productId,
                'variant' =>  $variant_code[$index] ?? null,
                'review' => $review[$index],
                'rating' => $rating[$index],
                'hidden_name' => $request->hidden_name ?? 'N',
                'review_date' => now(),
                'created_at' => now()
            ]);
        }


        $feedback = CustomerFeedback::create([
            'feedback_code' => $feedbackCode,
            'transaction' => $transaction,
            'feedback_message' => $request->feedback_message,
            'feedback_date' => now()
        ]); 

        foreach($feedbackType as $index =>$key){
            CustomerFeedbackDetail::create([
                'feedback' => $feedbackCode,
                'feedback_type' => $feedbackType[$index]
            ]);
        }

       

        session()->flash('message_success', 'Terima kasih telah memberikan Review dan Rating!');
        return redirect()->back();

    }


    public function customer_feedback(Request $rq){

        $customer_feedback = DB::table('customer_feedback as cf')
        ->leftJoin('v_main_transactions as t', 'cf.transaction', '=', 't.transaction_code')
        ->get();

        return view('layouts.main_pages.customers.customer_feedback', compact('customer_feedback'));
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
