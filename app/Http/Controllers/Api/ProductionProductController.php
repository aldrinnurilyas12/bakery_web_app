<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionProduct;
use App\Models\RawMaterialUsages;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductionProductController extends Controller
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
    public function create() : View
    {
        $products = DB::table('v_products as vp')
        ->leftJoin('products as p', 'vp.product_code', '=', 'p.product_code')
        ->whereNotIn('category_id',['10', '11'])->get();
        $raw_materials = DB::table('raw_material')->get();
        return view('layouts.main_pages.production_products.create.production_create', compact('products', 'raw_materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'raw_material' => 'required|array',
            'raw_material.*' => 'exists:raw_material,material_code',
            'target_total' => 'required',
            'production_type' => 'required',
            'quantity_used' => 'required|array'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $production_code = 'PRODUCTION' . $unique_code;

        $rawMaterials = $request->raw_material;
        $quantities   = $request->quantity_used;

        $production = ProductionProduct::create([
            'production_code' =>$production_code,
            'product' =>$request->product,
            'target_total' =>$request->target_total,
            'status' => 10,
            'production_type' =>$request->production_type,
            'production_date' =>$request->production_date,
            'created_by' =>$created_by,
            'created_at' => now()
        ]);
        
        foreach($rawMaterials as $rawCode) {
            RawMaterialUsages::create([
                'production_code' => $production->production_code,
                'quantity_used' =>(int) ($quantities[$rawCode] ?? 0),
                'raw_material' => $rawCode,
                'created_by' => $created_by,
                'created_at' => now()
            ]);
        }

        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->route('production_products');
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
    public function edit(string $id, Request $request) : View
    {   
        $production = DB::table('v_production_products')
        ->where('production_code', $request->production_code)->first();
        $products = DB::table('v_products')->get();
        $raw_materials = DB::table('raw_material')->get();
        $production_date = Carbon::parse($production->production_date);
        $status = DB::table('status_category')->whereIn('id', ['2','3','4', '5', '9', '10'])->get();
        return view('layouts.main_pages.production_products.edit.production_edit', compact('production','products','raw_materials','production_date','status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
           $request->validate([
            'product' => 'required',
            'raw_material.*' => 'required|string',
            'target_total' => 'required',
            'quantity_used' => 'required|array'
        ]);

        

        DB::transaction(function() use ($request){
            $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
            $rawMaterials = $request->raw_material;
            $quantities   = $request->quantity_used;

            ProductionProduct::where('production_code', $request->production_code)->update([
                'product' =>$request->product,
                'target_total' =>$request->target_total,
                'production_type' =>$request->production_type,
                'production_date' =>$request->production_date,
                'updated_by' =>$updated_by,
                'updated_at' => now()
            ]);

            foreach($request->raw_material  as $rawCode) {
                DB::table('raw_material_usages')->updateOrInsert(
                  [ 
                    'production_code' => $request->production_code,
                    'raw_material' => $rawCode,
                  ],
                  [
                        'quantity_used' =>$request->quantity_used[$rawCode] ?? 0,
                        'updated_by' => $updated_by,
                        'updated_at' => now()
                  ]
                );
            }
        });
        
        session()->flash('message_success', 'Data Produksi Produk berhasil disimpan!');
        return redirect()->route('production_products');
    }

    public function update_target_production(Request $request) {

        $request->validate([
            'reject_quantity' => 'required',
            'actual_quantity' => 'required'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        ProductionProduct::where('production_code', $request->production_code)->update([
            'reject_quantity' =>$request->reject_quantity,
            'actual_quantity' =>$request->actual_quantity,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data Produksi Produk berhasil diperbarui!');
        return redirect()->back();
    }

    public function update_production_reason(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        ProductionProduct::where('production_code', $request->production_code)->update([
            'description' =>$request->description,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Alasan produksi dibatalkan sudah disimpan');
        return redirect()->back();
    }

    public function update_production_status(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;        
        ProductionProduct::where('production_code', $request->production_code)->update([
            'status' => $request->status,
            'updated_by' =>$updated_by,
            'updated_at' => now()
        ]);
        
        session()->flash('message_success', 'Data Produksi Produk berhasil diperbarui!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $production = ProductionProduct::where('production_code', $request->production_code)->first();
        if($production){
            $production->delete();
        }
        session()->flash('message_success', 'Data Produksi Produk berhasil dihapus!');
        return redirect()->route('production_products');
    }
}
