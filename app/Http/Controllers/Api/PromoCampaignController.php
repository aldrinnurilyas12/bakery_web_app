<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCampaign;
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
    public function create() :View
    {
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
            'min_transaction' => 'required'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0,8);
        
        PromoCampaign::create([
            'promo_name' => $request->promo_name,
            'promo_code' => $request->promo_code,
            'product' => $request->product,
            'min_transaction' => $request->min_transaction,
            'status' => $request->status,
            'quota' => $request->quota,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => $created_by
        ]);
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
    public function edit(string $id, Request $request) :View
    {
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
            'min_transaction' => 'required'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

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
