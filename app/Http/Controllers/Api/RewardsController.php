<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutletStoreModel;
use App\Models\RedeemRewardModel;
use App\Models\RewardsModel;
use App\Models\RewardsStoreModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\UserLogActivity;


class RewardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;
        $rewards = DB::table('v_rewards')->where('store_code', $store)->orderBy('created_at', 'DESC')->get();
        $store_reward = DB::table('v_rewards')->get();
       return view('layouts.main_pages.rewards.rewards', compact('rewards', 'store_reward'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        $store = OutletStoreModel::where('status', 7)->get();
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        return view('layouts.main_pages.rewards.create.rewards_create', compact('store'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rewards_name' => 'required',
            'point' => 'required', 
            'images' => 'required|image|mimes:jpg,png,jpeg,JPG,PNG',
            'start_date' => 'required',
            'end_date' => 'required',
            'store' => 'required|array',
            'store.*' => 'required|exists:store,store_code',
            'stock' => 'required|array'

        ], 
        [
            'rewards_name.required' => 'Nama reward harus diisi',
            'point.required' => 'Point harus diisi', 
            'images.required' => 'Gambar harus diisi',
            'start_date.required' => 'Tanggal awal berlaku harus diisi',
            'end_date.required' => 'Tanggal akhir berlaku harus diisi',
            'store.required' => 'Store harus diisi',
            'stock.required' => 'Stock tidak boleh kosong'

        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);
        $rewards_code = 'REWARD' . $unique_code;
        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        $store_code = $request->store;
        $stock = $request->stock;


        if($store_code == null && $stock == null){
            session()->flash('message_success', 'Store dan Stok wajib diisi!');
            return redirect()->back();
        }

        if($request->hasFile('images')){
             $rewards_image = $request->file('images');
             $folderPath = 'rewards';
             $imagePath = $rewards_image->storeAs($folderPath, uniqid() . '.' . $rewards_image->getClientOriginalExtension(), 'public');
        
           $main_reward = RewardsModel::create([
                'rewards_code' => $rewards_code,
                'rewards_name' => $request->rewards_name,
                'point' => $request->point, 
                'images' => $imagePath,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $created_by,
                'created_at' => now()
            ]);

            foreach($store_code as $arrayStore){
                $reward_store_code = 'RWST-' . substr((string) Str::uuid(), 0, 8);
                RewardsStoreModel::create([
                    'reward_store_code' => $reward_store_code,
                    'reward' => $main_reward->rewards_code,
                    'store' => $arrayStore,
                    'initial_stock' => (int) ($stock[$arrayStore] ?? 0),
                    'stock' => (int) ($stock[$arrayStore] ?? 0),
                    'status' => 7,
                    'created_at' => now(),
                    'created_by' => $created_by,
                    'updated_at' => null
                ]);
            }
        }

        UserLogActivity::log(
                module: 'Rewards',
                method_type: 'CREATE',
                description: "user create new rewards: {$rewards_code}"      
        );

        session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->route('rewards');
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
    public function edit(string $id, Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $rewards = DB::table('rewards_store as rs')
        ->leftJoin('rewards as r', 'rs.reward', '=', 'r.rewards_code')
        ->where('reward_store_code',$request->reward_store_code)
        ->first();
        return view('layouts.main_pages.rewards.edit.rewards_edit', compact('rewards','status'));
    }

    public function edit_master_reward(string $id, Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $rewards = DB::table('rewards')
        ->where('rewards_code',$request->rewards_code)
        ->first();
        $start_date = Carbon::parse($rewards->start_date);
        $end_date = Carbon::parse($rewards->end_date);
        return view('layouts.main_pages.rewards.edit.rewards_edit', compact('rewards','status', 'start_date', 'end_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'rewards_name' => 'required',
            'point' => 'required', 
            'images' => 'image|mimes:jpg,png,jpeg,JPG,PNG',
            'start_date' => 'required',
            'end_date' => 'required',
            'store' => 'required|array',
            'store.*' => 'required|exists:store,store_code',
            'stock' => 'required|array'

        ], 
        [
            'rewards_name.required' => 'Nama reward harus diisi',
            'point.required' => 'Point harus diisi', 
            'images.required' => 'Gambar harus diisi',
            'start_date.required' => 'Tanggal awal berlaku harus diisi',
            'end_date.required' => 'Tanggal akhir berlaku harus diisi',
            'store.required' => 'Store harus diisi',
            'stock.required' => 'Stock tidak boleh kosong'

        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        $images_old = DB::table('rewards')->select('images')->where('rewards_code', $request->rewards_code)->first();
        if($request->hasFile('images')){
             $rewards_image = $request->file('images');
             $folderPath = 'rewards';
             $imagePath = $rewards_image->storeAs($folderPath, uniqid() . '.' . $rewards_image->getClientOriginalExtension(), 'public');
        
            RewardsModel::where('rewards_code', $request->rewards_code)->update([
                'rewards_name' => $request->rewards_name,
                'point' => $request->point, 
                'images' => $imagePath,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated_by' => $updated_by,
                'updated_at' => now()
            ]);

            $dropPicture = public_path('storage/' . $images_old->images);
            if(file_exists($dropPicture)){
                unlink($dropPicture);
            }
        }

        UserLogActivity::log(
                module: 'Rewards',
                method_type: 'UPDATE',
                description: "user update rewards: {$rewards_code}"      
        );
        session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->route('rewards');
    }


    // Function untuk update Reward per store
    public function update_reward_store(Request $request){
        
        $request->validate([
            'stock' => 'required'
        ], 
        [
            'stock.required' => 'Stock tidak boleh kosong'

        ]);

        RewardsStoreModel::where('reward_store_code', $request->reward_store_code)->update([
                'stock' => $request->stock,
                'status' => $request->status
        ]);

         UserLogActivity::log(
                module: 'Rewards',
                method_type: 'UPDATE',
                description: "user update rewards store: {$request->reward_store_code}"      
        );

        session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->route('rewards');

    }



    public function update_nonactive_rewards(Request $request) {
         $request->validate([
            'status' => 'required'
        ], 
        [
            'status.required' => 'Centang status dahulu'

        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        if(!$request->status){
            session()->flash('failed_message', 'Centang status dahulu!');
            return redirect()->back();
        }

        if($request->status == 7){
            RewardsModel::where('rewards_code', $request->reward)->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated_at' => now(),
                'updated_by' => $updated_by
            ]);
        }

        RewardsStoreModel::where('reward', $request->reward)->update([
            'status' => $request->status,
            'updated_by' => $updated_by,
            'updated_at' => now()
        ]);

         UserLogActivity::log(
                module: 'Rewards',
                method_type: 'UPDATE',
                description: "user nonactive rewards: {$request->reward}"      
        );

        session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->back();
    }

    public function claim_reward_layouts(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $reward_data = DB::table('redeem_reward as rr')
                    ->select('rr.id','rr.redeem_code', 'rr.reward', 'r.rewards_name', 'c.name as customer', 'sc.status_name',  'rr.quantity', 'rr.pickup_schedule', 'rr.redeem_date', 'e.name as approval_by', 'rr.claimed_at','rr.created_at', 'rr.updated_at')
                    ->leftJoin('rewards_store as rws', 'rr.reward', '=', 'rws.reward_store_code')
                    ->leftJoin('rewards as r','rws.reward', '=', 'r.rewards_code')
                    ->leftJoin('customer as c', 'rr.customer', '=', 'c.customer_code')
                    ->leftJoin('employee as e', 'rr.redeem_by', '=', 'e.nik')
                    ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
                    ->where('rws.store', $store)
                    ->orderBy('created_at', 'DESC')
                    ->get();
        
        $reward_data_claimed = DB::table('redeem_reward as rr')
                    ->select('rr.id','rr.redeem_code', 'rr.reward', 'r.rewards_name', 'c.name as customer', 'sc.status_name', 'rr.pickup_schedule', 'rr.quantity','rr.redeem_date', 'e.name as approval_by', 'rr.claimed_at','rr.created_at', 'rr.updated_at')
                    ->leftJoin('rewards_store as rws', 'rr.reward', '=', 'rws.reward_store_code')
                    ->leftJoin('rewards as r','rws.reward', '=', 'r.rewards_code')
                    ->leftJoin('customer as c', 'rr.customer', '=', 'c.customer_code')
                    ->leftJoin('employee as e', 'rr.redeem_by', '=', 'e.nik')
                    ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
                    ->where('rr.status', 11)
                    ->where('rws.store', $store)
                    ->orderBy('created_at', 'DESC')->get();

        $reward_data_unclaimed = DB::table('redeem_reward as rr')
                    ->select('rr.id','rr.redeem_code', 'rr.reward', 'r.rewards_name', 'c.name as customer', 'sc.status_name', 'rr.pickup_schedule', 'rr.quantity', 'rr.redeem_date', 'e.name as approval_by', 'rr.claimed_at','rr.created_at', 'rr.updated_at')
                    ->leftJoin('rewards_store as rws', 'rr.reward', '=', 'rws.reward_store_code')
                    ->leftJoin('rewards as r','rws.reward', '=', 'r.rewards_code')
                    ->leftJoin('customer as c', 'rr.customer', '=', 'c.customer_code')
                    ->leftJoin('employee as e', 'rr.redeem_by', '=', 'e.nik')
                    ->leftJoin('status_category as sc', 'rr.status', '=', 'sc.id')
                    ->where('rr.status', 12)
                    ->where('rws.store', $store)
                    ->orderBy('created_at', 'DESC')->get();
   
        return view('layouts.main_pages.rewards.claim.claim-reward', compact('reward_data','reward_data_claimed', 'reward_data_unclaimed'));
    }

    // APPROVAL REDEEM REWARD BY CASHEER
    public function claimed_reward(Request $request)
    {
        $redeem_code = $request->redeem_code;
        $redeem_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        RedeemRewardModel::where('redeem_code', $redeem_code)->update([
            'status' => 11,
            'claimed_at' => now(),
            'redeem_by' => $redeem_by,
            'updated_at' => now()
        ]);

         UserLogActivity::log(
                module: 'Rewards',
                method_type: 'UPDATE',
                description: "user claim rewards: {$request->redeem_code}"      
        );
        session()->flash('message_success', 'Reward berhasil diklaim!');
        return redirect()->back();
    }

    public function filter_rewards(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);

        if($filter_forbidden_access){
            return redirect()->back();
        }


        $store = DB::table('store')->get();
        $status = DB::table('status_category')->whereIn('id', ['2','3', '4', '5', '9', '10'])->get();
        $filter = $request->filter;

        $rewards = DB::table('v_rewards');


        
        if ($filter !== 'all' && !empty($filter)) {
            $rewards->where('store_id', $filter);
        }


        $rewards = $rewards->get();
         return view('pages.rewards',compact('rewards','store', 'status'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $rewards = RewardsModel::where('rewards_code', $request->rewards_code)->first();

        if($rewards){
            $rewards->delete();
            $dropPicture = public_path('storage/' . $rewards->images);
            if (file_exists($dropPicture)) {
                    unlink($dropPicture);
                }

            UserLogActivity::log(
                module: 'Rewards',
                method_type: 'DELETE',
                description: "user delete rewards: {$request->reward_code}"      
            );

            session()->flash('message_success', 'Data reward berhasil dihapus!');
            return redirect()->route('rewards');
        }
        
    }
}
