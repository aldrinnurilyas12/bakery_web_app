<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemsModel;
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
    public function create()
    {   $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $material_category = DB::table('raw_material_category')->get();
        return view('layouts.main_pages.raw_material.create.raw_material_create', compact('material_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_name'=> 'required',
            'material_type'=> 'required',
            'material_category'=> 'required',
            'expired_date'=> 'required'
        ],
        [
            'material_name.required' => 'Nama bahan baku harus diisi',
            'material_type.required' => 'Tipe bahan baku harus diisi',
            'material_category.required' => 'Kategori bahan baku harus diisi',
            'expired_date.required' => 'Tanggal kadaluarsa harus diisi'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $created = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $unique_code_item = substr($uuid, 0, 5);
        $material_code = 'RAW' . $unique_code;
        $item_code = 'ITEM-' . $unique_code_item;


       $raw = RawMaterial::create([
            'material_code' =>$material_code,
            'material_name' =>$request->material_name,
            'material_type' =>$request->material_type,
            'material_category' => $request->material_category,
            'expired_date' =>$request->expired_date,
            'status' => 4,
            'created_by' =>$created_by,
            'created_at' => now()

        ]);

        $items = ItemsModel::create([
            'item_code' => $item_code,
            'raw_material'=> $raw->material_code,
            'name' => $raw->material_name,
            'item_category' => 1,
            'weight_type' => $raw->material_type,
            'created_at' => now(),
            'created_by' => $created
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
    public function edit(string $id, Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
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
            'price'=> 'required',
            'material_type'=> 'required',
            'material_category'=> 'required',
            'expired_date'=> 'required'
        ],
        [
            'material_name.required' => 'Nama bahan baku harus diisi',
            'price.required' => 'Harga bahan baku harus diisi',
            'material_type.required' => 'Tipe bahan baku harus diisi',
            'material_category.required' => 'Kategori bahan baku harus diisi',
            'expired_date.required' => 'Tanggal kadaluarsa harus diisi'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        RawMaterial::where('material_code', $request->material_code)->update([
            'material_name' =>$request->material_name,
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


    public function history_raw_material(Request $rq)
    {
        $history = DB::table('purchase_order_detail as po')
        ->select('po.purchase_code', 'po.quantity as qty_po', 'rm.material_name','po.price', 'po.created_at')
        ->leftJoin('raw_material as rm', 'po.raw_material', '=', 'rm.material_code')
        ->where('raw_material', $rq->material_code)
        ->orderBy('created_at', 'DESC')->get();
        return view('layouts.main_pages.raw_material.history_raw_material_po', compact('history'));
    }

    public function raw_material_usages(Request $rq)
    {
        $material_usages = DB::table('raw_material_usages as rmu')
            ->leftJoin('raw_material as rm', 'rmu.raw_material', '=', 'rm.material_code')
            ->where('rmu.raw_material', $rq->material_code)->get();
        return view('layouts.main_pages.raw_material.raw_material_usages', compact('material_usages'));
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
