<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceInformation as ModelsMaintenanceInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MaintenanceInformation extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenance_info = DB::table('maintenance_information as mi')
                ->select('mi.info_code','mi.maintenance_information', 'mi.message', 'mi.start_date', 'mi.hour_start', 'mi.hour_end',
                'mi.end_date', 'e.name as created_by', 'emp.name as updated_by', 'mi.created_at', 'mi.updated_at', 'sc.status_name')
                ->leftJoin('employee as e', 'mi.created_by', '=', 'e.nik')
                ->leftJoin('employee as emp', 'mi.updated_by', '=', 'emp.nik')
                ->leftJoin('status_category as sc', 'mi.status', '=', 'sc.id')->distinct()
                ->orderBy('mi.created_at', 'DESC')->get();

        $status_categories = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $check_last_active = DB::table('maintenance_information')->where('status', 7)->orderBy('created_at', 'DESC')->first();
        
        
        return view('layouts.main_pages.maintenance_information.maintenance_info', compact('maintenance_info', 'status_categories', 'check_last_active'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.main_pages.maintenance_information.create.maintenance_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'maintenance_information' => 'required',
            'message' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type' => 'array'
        ],
        [
            'maintenance_information.required' => 'Nama Informasi haru diisi',
            'message.required' => 'Pesan Informasi harus diisi',
            'start_date.required' => 'Pilih tanggal awal dahulu',
            'end_date.required' => 'Pilih tanggal akhir'
        ]);
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $maintenance_information = $request->maintenance_information;
        $typeArray = $request->type ?? [];

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $date = Carbon::now()->format('Ymd');
        $infoCode = 'INFMNTNC' . $date . $unique_code;

        foreach($typeArray as $index => $info){
            ModelsMaintenanceInformation::create([
                'info_code' => $infoCode,
                'maintenance_information' => $maintenance_information,
                'message'  => $request->message,
                'start_date' =>  $request->start_date,
                'hour_start' => $request->hour_start,
                'end_date'  => $request->end_date,
                'hour_end' => $request->hour_end,
                'status' => 7,
                'type'=> $typeArray[$index],
                'created_at' => now(),
                'created_by' => $user,
                'updated_by' => null
            ]);
        }


        session()->flash('message_success', 'Data berhasil disimpan!');
        return redirect()->route('maintenance_information.index');
    }


    public function change_status(Request $request)
    {
        $info_code = $request->info_code;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        ModelsMaintenanceInformation::where('info_code', $info_code)->update([
            'status' => $request->status,
            'updated_at' => now(),
            'updated_by' => $user
        ]);
         
        session()->flash('message_success', 'Data berhasil disimpan!');
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
