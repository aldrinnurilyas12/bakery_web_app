<?php

namespace App\Http\Controllers\Api\MainApp;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class RegisteredCustomer extends Controller
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
    public function create()
    {
        //
    }

    public function login_layouts()
     {
        return view('layouts.main_views.auth.login');
    }

    public function register_layouts()
     {
        return view('layouts.main_views.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|unique:customer,email',
            'birth_date' => 'required',
            'password' => 'required',
            'phone_number' => 'required|numeric|unique:customer,phone_number'
        ],
        [
            'phone_number.unique' => 'Nomor telepon sudah digunakan.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'email.required' => 'alamat Email harus diisi.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'address.required' => 'alamat harus diisi.',
            'name.required' => 'Nama harus diisi',
            'password.required' => 'Kata sandi harus diisi'
             
        ]);

        $date = Carbon::now()->format('ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $customer_code = 'cust'. $date . $unique_code;

        // QR CODE CUSTOMER:
        $customer_data_qr_code  = [
            'customer_code' => $customer_code,
            'name' => $request->name
        ];
        $folderPath = 'qr_customer';
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
        $svgOutput = $writer->writeString(json_encode($customer_data_qr_code));

        Storage::disk('public')->put($qrCodePath, $svgOutput);

        

       $customer = CustomerModel::create([
            'customer_code' => $customer_code,
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'qr_code' => $qrCodePath,
            'member_date' => Carbon::now()->format('y-m-d'),
            'status' => 7,
            'created_at' => now()
        ]);

        $token = $customer->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'data' => $customer,
        //     'token' => $token,
        //     'token_type' => 'Bearer'
        // ]);

        session()->flash('message_success', 'Berhasil daftar akun!');
        return redirect()->route('login_app');
    }


    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
