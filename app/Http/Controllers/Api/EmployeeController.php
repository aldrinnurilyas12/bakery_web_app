<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeModel;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $v_employee = DB::table('v_employee')->get();

        return view('layouts.main_pages.employee.employee_data', compact('v_employee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {

        $job_position = DB::table('job_position')->get();
        $branch = DB::table('branch')->get();
        return view('layouts.main_pages.employee.create.employee_create', compact('job_position', 'branch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'position' => 'required',
            'branch' => 'required'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $data = EmployeeModel::create([
            'nik' => $request->nik,
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'position' => $request->position,
            'branch' => $request->branch,
            'status' => 7,
            'start_date' => $request->start_date,
            'created_at' => now(),
            'created_by' => $created_by

        ]);

        if($data){
             session()->flash('message_success', 'Data karyawan berhasil disimpan!');
            return redirect()->route('master_employee.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function employee_edit_layout(Request $request) : View
    {
        $job_position = DB::table('job_position')->get();
        $branch = DB::table('branch')->get();
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $employee = DB::table('v_employee')->where('nik', $request->nik)->first();
        $birth_date = Carbon::parse($employee->birth_date);
        $start_date = Carbon::parse($employee->start_date);
        return view('layouts.main_pages.employee.edit.employee_edit', compact('employee', 'branch', 'job_position', 'birth_date', 'start_date', 'status'));
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
    public function update(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $update_data = DB::table('employee')->where('nik', $request->nik)->update([
            'nik' => $request->nik,
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'position' => $request->position,
            'branch' => $request->branch,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'updated_at' => now(),
            'updated_by' => $updated_by
       ]);

         if($update_data){
             session()->flash('message_success', 'Data karyawan berhasil diperbarui!');
            return redirect()->route('master_employee.index');
        }


    }

    public function update_user_profile(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $update_data = DB::table('employee')->where('nik', $request->nik)->update([
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'updated_at' => now(),
            'updated_by' => $updated_by
       ]);

         if($update_data){
             session()->flash('message_success', 'Data berhasil diperbarui!');
            return redirect()->back();
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function employee_nonactive(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        EmployeeModel::where('nik', $request->nik)->update([
            'status' => $request->status,
            'deleted_at' => now(),
            'deleted_by' => $updated_by
        ]);

        session()->flash('message_success', 'Data berhasil diperbarui!');
        return redirect()->back();
    }
}
