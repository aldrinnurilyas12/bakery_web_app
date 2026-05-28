<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemsModel;
use App\Models\MaterialUnitModel;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\UserLogActivity;


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
        $material_unit = MaterialUnitModel::all();
        return view('layouts.main_pages.raw_material.create.raw_material_create', compact('material_category', 'material_unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_name'=> 'required',
            'purchase_unit'=> 'required',
            'material_category'=> 'required'
        ],
        [
            'material_name.required' => 'Nama bahan baku harus diisi',
            'purchase_unit.required' => 'Tipe bahan baku harus diisi',
            'material_category.required' => 'Kategori bahan baku harus diisi'
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
            'purchase_unit' =>$request->purchase_unit,
            'inventory_unit' =>$request->inventory_unit,
            'material_category' => $request->material_category,
            'status' => 6,
            'created_by' =>$created_by,
            'created_at' => now()

        ]);

        $items = ItemsModel::create([
            'item_code' => $item_code,
            'raw_material'=> $raw->material_code,
            'name' => $raw->material_name,
            'item_category' => 1,
            'weight_type' => $raw->purchase_unit,
            'created_at' => now(),
            'created_by' => $created
        ]);

        UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'CREATE',
                description: "user create new raw material: {$raw->material_name}"      
        );

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
                            ->leftJoin('purchase_order_detail as pod', 'rm.material_code', '=', 'pod.raw_material')
                            ->where('material_code', $request->material_code)
                            ->orderBy('pod.created_at', 'DESC')->first();
        $status = DB::table('status_category')->whereIn('id', ['4', '6'])->get();
        $material_category = DB::table('raw_material_category')->get();
        $material_unit = DB::table('material_unit_category')->get();
        $expired_date = Carbon::Parse($raw_material->expired_date);
        return view('layouts.main_pages.raw_material.edit.raw_material_edit', compact('raw_material','material_category', 'status', 'expired_date', 'material_unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'material_name'=> 'required',
            'purchase_unit'=> 'required',
            'material_category'=> 'required',
        ],
        [
            'material_name.required' => 'Nama bahan baku harus diisi',
            'purchase_unit.required' => 'Tipe bahan baku harus diisi',
            'material_category.required' => 'Kategori bahan baku harus diisi',
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        RawMaterial::where('material_code', $request->material_code)->update([
            'material_name' =>$request->material_name,
            'purchase_unit' =>$request->purchase_unit,
            'inventory_unit' => $request->inventory_unit,
            'material_category' =>$request->material_category,
            'updated_by' =>$updated_by,
            'updated_at' => now()

        ]);

        UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'UPDATE',
                description: "user update raw material: {$request->material_name}"      
        );

        session()->flash('message_success', 'Data Bahan Baku berhasil disimpan!');
        return redirect()->route('raw_material');
    }


    public function history_raw_material(Request $rq)
    {
        $history = DB::table('purchase_order_detail as po')
        ->select('po.purchase_code', 'po.quantity as qty_po', 'rm.material_name','po.price', 'po.created_at','po.expired_date')
        ->leftJoin('raw_material as rm', 'po.raw_material', '=', 'rm.material_code')
        ->where('raw_material', $rq->material_code)
        ->orderBy('created_at', 'DESC')->get();
        return view('layouts.main_pages.raw_material.history_raw_material_po', compact('history'));
    }

    public function unit_material(Request $rq)
    {
       $unit_material = MaterialUnitModel::leftJoin('raw_material as rm', 'material_unit_category.id', '=', 'rm.purchase_unit')
            ->select(
                'material_unit_category.*',
                DB::raw('COUNT(rm.id) as total_used')
            )
            ->groupBy('material_unit_category.id')
            ->get();

        
        return view('layouts.main_pages.raw_material.unit_material', compact('unit_material'));
    }

    public function unit_material_create(Request $rq)
    {

        return view('layouts.main_pages.raw_material.create.unit_material_create');
    }

    public function unit_material_save(Request $rq){
       
        $rq->validate([
            'unit_code' => 'required|max:5',
            'unit_name' => 'required'
        ],
        [
            'unit_code.required' => 'Kode satuan unit harus diisi',
            'unit_name.required' => 'Nama satuan unit harus diisi'
        ]);

        $data = [
            'unit_code' => Str::upper($rq->unit_code),
            'unit_name' => $rq->unit_name,
            'created_at' => now(),
            'updated_at' => null
        ];

        MaterialUnitModel::create($data);
        UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'CREATE',
                description: "user create new unit material: {$rq->unit_name}"      
        );

        session()->flash('message_success', 'Data satuan unit berhasil disimpan!');
        return redirect()->route('unit_material');
    }

    public function unit_material_edit(Request $rq)
    {
        $unit_material = MaterialUnitModel::where('id', $rq->id)->first();
        return view('layouts.main_pages.raw_material.edit.unit_material_update', compact('unit_material'));
    }

    public function unit_material_update(Request $rq)
    {
        $rq->validate([
            'unit_code' => 'required|max:5',
            'unit_name' => 'required'
        ],
        [
            'unit_code.required' => 'Kode satuan unit harus diisi',
            'unit_name.required' => 'Nama satuan unit harus diisi'
        ]);

        $data = [
            'unit_code' => Str::upper($rq->unit_code),
            'unit_name' => $rq->unit_name,
            'updated_at' => now()
        ];

        MaterialUnitModel::where('id', $rq->id)->update($data);
        UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'UPDATE',
                description: "user update unit material: {$rq->unit_name}"      
        );

        session()->flash('message_success', 'Data satuan unit berhasil disimpan!');
        return redirect()->route('unit_material');
    }

    public function unit_material_delete($id)
    {
        $unit = MaterialUnitModel::find($id);

        if($unit){
            UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'DELETE',
                description: "user delete unit material"      
             );
            $unit->delete();
        }
        session()->flash('message_success', 'Data satuan unit berhasil dihapus!');
        return redirect()->route('unit_material');
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
            UserLogActivity::log(
                module: 'Raw Material',
                method_type: 'DELETE',
                description: "user delete raw material: {$request->material_code}"      
            );
            $raw_material->delete();
        }
        session()->flash('message_success', 'Data Bahan Baku berhasil dihapus!');
        return redirect()->route('raw_material');
    }
}
