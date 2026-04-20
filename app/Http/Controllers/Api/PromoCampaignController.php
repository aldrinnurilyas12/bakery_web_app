<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCampaign;
use App\Models\PromoCampaignImages;
use App\Models\PromoCampaignProducts;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PromoCampaignController extends Controller
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
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $products = DB::table('v_daily_products')->where('status', 'Ready')->get();
        $status  = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        return view('layouts.main_pages.promo_campaign.create.promo_campaign_create', compact('products', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_name' => 'required',
            'promo_code' => 'required',
            'quota' => 'required',
            'status' => 'required',
            'description' => 'required',
            'min_transaction' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'images' => 'required|image|mimes:jpg,png,jpeg,JPG,PNG'
        ],
        [
            'promo_name.required' => 'Masukan nama promo',
            'promo_code.required' => 'Masukan kode promo',
            'min_transaction.required' => 'Masukan minimal transaksi',
            'quota.required' => 'Kuota promo harus diisi',
            'status.required' => 'Pilih status',
            'description.required' => 'Deskripsi harus diisi',
            'start_date.required' => 'Tanggal awal promo harus diisi',
            'end_date.required' => 'Tanggal akhir promo harus diisi',
            'images.required' => 'Gambah harus diupload'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0,8);
        
        if($request->hasFile('images')){
             $promo_image = $request->file('images');
             $folderPath = 'promo_images';
             $imagePath = $promo_image->storeAs($folderPath, uniqid() . '.' . $promo_image->getClientOriginalExtension(), 'public');
            
            $promo = PromoCampaign::create([
                'promo_name' => $request->promo_name,
                'promo_code' => $request->promo_code,
                'min_transaction' => $request->min_transaction,
                'status' => $request->status,
                'quota' => $request->quota,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $created_by
            ]);

            PromoCampaignImages::create([
                'promo_code' => $promo->promo_code,
                'images' => $imagePath,
                'created_at' => now()
            ]);
        }
        session()->flash('message_success', 'Data Promo berhasil disimpan!');
        return redirect()->route('promo_campaign');
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
        $products = DB::table('v_daily_products')->where('status', 'Ready')->get();
        $promo = DB::table('v_promos')->where('promo_code', $request->promo_code)->first();
        $status  = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $start_date = Carbon::parse($promo->start_date);
        $end_date = Carbon::parse($promo->end_date);
        return view('layouts.main_pages.promo_campaign.edit.promo_edit', compact('products', 'promo', 'status', 'start_date', 'end_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'promo_name' => 'required',
            'promo_code' => 'required',
            'quota' => 'required',
            'status' => 'required',
            'description' => 'required',
            'min_transaction' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ],
        [
            'promo_name.required' => 'Masukan nama promo',
            'promo_code.required' => 'Masukan kode promo',
            'min_transaction.required' => 'Masukan minimal transaksi',
            'quota.required' => 'Kuota promo harus diisi',
            'status.required' => 'Pilih status',
            'description.required' => 'Deskripsi harus diisi',
            'start_date.required' => 'Tanggal awal promo harus diisi',
            'end_date.required' => 'Tanggal akhir promo harus diisi',
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        PromoCampaign::where('promo_code', $request->promo_code)->update([
            'promo_name' => $request->promo_name,
            'product' => $request->product,
            'min_transaction' => $request->min_transaction,
            'status' => $request->status,
            'quota' => $request->quota,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'updated_by' => $updated_by
        ]);
        session()->flash('message_success', 'Data Promo berhasil disimpan!');
        return redirect()->route('promo_campaign');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
       $promo = PromoCampaign::where('promo_code', $request->promo_code)->first();

        if($promo){
            $promo->delete();
        }
         session()->flash('message_success', 'Data Promo berhasil disimpan!');
        return redirect()->back();
    }
}
