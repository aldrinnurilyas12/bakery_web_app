<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmployeeModel;
use App\Models\ShopModel;
use App\Models\User;
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
        ->select('ve.nik', 've.name')->get();

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
       $emp = DB::table('employee as e')->select('e.email', 'u.username')
                ->leftJoin('users as u', 'e.nik', '=', 'u.nik')->where('e.nik', $emp_nik)->first();
        return response()->json([
            'email'=> $emp->email,
            'username' => $emp->username
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

        $users_exists = DB::table('users')->where('nik', $nik)->exists();
        $checking_role_exist = DB::table('users_role')->where('role', $role)->where('user', $nik)->exists();

        if($checking_role_exist) {
             session()->flash('failed_message', 'Role dengan akun ini sudah ada silahkan coba lagi!');
            return redirect()->back();
        }

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
       

       if(!$users_exists){
            $user = User::create([
                'nik' => $nik,
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => 'Y',
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'created_by' => $created_by
                
            ]);

            $user_role = UsersRole::create([
                'role' => $role,
                'user' => $user->nik
            ]);
       }else{
            $user_role = UsersRole::create([
                'role' => $role,
                'user' => $request->nik
            ]);
       }

        session()->flash('message_success', 'Berhasil daftar akun!');
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
            'nik' => 'required',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required'
        ],
        [
            'nik.required' => 'Pilih Karyawan dahulu',
            'username.required' => 'Username harus diisi',
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

         
            session()->flash('message_success', 'Data pengguna berhasil diperbarui!');
            return redirect()->route('users_data');
        
    }

    public function update_user_active(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        
        User::where('nik', $request->nik)->update([
            'is_active' => $request->is_active,
            'updated_at' => now(),
            'updated_by' =>  $updated_by
        ]);

        session()->flash('message_success', 'Data pengguna berhasil diperbaui!');
        return redirect()->back();
    }

    public function destroy($id) {
        $user = User::find($id);
        
        if($user) {
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
            $user_role->delete();
            session()->flash('message_success', 'Data Role pengguna berhasil dihapus!');
            return redirect()->back();
        }else{
            session()->flash('failed_message', 'Data pengguna gagal dihapus!');
            return redirect()->back();
        }
    }
}
