<?php

namespace App\Http\Middleware;

use App\Models\MasterSubMenuModel;
use App\Models\ModuleDocumentation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;


class CheckRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $CUSTOMER_ENV =  app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getCustomer();
        $position = $GLOBAL_ENV->position_name ?? null;
        $IT_GUY = $position === 'IT Developer';

        $routeName = $request->segment(1);
        $module_documentation = ModuleDocumentation::where('url_path', $routeName)->first();

        $maintenance_info = $maintenance_info = DB::table('maintenance_information')
        ->where('status', 7)
        ->where('type', 'admin_web')
        ->orderBy('created_at', 'DESC')
        ->exists();

        $maintenance_info_cust = DB::table('maintenance_information')
        ->where('status', 7)
        ->where('type', 'customer_web')
        ->orderBy('created_at', 'DESC')
        ->exists();

        $role = $GLOBAL_ENV->role_id ?? null;

        if(!$role){
            if($maintenance_info_cust){
                 Auth::guard('customer')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                session()->flash('failed_message', 'Maaf saat ini anda tidak bisa akses '); 
                return redirect()->route('login_app');
            }
              return $next($request);
        }


        if($CUSTOMER_ENV){
            if($maintenance_info_cust){
                 Auth::guard('customer')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                session()->flash('failed_message', 'Maaf saat ini anda tidak bisa akses '); 
                return redirect()->route('login_app');
            }
            return $next($request);
        }
    
        if(!$IT_GUY){
            
            if($maintenance_info){
                session()->flash('failed_message', 'Maaf saat ini anda tidak bisa akses Module pada website ini'); 
                return redirect()->route('dashboard_main');
            }
            
        }


    
        $routeId = DB::table('submenu')
        ->where('submenu_link', $routeName)
        ->pluck('id');
        $hasPermission = DB::table('user_permission_access')
                    ->where('submenu', $routeId)
                    ->where('role', $role)
                    ->exists();

        // dd($hasPermission);

        

        if(!$hasPermission){
            session()->flash('failed_message', 'Maaf anda tidak bisa akses menu ini'); 
            return redirect()->route('dashboard_main');
        }


        View::share('module_documentation', $module_documentation);
        return $next($request);
    }
}
