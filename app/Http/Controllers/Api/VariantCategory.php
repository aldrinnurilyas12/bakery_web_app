<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VariantCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\UserLogActivity;

class VariantCategory extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variant_category = VariantCategoryModel::all();
        return view('layouts.main_pages.variant_category.variant_category', compact('variant_category'));
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
        return view('layouts.main_pages.variant_category.create.category_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:variant_category,name'
        ],
        [
            'name.required' => 'Nama variant tidak boleh kosong',
            'name.unique' => 'Nama variant sudah ada'
        ]);

        VariantCategoryModel::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => null
        ]);
        UserLogActivity::log(
                module: 'Variant Category',
                method_type: 'CREATE',
                description: "user create new variant category: {$request->name}"      
        );

        session()->flash('message_success', 'Data Kategori Varian berhasil disimpan!');
        return redirect()->route('variant_category.index');
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
        $variant_category = DB::table('variant_category')->where('id', $request->id)->first();
        return view('layouts.main_pages.variant_category.edit.category_edit', compact('variant_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update_variant = DB::table('variant_category')->where('id', $request->id)->update([
            'name' => $request->name,
            'updated_at' => now() 
        ]);

         UserLogActivity::log(
                module: 'Variant Category',
                method_type: 'UPDATE',
                description: "user update variant category: {$request->name}"      
        );

        session()->flash('message_success', 'Data Kategori Varian berhasil disimpan!');
        return redirect()->route('variant_category.index');
    }


    public function delete_variant_category($id)
    {
       $category = VariantCategoryModel::find($id);

        if($category){
            $category->delete();
            session()->flash('message_success', 'Data Kategori Varian berhasil dihapus!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
