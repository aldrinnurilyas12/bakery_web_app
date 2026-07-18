<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CustomerModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\UserLogActivities;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        return $request->authenticate();
    }

    public function request_sign_in(LoginRequest $request) : RedirectResponse
    {
        return $request->customer_login_request();
    }

    public function getCustomer()
    {
        $user = auth()->guard('customer')->user();
        $customer = CustomerModel::where('id', $user?->id)->first();

        return $customer;
    }


    public function getUsers()
    {

        $user = auth()->user();
        $users_data = DB::table('users as u')
                        ->select('e.id', 'e.nik','e.name', 'u.id', 'u.username', 'st.id as store_id','st.store_code',
                            'st.store_name', 'r.role as role_name', 'jp.position_name','r.id as role_id',
                            DB::raw("
                                    CONCAT(
                                        LEFT(SUBSTRING_INDEX(e.name, ' ', 1), 1),
                                        LEFT(SUBSTRING_INDEX(e.name, ' ', -1), 1)
                                    )
                                as inisial"))
                        ->leftJoin('employee as e','u.nik', '=', 'e.nik')
                        ->leftJoin('store as st', 'e.store', '=', 'st.id')
                        ->leftJoin('users_role as ur', 'u.nik', '=', 'ur.user')
                        ->leftJoin('role as r', 'ur.role', '=', 'r.id')
                        ->leftJoin('job_position as jp', 'e.position','=', 'jp.position_code')
                        ->where('u.id', $user?->id)->first();
        return $users_data;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
       $nik = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        
         UserLogActivities::create([
            'user' => $nik,
            'module' => 'Session Logout',
            'method_type' => 'LOGOUT',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'activity_date' => now(),
            'description' => 'User Logout Web'
        ]);


        return redirect('login');
    }

     public function logout_session_account(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login_app');
    }
}
