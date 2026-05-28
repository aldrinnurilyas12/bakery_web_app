<?php

namespace App\Http\Controllers\Api;

use App\Events\NewProductCategoryCreated as EventsNewProductCategoryCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ItemsCategoryModel;
use App\Services\UserLogActivity;
use Events\App\Events\NewProductCategoryCreated;

class ItemsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;

        $category_data = DB::table('product_category')->get();

        $category_data = ItemsCategoryModel::leftJoin('products as p', 'product_category.id', '=', 'p.category_id')
            ->select(
                'product_category.*',
                DB::raw('COUNT(p.category_id) as total_used')
            )
            ->groupBy('product_category.id')
            ->get();

        return view('layouts.main_pages.category.category', compact('category_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function category_create()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        return view('layouts.main_pages.category.create.category_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'icon' => 'required'
        ],
        [
            'category_name.required' => 'Nama kategori harus diisi',
            'icon.required' => 'Icon harus diisi'
        ]);
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;


       $category = ItemsCategoryModel::create([
            'category_name' => $request->category_name,
            'icon' => $request->icon
        ]);

        UserLogActivity::log(
            module: 'Product Category',
            method_type: 'CREATE',
            description: "user create new category"      
        );

        broadcast(new EventsNewProductCategoryCreated($category))->toOthers();

        session()->flash('message_success', 'Data kategori berhasil disimpan!');
        return redirect()->route('master_category.index');
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
    public function category_update(string $id, Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $ctgid = $request->id;
        $checking_category = ItemsCategoryModel::find($id);

        $category_data = ItemsCategoryModel::where('id', $ctgid)->get();

        if (!$checking_category && $category_data->isEmpty()) {
            return view('errors.404');
            
        }

        UserLogActivity::log(
            module: 'Product Category',
            method_type: 'UPDATE',
            description: "user update category: {$category_data->first()->category_name}"      
        );
        return view('layouts.main_pages.category.edit.category_edit', compact('category_data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
            'icon' => 'required'
        ],
        [
            'category_name.required' => 'Nama kategori harus diisi',
            'icon.required' => 'Icon harus diisi'
        ]);

        $update_data = DB::table('product_category')->where('id', $id)->update([
            'category_name' => $request->category_name,
            'icon' => $request->icon,
            'updated_at' => now()
        ]);

         UserLogActivity::log(
            module: 'Product Category',
            method_type: 'UPDATE',
            description: "user update category: {$request->category_name}"      
        );

        if ($update_data) {
            session()->flash('message_success', 'Data kategori berhasil diperbarui!');
            return redirect()->route('master_category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoryId = ItemsCategoryModel::find($id);
        $category_data = DB::table('product_category')->where('id', $id)->get();

        
        if ($categoryId) {
            UserLogActivity::log(
                module: 'Product Category',
                method_type: 'DELETE',
                description: "user delete category: {$category_data->first()->category_name}"      
            );
            $categoryId->delete();
        }

        session()->flash('message_success', 'Data kategori berhasil dihapus!');
        return redirect()->route('master_category.index');
    }
}
