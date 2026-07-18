<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Services\UserLogActivity;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $v_employee = DB::table('v_employee')->get();
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager', 'Casheer']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        return view('layouts.main_pages.employee.employee_data', compact('v_employee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager', 'Casheer']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $job_position = DB::table('job_position')->get();
        $branch = DB::table('store')->get();
        return view('layouts.main_pages.employee.create.employee_create', compact('job_position', 'branch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|max:16',
            'name' => 'required',
            'address' =>'required',
            'phone_number' => 'required',
            'birth_date' => 'required',
            'email' => 'required',
            'position' => 'required',
            'store' => 'required',
            'start_date' => 'required'
        ], 
        [
            'nik.required' => 'NIK Harus diisi',
            'nik.max' => 'NIK Harus 16 digit',
            'name.required' => 'Nama harus diisi',
            'address.required' =>'Alamat harus diisi',
            'phone_number.required' => 'No.Telepon harus diisi',
            'birth_date.required' => 'Tanggal Lahir harus diisi',
            'email.required' => 'Email harus diisi',
            'position.required' => 'Posisi pekerjaan harus diisi',
            'store.required' => 'Store harus diisi',
            'start_date.required' => 'Tanggal masuk harus diisi'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $data = EmployeeModel::create([
            'nik' => $request->nik,
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'position' => $request->position,
            'store' => $request->store,
            'status' => 7,
            'start_date' => $request->start_date,
            'created_at' => now(),
            'created_by' => $created_by

        ]);

         UserLogActivity::log(
                module: 'Employee',
                method_type: 'CREATE',
                description: "user create employee: {$request->nik}"      
        );

        if($data){
             session()->flash('message_success', 'Data karyawan berhasil disimpan!');
            return redirect()->route('master_employee.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function employee_edit_layout(Request $request)
    {

        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        
        $job_position = DB::table('job_position')->get();
        $branch = DB::table('store')->get();
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
         $request->validate([
            'nik' => 'required|max:16',
            'name' => 'required',
            'address' =>'required',
            'phone_number' => 'required',
            'birth_date' => 'required',
            'email' => 'required',
            'position' => 'required',
            'store' => 'required',
            'start_date' => 'required'
        ], 
        [
            'nik.required' => 'NIK Harus diisi',
            'nik.max' => 'NIK Harus 16 digit',
            'name.required' => 'Nama harus diisi',
            'address.required' =>'Alamat harus diisi',
            'phone_number.required' => 'No.Telepon harus diisi',
            'birth_date.required' => 'Tanggal Lahir harus diisi',
            'email.required' => 'Email harus diisi',
            'position.required' => 'Posisi pekerjaan harus diisi',
            'store.required' => 'Store harus diisi',
            'start_date.required' => 'Tanggal masuk harus diisi'
        ]);

        
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $update_data = DB::table('employee')->where('id', $request->id)->update([
            'nik' => $request->nik,
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'position' => $request->position,
            'store' => $request->store,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'updated_at' => now(),
            'updated_by' => $updated_by
       ]);

         if($update_data){
            UserLogActivity::log(
                module: 'Employee',
                method_type: 'UPDATE',
                description: "user update employee: {$request->nik}"      
            );
             session()->flash('message_success', 'Data karyawan berhasil diperbarui!');
            return redirect()->route('master_employee.index');
        }


    }

    public function update_user_profile(Request $request)
    {
        $request->validate([
            'nik' => 'max:16',
        ],
        [
            'nik.max' => 'NIK Harus 16 digit'
        ]);
    
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $update_data = DB::table('employee')->where('id', $request->id)->update([
            'nik' => $request->nik,
            'name' =>$request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birth_date'=> $request->birth_date,
            'email' => $request->email,
            'updated_at' => now(),
            'updated_by' => $updated_by
       ]);

         if($update_data){
            UserLogActivity::log(
                module: 'Employee',
                method_type: 'CREATE',
                description: "user update employee profil: {$request->nik}"      
            );
             session()->flash('message_success', 'Data berhasil diperbarui!');
            return redirect()->back();
        }


    }

    public function update_password_employee(Request $request) 
    {
        $request->validate([
            'input_email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ],
        [
            'input_email.required' => 'Alamat email harus diisi',
            'password.required' => 'Kata sandi harus diisi',
            'confirm_password.required' => 'Kata sandi harus diisi'
        ]
        );

        $employee_email = $request->input_email;
        $password = $request->password;
        $confirm_password = $request->confirm_password;

        $nik = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $employee_email = DB::table('users')->where('nik', $nik)->where('email', $employee_email)->first();

        if(!$employee_email){
            session()->flash('failed_message', 'Alamat email anda tidak sesuai!');
            return redirect()->back();
        }

        if($confirm_password != $password){
            session()->flash('failed_message', 'Konfirmasi kata sandi tidak sesuai!');
            return redirect()->back();
        }

        if($employee_email)
        { 
            User::where('nik', $nik)->update([
                'password' => Hash::make($password),
                'updated_at' => now()
            ]);
            session()->flash('message_success', 'Berhasil merubah kata sandi!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function employee_nonactive(Request $request)
    {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        if($request->status == 8){
            EmployeeModel::where('nik', $request->nik)->update([
                'status' => $request->status,
                'deleted_at' => now(),
                'deleted_by' => $updated_by,
                'updated_at' => now(),
                'updated_by' => $updated_by
            ]);

            User::where('nik', $request->nik)->update([
                'deleted_at' => now(),
                'deleted_by' => $updated_by,
            ]);


        }elseif($request->status == 7){

            EmployeeModel::where('nik', $request->nik)->update([
                'status' => $request->status,
                'reactivate' => 'Y',
                'reactivate_date' => now(),
                'updated_at' => now(),
                'updated_by' => $updated_by
            ]);

            User::where('nik', $request->nik)->update([
                'reactivate' => 'Y',
                'reactivate_date' => now()
            ]);
        }

        UserLogActivity::log(
                module: 'Employee',
                method_type: 'UPDATE',
                description: "user nonactive employee: {$request->nik}"      
        );

        session()->flash('message_success', 'Data berhasil diperbarui!');
        return redirect()->back();
    }

    public function employee_activity(Request $rq){

        $employee = DB::table('v_employee as e')
        ->select('nik', 'name', 'position_name')
        ->where('nik',$rq->nik)->first();

        $user_session = DB::table('user_log_activities as ula')
            ->select('ula.user', 'ula.method_type', 'ula.ip_address','ula.user_agent', 'ula.activity_date', 'ula.description', 'ula.created_at')
            ->where('user', $employee->nik)
              ->whereIn('ula.method_type', ['LOGIN', 'LOGOUT'])
            ->orderBy('created_at', 'DESC')
            ->get();


       

         $log_activities = DB::table('user_log_activities as ula')
            ->select('ula.module','ula.method_type', 'ula.ip_address','ula.user_agent', 'ula.activity_date', 'ula.description', 'ula.created_at')
            ->where('user',  $employee->nik)
            ->orderBy('created_at', 'DESC')
            ->get();


        return view('layouts.main_pages.employee.employee_activity', compact('employee', 'user_session', 'log_activities'));
    }


}
