<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
use App\Models\User;
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

    public function show_users_register() : View {

        $employee = DB::table('v_employee')->select('nik', 'name')->get();
         return view('layouts.main_pages.users.create.users_create_account', compact('employee'));
    }

    public function master_main_users() : View {
        $v_users = DB::table('v_users')->get();
         return view('layouts.main_pages.users.users_data', compact('v_users'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class]
        ]);


        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $user = User::create([
            'nik' => $request->nik,
            'username' => $request->username,
            'email' => $request->email,
            'is_active' => 'Y',
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'created_by' => $created_by
            
        ]);

        if($user){
            session()->flash('message_success', 'Berhasil daftar akun!');
            return redirect()->back();
        }

    }

    public function edit_users_layout(Request $request): View {
         $v_users = DB::table('v_users')->where('nik', $request->nik)->get();
         return view('layouts.main_pages.users.edit.users_edit', compact('v_users'));
    }

    public function update(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $user_update =  DB::table('users')->where('nik', $request->nik)->update([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
            'updated_by' => $updated_by
        ]);

         if($user_update){
            session()->flash('message_success', 'Data pengguna berhasil diperbarui!');
            return redirect()->route('users_data');
        }
    }

    public function update_user_active(Request $request) {
        User::where('nik', $request->nik)->update([
            'is_active' => $request->is_active
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
}
