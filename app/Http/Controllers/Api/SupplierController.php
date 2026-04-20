<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplierCategoryModel;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(){
        $supplier = DB::table('supplier as s')
        ->leftJoin('supplier_category as sc', 's.supplier_category', '=', 'sc.id')
        ->leftJoin('status_category as scy', 's.status', '=', 'scy.id')->get();

        return view('layouts.main_pages.supplier.supplier',compact('supplier'));
    }


    public function store(Request $rq)
    {
        $rq->validate([
            'store' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'pic' => 'required',
            'supplier_category' => 'required'
        ],
        [
            'store.required' => 'Nama Perusahaan harus diisi',
            'phone_number.required' => 'No.Telepon harus diisi',
            'address.required' => 'Alamat harus diisi',
            'pic.required' => 'Penanggung Jawab harus diisi',
            'supplier_category.required' => 'Kategori supplier harus diisi'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $supplier_code = 'SP-' . $unique_code;

        SupplierModel::create([
            'supplier_code' => $supplier_code,
            'store' => $rq->store,
            'phone_number' => $rq->phone_number,
            'address' => $rq->address,
            'pic' => $rq->pic,
            'supplier_category' => $rq->supplier_category,
            'status' => 7
        ]);

        session()->flash('message_success', 'Data Supplier berhasil disimpan!');
        return redirect()->route('supplier.index');
    }

    public function create()  {

        $supplier_category = DB::table('supplier_category')->get();
        return view('layouts.main_pages.supplier.create.supplier_create', compact('supplier_category'));
    }

    public function update_layouts(Request $rq)
    {

        $supplier = DB::table('supplier')->where('supplier_code', $rq->supplier_code)->first();
         $supplier_category = DB::table('supplier_category')->get();
         $status = DB::table('status_category')->whereIn('id', ['7','8'])->get();
        return view('layouts.main_pages.supplier.edit.supplier_edit', compact('supplier', 'supplier_category', 'status'));
    }

    public function edit(Request $rq)
    {
        $rq->validate([
            'store' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'pic' => 'required',
            'supplier_category' => 'required'
        ],
        [
            'store.required' => 'Nama Perusahaan harus diisi',
            'phone_number.required' => 'No.Telepon harus diisi',
            'address.required' => 'Alamat harus diisi',
            'pic.required' => 'Penanggung Jawab harus diisi',
            'supplier_category.required' => 'Kategori supplier harus diisi'
        ]);

          SupplierModel::where('supplier_code', $rq->supplier_code)->update([
            'store' => $rq->store,
            'phone_number' => $rq->phone_number,
            'address' => $rq->address,
            'pic' => $rq->pic,
            'supplier_category' => $rq->supplier_category,
            'status' => $rq->status
        ]);

        session()->flash('message_success', 'Data Supplier berhasil disimpan!');
        return redirect()->route('supplier.index');
    }

    public function supplier_category(Request $rq)
    {
        $supplier_category = DB::table('supplier_category')->get();
        return view('layouts.main_pages.supplier.supplier_category', compact('supplier_category'));
    }

    public function category_supplier_layouts(Request $rq)
    {
        return view('layouts.main_pages.supplier.create.category_supplier_create');
    }

    public function category_supplier_save(Request $request)
    {
        $request->validate([
            'category_name' =>'required',
            'description' => 'required'
        ],
        [
            'category_name.required' =>'Nama kategori harus diisi',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        SupplierCategoryModel::create([
            'category_name' =>$request->category_name,
            'description' => $request->description
        ]);

        session()->flash('message_success', 'Data Supplier berhasil disimpan!');
        return redirect()->route('supplier_category');

    }

    public function category_supplier_update(Request $request)
    {
        $supplier_category = DB::table('supplier_category')->where('id', $request->id)->first();
         return view('layouts.main_pages.supplier.edit.category_supplier_edit', compact('supplier_category'));
    }

    public function category_supplier_edit(Request $request)
    {
         $request->validate([
            'category_name' =>'required',
            'description' => 'required'
        ],
        [
            'category_name.required' =>'Nama kategori harus diisi',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        SupplierCategoryModel::where('id', $request->id)->update([
            'category_name' =>$request->category_name,
            'description' => $request->description
        ]);

         session()->flash('message_success', 'Data Supplier berhasil disimpan!');
        return redirect()->route('supplier_category');
    }
    

    public function destroy(Request $rq)
    {

    }
}
