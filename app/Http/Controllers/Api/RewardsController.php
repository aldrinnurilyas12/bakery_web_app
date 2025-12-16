<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RewardsModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class RewardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('layouts.main_pages.rewards.create.rewards_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rewards_name' => 'required',
            'point' => 'required', 
            'quota' => 'required',
            'images' => 'image|mimes:jpg,png,jpeg,JPG,PNG',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);
        $rewards_code = 'REWARD' . $unique_code;

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        if($request->hasFile('images')){
             $rewards_image = $request->file('images');
             $folderPath = 'rewards';
             $imagePath = $rewards_image->storeAs($folderPath, uniqid() . '.' . $rewards_image->getClientOriginalExtension(), 'public');
        
            RewardsModel::create([
                'rewards_code' => $rewards_code,
                'rewards_name' => $request->rewards_name,
                'point' => $request->point, 
                'quota' => $request->quota,
                'images' => $imagePath,
                'status' => 7,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $created_by,
                'created_at' => now()
            ]);
        }

         session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->route('rewards');
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
    public function edit(string $id, Request $request) : View
    {
        $status = DB::table('status_category')->whereIn('id', ['7', '8'])->get();
        $rewards = DB::table('rewards')->where('rewards_code',$request->rewards_code )->first();
        $start_date = Carbon::parse($rewards->start_date);
        $end_date = Carbon::parse($rewards->end_date);
        return view('layouts.main_pages.rewards.edit.rewards_edit', compact('rewards','status', 'start_date', 'end_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'rewards_name' => 'required',
            'point' => 'required', 
            'quota' => 'required',
            'images' => 'image|mimes:jpg,png,jpeg,JPG,PNG'
        ]);

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;

        $images_old = DB::table('rewards')->select('images')->where('rewards_code', $request->rewards_code)->first();
        if($request->hasFile('images')){
             $rewards_image = $request->file('images');
             $folderPath = 'rewards';
             $imagePath = $rewards_image->storeAs($folderPath, uniqid() . '.' . $rewards_image->getClientOriginalExtension(), 'public');
        
            RewardsModel::where('rewards_code', $request->rewards_code)->update([
                'rewards_name' => $request->rewards_name,
                'point' => $request->point, 
                'quota' => $request->quota,
                'images' => $imagePath,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated_by' => $updated_by,
                'updated_at' => now()
            ]);

            $dropPicture = public_path('storage/' . $images_old->images);
            if(file_exists($dropPicture)){
                unlink($dropPicture);
            }
        }

        session()->flash('message_success', 'Data reward berhasil disimpan!');
        return redirect()->route('rewards');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $rewards = RewardsModel::where('rewards_code', $request->rewards_code)->first();

        if($rewards){
            $rewards->delete();
            $dropPicture = public_path('storage/' . $rewards->images);
            if (file_exists($dropPicture)) {
                    unlink($dropPicture);
                }

        }
        session()->flash('message_success', 'Data reward berhasil dihapus!');
        return redirect()->route('rewards');
    }
}
