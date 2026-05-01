<?php

namespace App\Http\Middleware;

use App\Models\MasterSubMenuModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        // $IT_GUY = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->position_name == 'IT Developer';
        // $routeName = $request->segment(1);
        // $route = DB::table('submenu')->where('submenu_link', $routeName)->first();

        // if(!$IT_GUY){
        //     if($route && $route->status === 8){
        //         session()->flash('failed_message', 'Tidak bisa akses');
        //         return redirect()->back();
        //     }
        // }

        // return $next($request);

        $GLOBAL_ENV = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();

        $position = $GLOBAL_ENV->position_name ?? null;

        $IT_GUY = $position === 'IT Developer';

        $routeName = $request->segment(1);
        $route = DB::table('submenu')->where('submenu_link', $routeName)->first();

        if (!$IT_GUY) {
            // termasuk yang tidak punya position_name (global env)
            if ($route && $route->status == 8) {
                session()->flash('failed_message', 'Tidak bisa akses');
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
