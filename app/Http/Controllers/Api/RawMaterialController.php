<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RawMaterialController extends Controller
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
    {   $material_category = DB::table('raw_material_category')->get();
        return view('layouts.main_pages.raw_material.create.raw_material_create', compact('material_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_name'=> 'required',
            'quantity' => 'required',
            'price'=> 'required',
            'material_type'=> 'required',
            'material_category'=> 'required',
            'expired_date'=> 'required'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $material_code = 'RAW' . $unique_code;

        RawMaterial::create([
            'material_code' =>$material_code,
            'material_name' =>$request->material_name,
            'quantity' => $request->quantity,
            'price' =>$request->price,
            'material_type' =>$request->material_type,
            'material_category' => $request->material_category,
            'expired_date' =>$request->expired_date,
            'status' => 4,
            'created_by' =>$created_by,
            'created_at' => now()

        ]);

         session()->flash('message_success', 'Data Bahan Baku berhasil disimpan!');
        return redirect()->route('raw_material');
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
        $raw_material = DB::table('raw_material as rm')
                            ->leftJoin('status_category as s','rm.status', '=', 's.id')
                            ->leftJoin('raw_material_category as ctg', 'rm.material_category', '=', 'ctg.id')
                            ->where('material_code', $request->material_code)->first();
        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();
        $material_category = DB::table('raw_material_category')->get();
        $expired_date = Carbon::Parse($raw_material->expired_date);
        return view('layouts.main_pages.raw_material.edit.raw_material_edit', compact('raw_material','material_category', 'status', 'expired_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'material_name'=> 'required',
            'quantity' => 'required',
            'price'=> 'required',
            'expired_date'=> 'required'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        RawMaterial::where('material_code', $request->material_code)->update([
            'material_name' =>$request->material_name,
            'quantity' => $request->quantity,
            'price' =>$request->price,
            'material_type' =>$request->material_type,
            'material_category' =>$request->material_category,
            'expired_date' =>$request->expired_date,
            'status' => $request->status,
            'updated_by' =>$updated_by,
            'updated_at' => now()

        ]);

        session()->flash('message_success', 'Data Bahan Baku berhasil disimpan!');
        return redirect()->route('raw_material');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $raw_material = RawMaterial::where('material_code', $request->material_code)->first();

        if($raw_material){
            $raw_material->delete();
        }
        session()->flash('message_success', 'Data Bahan Baku berhasil dihapus!');
        return redirect()->route('raw_material');
    }
}
