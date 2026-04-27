<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetPointMemberTransaction as ModelsGetPointMemberTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetPointMemberTransaction extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = DB::table('point_member_transactions as p')
        ->select('p.id as id', 'p.point', 'p.start_date', 'p.end_date', 'p.created_at', 'p.updated_at', 'sc.status_name')
        ->leftJoin('status_category as sc', 'p.status', '=', 'sc.id')
        ->orderBy('created_at', 'DESC')->get();

        $check_last_active = DB::table('point_member_transactions')->where('status', 7)->orderBy('created_at', 'DESC')->first();
        // dd($check_last_active);
        return view('layouts.main_pages.point_member_setting.master_point', compact('points', 'check_last_active'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check_last_active = DB::table('point_member_transactions')->where('status', 7)->orderBy('created_at', 'DESC')->first();

        if($check_last_active){
             session()->flash('failed_message', 'Ada point yang sedang aktif!');
        return redirect()->route('point_member_setting.index');
        }
        return view('layouts.main_pages.point_member_setting.create.point_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'point' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'

        ],
        [
            'point.required' => 'Point harus diisi',
            'start_date.required' => 'Tanggal berlaku point harus diisi',
            'end_date.required' => 'Tanggal berakhir point harus diisi'
        ]);


        $data = [
            'point' => $request->point,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 7
        ];

        ModelsGetPointMemberTransaction::create($data);

        session()->flash('message_success', 'Data Point Berhasil disimpan!');
        return redirect()->route('point_member_setting.index');
        

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
        $request->validate([
            'status' => 'required'
        ]);


        ModelsGetPointMemberTransaction::where('id', $request->id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Perubahan status berhasil!');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
