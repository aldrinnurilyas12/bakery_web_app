<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = DB::table('items as i')
                ->select('i.item_code', 'i.raw_material', 'i.name as item_name',
                 'ic.category_name as category_name', 'muc.unit_name',
                 'i.created_at', 'i.updated_at', 'e.name as created_by', 'emp.name as updated_by')
                ->leftJoin('raw_material as rm', 'i.raw_material', '=', 'rm.material_code')
                ->leftJoin('item_category as ic', 'i.item_category', '=', 'ic.id')
                ->leftJoin('material_unit_category as muc', 'i.weight_type', '=', 'muc.id')
                ->leftJoin('employee as e', 'i.created_by', '=', 'e.nik')
                ->leftJoin('employee as emp', 'i.updated_by', '=', 'emp.nik')->get();
        return view('layouts.main_pages.items.items', compact('items'));
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
        $category_item = DB::table('item_category')->get();

        return view('layouts.main_pages.items.create.item_create', compact('category_item'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'item_category' => 'required'
        ],
        [
            'name.required' => 'Nama item harus diisi',
            'item_category.required' => 'Kategori item harus diisi'
        ]);

        $created_by =app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $item_code = 'ITEM-' . $unique_code;
        ItemsModel::create([
            'item_code' => $item_code,
            'name' => $request->name,
            'item_category' => $request->item_category,
            'weight_type' => $request->weight_type,
            'created_by' => $created_by,
            'updated_at' => null
        ]);

        session()->flash('message_success', 'Data Item berhasil disimpan!');
        return redirect()->route('master_items.index');
    }


    public function update_layout(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $category_item = DB::table('item_category')->get();
        $items = DB::table('items as i')
        ->leftJoin('item_category as ic', 'i.item_category', '=', 'ic.id')->where('item_code', $request->item_code)->first();
        return view('layouts.main_pages.items.edit.item_update', compact('items', 'category_item'));
    }

    public function edit_item(Request $request)
    {
        $created_by =app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        ItemsModel::where('item_code', $request->item_code)->update([
            'name' => $request->name,
            'updated_by' => $created_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data Item berhasil disimpan!');
        return redirect()->route('master_items.index');
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
