<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoucherModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Voucher extends Controller
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
        return view('layouts.main_pages.voucher.create.voucher_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_name'=> 'required',
            'quota'=> 'required',
            'min_transaction'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'voucher_type' => 'required'
        ]);
        
        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 8);

        $voucher_code = 'VOUCHER' . $unique_code;
        $voucher_data_qr_code  = [
            'voucher_code' => $voucher_code,
            'voucher_name' => $request->voucher_name
        ];


       $folderPath = 'qr_voucher';
       if (!Storage::disk('public')->exists($folderPath)) {
        Storage::disk('public')->makeDirectory($folderPath);
        }

        $fileName = uniqid() . '.svg';
        $qrCodePath = $folderPath . '/' . $fileName;
       
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $svgOutput = $writer->writeString(json_encode($voucher_data_qr_code));

        Storage::disk('public')->put($qrCodePath, $svgOutput);

        VoucherModel::create([
            'voucher_code'=> $voucher_code,
            'voucher_name'=> $request->voucher_name,
            'quota'=> $request->quota,
            'nominal'=> $request->nominal,
            'discount'=> $request->discount,
            'min_transaction'=> $request->min_transaction,
            'status'=> 7,
            'voucher_type' => $request->voucher_type,
            'qr_code' => $qrCodePath,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
            'created_by' => $created_by,
            'created_at' => now()

        ]);
        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher');


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
        $vouchers = DB::table('voucher')->where('voucher_code',$request->voucher_code )->first();
        $start_date = Carbon::parse($vouchers->start_date);
        $end_date = Carbon::parse($vouchers->end_date);
        return view('layouts.main_pages.voucher.edit.voucher_edit', compact('vouchers','status', 'start_date', 'end_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        VoucherModel::where('voucher_code', $request->voucher_code)->update([
            'voucher_name'=> $request->voucher_name,
            'quota'=> $request->quota,
            'total_quota_used' =>$request->quota,
            'nominal'=> $request->nominal,
            'discount'=> $request->discount,
            'min_transaction'=> $request->min_transaction,
            'status'=> $request->status,
            'voucher_type' => $request->voucher_type,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
            'updated_by' => $updated_by,
            'updated_at' => now()
        ]);

        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher');
    }


    
    public function update_nonactive_voucher(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username;
        VoucherModel::where('voucher_code', $request->voucher_code)->update([
            'status' => $request->status,
            'updated_by' => $updated_by,
            'updated_at' => now()
        ]);
        session()->flash('message_success', 'Data E-Voucher berhasil disimpan!');
        return redirect()->route('voucher');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
       $voucher = VoucherModel::where('voucher_code',$request->voucher_code)->first();

        if($voucher){
            $voucher->delete();
            $dropQrCode = public_path('storage/' . $voucher->qr_code);
            if (file_exists($dropQrCode)) {
                    unlink($dropQrCode);
                }
        }

        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher');
    }
}
