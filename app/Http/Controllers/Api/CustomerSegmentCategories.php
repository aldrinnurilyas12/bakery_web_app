<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerSegmentCategories as ModelsCustomerSegmentCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSegmentCategories extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category_data = DB::table('customer_segment as cs')
            ->select('cs.id', 'cs.segment_name', 'cs.min_transaction', 'cs.max_transaction', 'cs.min_spent',
             'cs.max_spent', 'cs.recency','cs.indicator', 'cs.color', 'cs.sort_order','sc.status_name', 'cs.created_at', 'cs.updated_at', 'e.name', 'emp.name')
            ->leftJoin('status_category as sc', 'cs.status', '=', 'sc.id')
            ->leftJoin('employee as e', 'cs.created_by', '=', 'e.nik')
            ->leftJoin('employee as emp', 'cs.updated_by', '=', 'emp.nik')->get();
            
        return view('layouts.main_pages.customer_segment_categories.category', compact('category_data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.main_pages.customer_segment_categories.create.category_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'segment_name' => 'required',
            'color' => 'required',
            'sort_order' => 'required'
        ],
        [
            'segment_name.required' => 'Nama Segment harus diisi',
            'color.required' =>' Warna segment harus disii',
            'sort_order.required' => 'Urutan segment harus diisi'     
        ]);

        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        ModelsCustomerSegmentCategories::create([
            'segment_name' =>$request->segment_name,
            'min_transaction' =>$request->min_transaction,
            'max_transaction' =>$request->max_transaction,
            'min_spent' =>$request->min_spent,
            'max_spent' =>$request->max_spent,
            'recency' => $request->recency,
            'indicator' => $request->indicator,
            'color' =>$request->color,
            'sort_order' =>$request->sort_order,
            'status' =>7,
            'created_by' =>$user,
            'updated_by'=>$user
        ]);


        session()->flash('message_success', 'Data Kategori Segment Pelanggan berhasil disimpan!');
        return redirect()->route('customer_segment.index');
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
