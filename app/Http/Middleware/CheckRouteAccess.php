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

        $ALLOWED_USER = in_array($position, [
            'IT Developer',
            'Manager',
            'Supervisor'
        ]);


        $routeName = $request->segment(1);
        $route = DB::table('submenu')->where('submenu_link', $routeName)->first();
        $allowed_route = DB::table('submenu')
                ->where('allow_access_outside_operational_hours', 'Y')
                ->pluck('submenu_link')
                ->toArray();
        $operational_hours = Carbon::now('Asia/Jakarta')->hour;
        $module_documentation = ModuleDocumentation::where('url_path', $routeName)->first();

        if($CUSTOMER_ENV){
            return $next($request);
        }

        if (!$ALLOWED_USER) {
            // termasuk yang tidak punya position_name (global env)

            if (!in_array($routeName, $allowed_route)) {
                if ($route && $route->status == 8) {
                    session()->flash('failed_message', 'Tidak bisa akses');
                    return redirect()->back();
                }

                if($operational_hours < 9  || $operational_hours > 21){
                session()->flash('failed_message', 'Maaf tidak bisa akses, Jam operasional : 08:00 s.d 21:00.');
                return redirect()->back();
                }
             }
        }

        View::share('module_documentation', $module_documentation);
        return $next($request);
    }
}
