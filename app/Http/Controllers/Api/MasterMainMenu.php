<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterMainMenuModel;
use App\Models\MasterSubMenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Services\UserLogActivity;

class MasterMainMenu extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }
        $main_menu = DB::table('main_menu')->get();
        return view('layouts.main_pages.master_menu.main_menu', compact('main_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }
        return view('layouts.main_pages.master_menu.create.main_menu_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required',
            'location' => 'required',
            'icon' => 'required',
            'description' => 'required'
        ],
        [
            'menu_name.required' => 'Nama menu utama harus diisi',
            'location.required' => 'Lokasi menu utama harus diisi',
            'icon.required' => 'Icon harus diisi',
            'description.required' => 'Deskripsi harus diisi'
        ]);

        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        MasterMainMenuModel::create([
            'menu_name' => $request->menu_name,
            'location' => $request->location,
            'icon' => $request->icon,
            'status' => 7,
            'description' => $request->description
        ]);

        UserLogActivity::log(
                module: 'Main Menu Admin',
                method_type: 'CREATE',
                description: "user create main menu: {$request->menu_name}"      
        );

        session()->flash('message_success', 'Berhasil menambahkan Menu Utama!');
        return redirect()->route('master_main_menu.index');
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
    public function main_menu_edit(string $id, Request $request)
    {
        $request->validate([
            'menu_name' => 'required',
            'location' => 'required',
            'icon' => 'required',
            'description' => 'required'
        ],
        [
            'menu_name.required' => 'Nama menu utama harus diisi',
            'location.required' => 'Lokasi menu utama harus diisi',
            'icon.required' => 'Icon harus diisi',
            'description.required' => 'Deskripsi harus diisi'
        ]);

        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        MasterMainMenuModel::where('id', $request->id)->update([
            'menu_name' => $request->menu_name,
            'location' => $request->location,
            'icon' => $request->icon,
            'status' => $request->status,
            'description' => $request->description
        ]);

        UserLogActivity::log(
                module: 'Main Menu Admin',
                method_type: 'UPDATE',
                description: "user update main menu: {$request->menu_name}"      
        );

        session()->flash('message_success', 'Berhasil perbarui data Menu Utama!');
        return redirect()->route('master_main_menu.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $main_menu = MasterMainMenuModel::where('id', $request->id)->first();
        return view('layouts.main_pages.master_menu.edit.main_menu_edit', compact('main_menu','status'));
    }

    public function submenu_list(Request $request)
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

       $submenu = DB::table('submenu as s')->select('mm.id','mm.menu_name','s.id as submenu_id', 's.submenu_name','s.submenu_link','s.allow_access_outside_operational_hours', 's.icon','s.status','s.description', 's.created_at','s.updated_at')
                ->leftJoin('main_menu as mm', 's.main_menu', '=', 'mm.id')
                ->where('s.main_menu', $request->id)->get();
                
        $main_menu_id = DB::table('main_menu')->where('id', $request->id)->first();

        return view('layouts.main_pages.master_menu.submenu',compact('submenu', 'main_menu_id'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function submenu_delete(string $id)
    {
        $submenu_id = MasterSubMenuModel::find($id);

        if($submenu_id){
            UserLogActivity::log(
                module: 'Submenu Admin',
                method_type: 'DELETE',
                description: "user delete submenu: {$submenu_id->submenu_name}"      
            );
            $submenu_id->delete();
            session()->flash('message_success', 'Berhasil hapus submenu!');
            return redirect()->back();
        }
    }


    public function submenu_create(Request $request)
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        $main_menu = DB::table('main_menu as m')->where('m.id', $request->id)->first();
        return view('layouts.main_pages.master_menu.create.submenu_create',compact('main_menu'));
    }

    public function submenu_save(Request $request)
    {
        $request->validate([
            'submenu_name' => 'required',
            'submenu_link' => 'required',
            'main_menu' => 'required',
            'icon' => 'required',
            'type' => 'required',
            'description' => 'required',
            'allow_access_outside_operational_hours' => 'required'
        ],
        [
            'submenu_name.required' => 'Nama Submenu harus diisi',
            'submenu_link.required' => 'Link Submenu harus diisi',
            'main_menu.required' => 'Menu utama harus diisi',
            'type.required' => 'Tipe Submenu harus diisi',
            'icon.required' => 'Icon harus diisi',
            'description.required' => 'Deskripsi submenu harus diisi',
            'allow_access_outside_operational_hours.required' => 'Harus dipilih'
        ]);

        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }

        MasterSubMenuModel::create([
            'submenu_name' => $request->submenu_name,
            'submenu_link' => $request->submenu_link,
            'type' => $request->type,
            'main_menu' => $request->main_menu,
            'icon' => $request->icon,
            'description' => $request->description,
            'status' => 7,
            'allow_access_outside_operational_hours' => $request->allow_access_outside_operational_hours
        ]);

        UserLogActivity::log(
                module: 'Submenu Admin',
                method_type: 'CREATE',
                description: "user create submenu: {$request->submenu_name}"      
        );

        session()->flash('message_success', 'Berhasil menambahkan Submenu');
        return redirect()->route('submenu_list', ['id' => $request->main_menu]);
    }

    public function submenu_update(Request $request)
    {
        $authSession =  (app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name == 'IT Developer');

        if(!$authSession){
             session()->flash('failed_message', 'Anda tidak bisa akses ini!');
            return redirect()->back();
        }
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $submenu = DB::table('submenu as s')
        ->leftJoin('main_menu as mm', 's.main_menu', '=', 'mm.id')
        ->select('s.id as submenu_id', 's.submenu_name','s.submenu_link','s.allow_access_outside_operational_hours','s.type', 's.icon','s.status', 's.description','mm.id', 'mm.menu_name', 's.updated_at')
        ->where('s.id', $request->submenu_id)
        ->first();
        return view('layouts.main_pages.master_menu.edit.submenu_edit', compact('submenu', 'status'));
    }
    public function submenu_edit(Request $request)
    {
        $request->validate([
            'submenu_name' => 'required',
            'submenu_link' => 'required',
            'main_menu' => 'required',
            'icon' => 'required',
            'description' => 'required'
        ],
        [
            'submenu_name.required' => 'Nama Submenu harus diisi',
            'submenu_link.required' => 'Link Submenu harus diisi',
            'main_menu.required' => 'Menu utama harus diisi',
            'icon.required' => 'Icon harus diisi',
            'description.required' => 'Deskripsi submenu harus diisi'
        ]);

        MasterSubMenuModel::where('id', $request->id)->update([
            'submenu_name' => $request->submenu_name,
            'submenu_link' => $request->submenu_link,
            'type' => $request->type,
            'icon' => $request->icon,
            'description' => $request->description,
            'status' => $request->status,
            'allow_access_outside_operational_hours' => $request->allow_access_outside_operational_hours
        ]);

        UserLogActivity::log(
                module: 'Submenu Admin',
                method_type: 'UPDATE',
                description: "user update submenu: {$request->submenu_name}"      
        );
        session()->flash('message_success', 'Berhasil perbarui Submenu');
         return redirect()->route('submenu_list', ['id' => $request->main_menu]);
    }

    public function submenu_change_status(Request $rq, $id){

        DB::table('submenu')->where('main_menu',$id)->update([
            'status' => $rq->status,
        ]);

        UserLogActivity::log(
                module: 'Submenu Admin',
                method_type: 'UPDATE',
                description: "user update submenu status"      
        );

        session()->flash('message_success', 'Berhasil perbarui Submenu');
         return redirect()->back();
    }

     public function submenu_update_status(Request $rq){

        DB::table('submenu')->update([
            'status' => $rq->status,
        ]);

        UserLogActivity::log(
                module: 'Submenu Admin',
                method_type: 'UPDATE',
                description: "user update submenu status"      
        );

        session()->flash('message_success', 'Berhasil perbarui Submenu');
         return redirect()->back();
    }

    public function user_role_permission(Request $rq){

        $role = DB::table('role')->get();
        $submenu = DB::table('submenu')->where('main_menu', '<>', 6)->orderBy('submenu_name', 'ASC')->get();
        $permissions = DB::table('user_permission_access')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->submenu . '_' . $item->role => true];
        });


        return view('layouts.main_pages.role_user_permission.role_permission', compact('role', 'submenu', 'permissions'));
    }

    public function permission_save(Request $request){
      
        DB::beginTransaction();

        try {

            // Hapus semua permission lama
            DB::table('user_permission_access')->truncate();

            $data = [];

            foreach ($request->permission ?? [] as $submenuId => $roles) {

                foreach ($roles as $roleId) {

                    $data[] = [
                        'submenu' => $submenuId,
                        'role'    => $roleId,
                        'created_at' => now()
                    ];
                }
            }

            if (!empty($data)) {
                DB::table('user_permission_access')->insert($data);
            }
            session()->flash('message_success', 'Perubahan berhasil disimpan');
            return redirect()->back();

            DB::commit();
            

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }


    }
    
}
