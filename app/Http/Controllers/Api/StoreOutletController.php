<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeModel;
use App\Models\OutletStoreModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\UserLogActivity;

class StoreOutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = DB::table('store as st')
                ->leftJoin('employee as e', 'st.head_of_branch', '=', 'e.nik')
                ->leftJoin('status_category as sc', 'st.status', '=', 'sc.id')->get();
        $status = DB::table('status_category')->whereIn('id',['7','8'])->get();

        return view('layouts.main_pages.outlet.store', compact('store', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store_create_layout()
    {
        $employee = DB::table('employee as e')->leftJoin('store as st', 'e.nik', '=', 'st.head_of_branch')
            ->where('position', 'HST')->where('st.head_of_branch', null)
            ->get();
        return view('layouts.main_pages.outlet.create.store_create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required',
            'head_of_branch' => 'required',
            'location' => 'required'
        ],
        [
            'store_name.required' => 'Nama Store harus diisi',
            'head_of_branch.required' => 'Kepala store harus dipilih',
            'location.required' => 'Lokasi store harus diisi'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $store_code = 'ST' . $unique_code;

        if($request->head_of_branch == null){
            session()->flash('failed_message', 'Data Karyawan tidak ada!');
            return redirect()->back();
        }

        OutletStoreModel::create([
            'store_name' => $request->store_name,
            'store_code' => $store_code,
            'location' => $request->location,
            'head_of_branch' => $request->head_of_branch,
            'latitude' => $request->latitude,
            'longitude' => $request->lomgitude,
            'status' => 7
        ]);

        UserLogActivity::log(
                module: 'Store',
                method_type: 'CREATE',
                description: "user create new store: {$request->store_name}"      
        );

        session()->flash('message_success', 'Data Outlet/Store berhasil disimpan');
        return redirect()->back();
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
    public function edit_layout(string $id, Request $request)
    {
        $head_store = DB::table('store as st')
            ->leftJoin('employee as e', 'st.head_of_branch', '=', 'e.nik')
            ->where('st.store_code', $request->store_code)
            ->first();
         $employee = DB::table('employee as e')->leftJoin('store as st', 'e.nik', '=', 'st.head_of_branch')
            ->where('position', 'HST')->where('st.head_of_branch', null)
            ->get();
        $store = DB::table('store')->where('store_code', $request->store_code)->first();

        return view('layouts.main_pages.outlet.edit.store_edit', compact('store', 'employee', 'head_store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $request->validate([
            'store_name' => 'required',
            'location' => 'required'
        ]);

        $head_store = $request->head_of_branch;
        $checking_store = DB::table('store')->select('head_of_branch')
                        ->where('store_code', $request->store_code)->first();

        $data = [
            'head_of_branch' => $head_store,
            'store_name' => $request->store_name,
            'location'   => $request->location,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'updated_at' => now()
        ];

        if ($checking_store && $checking_store->head_of_branch === null) {
            $data['head_of_branch'] = $head_store;
        }

        OutletStoreModel::where('store_code', $request->store_code)
        ->update($data);

        UserLogActivity::log(
                module: 'Store',
                method_type: 'UPDATE',
                description: "user update store: {$request->store_name}"      
        );

        session()->flash('message_success', 'Data Outlet/Store berhasil diperbarui');
        return redirect()->route('store.index');
    }

    public function update_status_store(Request $request) 
    {
         OutletStoreModel::where('store_code', $request->store_code)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        UserLogActivity::log(
                module: 'Store',
                method_type: 'UPDATE',
                description: "user update status store: {$request->store_name}"      
        );
        session()->flash('message_success', 'Data Outlet/Store berhasil disimpan');
        return redirect()->back();
    }

   
    public function delete_head_store(Request $request)
    {

        OutletStoreModel::where('store_code', $request->store_code)->update([
            'head_of_branch' => null,
            'updated_at' => now()
        ]);

        UserLogActivity::log(
                module: 'Store',
                method_type: 'DELETE',
                description: "user delete head of store: {$request->store_code}"      
        );
        session()->flash('message_success', 'Data Outlet/Store berhasil disimpan');
        return redirect()->back();
        
    }

    public function destroy(string $id)
    {
        //
    }
}
