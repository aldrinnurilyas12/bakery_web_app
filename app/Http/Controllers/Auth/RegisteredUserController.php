<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationUsersAccount;
use App\Models\EmployeeModel;
use App\Models\ShopModel;
use App\Models\User;
use App\Models\UserLogActivities;
use App\Models\UsersRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Services\UserLogActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    // public function create(): View
    // {
    //     return view('views.layouts.main_pages.users.create.users_create');
    // }

    public function show_users_register() {

        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        $employee = DB::table('v_employee as ve')
        ->leftJoin('users as u', 've.nik', '=', 'u.nik')
        ->select('ve.nik', 've.name', 've.position_name')->get();

        $role = DB::table('role')->get();
         return view('layouts.main_pages.users.create.users_create_account', compact('employee', 'role'));
    }

    public function master_main_users()  {

        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        $v_users = DB::table('v_users')->get();

        $master_users = DB::table('users as us')
                ->leftJoin('employee as e', 'us.nik', '=', 'e.nik')
                ->leftJoin('store as st', 'e.store','=', 'st.id')->get();

        $role = DB::table('users_role as ur')->select('ur.id as user_role_id','ur.role as role_id','r.role as role_name', 'ur.user','ur.created_at', 'ur.updated_at')
                ->leftJoin('role as r', 'ur.role', '=', 'r.id')->get();
         return view('layouts.main_pages.users.users_data', compact('v_users', 'master_users', 'role'));
    }

    public function get_email($emp_nik)
    {
       $emp = DB::table('employee as e')->select('e.email', 'u.username',  DB::raw("DATE_FORMAT(e.birth_date, '%d%m%Y') as birth_date"))
                ->leftJoin('users as u', 'e.nik', '=', 'u.nik')->where('e.nik', $emp_nik)->first();
        return response()->json([
            'email'=> $emp->email,
            'username' => $emp->username,
            'birth_date' => $emp->birth_date
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'nik' => 'required',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required'
        ],
        [
            'nik.required' => 'Pilih Karyawan dahulu',
            'username.required' => 'Username harus diisi',
            'email.required' => 'Alamat Email harus diisi',
            'role.required' => 'Pilih Role dahulu',
            'password' => 'Kata sandi tidak boleh kosong'
        ]);
      
        $role = $request->role;
        $nik = $request->nik;
        // $birth_date = Carbon::parse($request->birth_date)->format('Ymd');

        $users_exists = DB::table('users')->where('nik', $nik)->exists();
        $checking_role_exist = DB::table('users_role')->where('role', $role)->where('user', $nik)->exists();

        if($checking_role_exist) {
             session()->flash('failed_message', 'Role dengan akun ini sudah ada silahkan coba lagi!');
            return redirect()->back();
        }

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
       

       if(!$users_exists){
            $data = User::create([
                'nik' => $nik,
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => 8,
                'account_verified' => 'N',
                'account_verified_at' => null,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'created_by' => $created_by
                
            ]);

            $user_role = UsersRole::create([
                'role' => $role,
                'user' => $data->nik
            ]);
       }else{
            $user_role = UsersRole::create([
                'role' => $role,
                'user' => $request->nik
            ]);
       }

        Mail::to($request->email)->sendNow(new VerificationUsersAccount([
            'email' => $data->email,
            'nik' => $data->nik,
            'name' => $data->name
        ]));

       UserLogActivity::log(
                module: 'User',
                method_type: 'CREATE',
                description: "user create new user: {$data->nik}"      
        );

        session()->flash('message_success', 'Berhasil daftar akun, silahkan aktivasi akun melalui Email.');
        return redirect()->back();

    }

    public function edit_users_layout(Request $request) {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }
         $v_users = DB::table('v_users')->where('nik', $request->nik)->get();
           $role = DB::table('role')->get();
         return view('layouts.main_pages.users.edit.users_edit', compact('v_users', 'role'));
    }

    public function update(Request $request) {

         $request->validate([
            'email' => 'required',
            'role' => 'required'
        ],
        [
            'email.required' => 'Alamat Email harus diisi',
            'role.required' => 'Pilih Role dahulu'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $user =  DB::table('users')->where('nik', $request->nik)->update([
            'username' => $request->username,
            'email' => $request->email,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

        $user_role = UsersRole::where('user', $request->nik)->update([
            'role' => $request->role
        ]);

        UserLogActivity::log(
                module: 'User',
                method_type: 'UPDATE',
                description: "user update user: {$request->nik}"      
        );

         
            session()->flash('message_success', 'Data pengguna berhasil diperbarui!');
            return redirect()->route('users_data');
        
    }

    public function update_user_active(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        
        if($request->is_active == 8){
            User::where('nik', $request->nik)->update([
                'is_active' => $request->is_active,
                'updated_at' => now(),
                'updated_by' =>  $updated_by,
                'deleted_at' => now(),
                'deleted_by' => $updated_by
            ]);
        }elseif($request->is_active == 7){
            User::where('nik', $request->nik)->update([
                'is_active' => $request->is_active,
                'updated_at' => now(),
                'updated_by' =>  $updated_by,
                'reactivate' => 'Y',
                'reactivate_date' => now()
             ]);
        }


        UserLogActivity::log(
                module: 'User',
                method_type: 'UPDATE',
                description: "user update active: {$request->nik}"      
        );

        session()->flash('message_success', 'Data pengguna berhasil diperbaui!');
        return redirect()->back();
    }

     public function account_verification($nik)
    {

        User::where('nik', $nik)->update([
            'account_verified' => 'Y',
            'account_verified_at' => now(),
            'is_active' => 7,
            'updated_at' => now()
        ]);
        session()->flash('message_success', 'Akun berhasil diverifikasi, silahkan login kembali.');
        return redirect()->route('login_kencana_bakery');
    }

    public function log_users_activities(Request $request)
    {
        $user_log = DB::table('user_log_activities as ula')->leftJoin('employee as e', 'ula.user', '=', 'e.nik')->orderBy('ula.created_at', 'DESC')->get();

        return view('layouts.main_pages.users.user_log_activities', compact('user_log'));
    }

    public function destroy($id) {
        $user = User::find($id);
        
        if($user) {
            UserLogActivity::log(
                module: 'User',
                method_type: 'DELETE',
                description: "user delete admin account: {$user->nik}"      
            );
            $user->delete();
             session()->flash('message_success', 'Data pengguna berhasil dihapus!');
            return redirect()->back();

        }else{
            abort(404, 'Data not found');
            return redirect()->back();
        }
    }

    public function delete_role(Request $request){
       $user_role = UsersRole::where('role', $request->user_role_id)->first();

        if($user_role){
            UserLogActivity::log(
                module: 'USER',
                method_type: 'DELETE',
                description: "user delete Role"      
            );
            $user_role->delete();
            session()->flash('message_success', 'Data Role pengguna berhasil dihapus!');
            return redirect()->back();
        }else{
            session()->flash('failed_message', 'Data pengguna gagal dihapus!');
            return redirect()->back();
        }
    }
}
