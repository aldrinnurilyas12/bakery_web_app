<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BirthdayVoucherNotification;
use App\Models\VoucherCustomer;
use App\Models\VoucherModel;
use App\Models\CustomerNotification as NotificationModel;
use App\Models\CustomerNotificationDetail;
use App\Services\CustomerNotification;
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
use App\Services\UserLogActivity;
use Illuminate\Support\Facades\Mail;

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
    public function create() 
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
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
            'start_date'=> 'required',
            'end_date'=> 'required',
            'voucher_type' => 'required'
        ],
        [
            'voucher_name.required' => 'Nama E-Voucher harus diisi',
            'quota.required' => 'Kuota E-Voucher harus diisi',
            'start_date.required' => 'Tanggal awal Voucher harus diisi',
            'end_date.required' => 'Tanggal akhir Voucher harus diisi',
            'voucher_type.required' => 'Tipe Voucher harus dipilih'
        ]
        );
        
        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
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

         UserLogActivity::log(
                module: 'E-Vouchers',
                method_type: 'CREATE',
                description: "user create new e-voucher: {$request->voucher_name}"      
        );
        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher_data');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    public function redeem_voucher_data(Request $request)
    {
        $redeem_vouchers = DB::table('redeem_voucher as rv')
        ->leftJoin('voucher as v', 'rv.voucher_code', '=', 'v.voucher_code')
        ->leftJoin('customer as c', 'rv.customer', '=', 'c.customer_code')
        ->leftJoin('status_category as sc', 'rv.status', '=', 'sc.id')
        ->leftJoin('store as st', 'rv.store', '=', 'st.store_code')
        ->get();
        

        return view('layouts.main_pages.voucher.redeem_voucher', compact('redeem_vouchers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
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
         $request->validate([
            'voucher_name'=> 'required',
            'quota'=> 'required',
            'min_transaction'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'voucher_type' => 'required'
        ],
        [
            'voucher_name.required' => 'Nama E-Voucher harus diisi',
            'quota.required' => 'Kuota E-Voucher harus diisi',
            'min_transaction.required' => 'masukan minimal transaksi',
            'start_date.required' => 'Tanggal awal Voucher harus diisi',
            'end_date.required' => 'Tanggal akhir Voucher harus diisi',
            'voucher_type.required' => 'Tipe Voucher harus dipilih'
        ]
        );

        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        VoucherModel::where('voucher_code', $request->voucher_code)->update([
            'voucher_name'=> $request->voucher_name,
            'quota'=> $request->quota,
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
         UserLogActivity::log(
                module: 'E-Vouchers',
                method_type: 'UPDATE',
                description: "user update e-voucher: {$request->voucher_name}"      
        );

        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher');
    }


    
    public function update_nonactive_voucher(Request $request) {
        $updated_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        VoucherModel::where('voucher_code', $request->voucher_code)->update([
            'status' => $request->status,
            'updated_by' => $updated_by,
            'updated_at' => now()
        ]);
        UserLogActivity::log(
                module: 'E-Vouchers',
                method_type: 'UPDATE',
                description: "user nonactive e-voucher: {$request->voucher_code}"      
        );
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

         UserLogActivity::log(
                module: 'E-Vouchers',
                method_type: 'DELETE',
                description: "user delete e-voucher: {$request->voucher_code}"      
        );

        session()->flash('message_success', 'Data Voucher berhasil disimpan!');
        return redirect()->route('voucher');
    }




    public function customer_voucher_birthday(Request $rq){

        $vouchers = DB::table('v_vouchers')->where('voucher_type', 'birth_day')
        ->where(function ($query) {
                $query->whereMonth('start_date', 7)
                    ->orWhereMonth('end_date', 7);
            })
        ->where('total_available', '>', 0)
        ->get();


        $customers_data = DB::table('v_customer_birthday_vouchers')->get();

        $check_voucher_shared = DB::table('customer_vouchers as cv')
                        ->first();
        return view('layouts.main_pages.voucher.birthday_vouchers.customer_birthday', compact('customers_data', 'vouchers', 'check_voucher_shared'));
    }


    public function voucher_birthday_shared(Request $request)
    {
        $request->validate([
            'customer'     => 'array',
            'voucher_code' => 'required',
        ]);

        $customers = $request->customer ?? [];
        $voucherCode = $request->voucher_code;
        $folderPath = 'qrcode_voucher_customer';
        $check_voucher_shared = DB::table('customer_vouchers as cv')
        ->whereIn('customer', $customers)
        ->distinct()
        ->pluck('customer')->toArray();


        $checkVoucher = DB::table('v_vouchers')
        ->where('voucher_code', $voucherCode)->first();
        $voucherExpired = DB::table('v_vouchers')
        ->where('voucher_code', $voucherCode)->where('status', 7)->value('end_date');

        if($checkVoucher->total_available == 0){
            session()->flash('failed_message', 'Kuota Voucher ini telah habis.');
            return redirect()->back();
        }

        if($voucherExpired && Carbon::now()->gt(Carbon::parse($voucherExpired)) ){
            session()->flash('failed_message', 'Voucher ini telah Expired.');
            return redirect()->back();
        }


        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        

        $data = [];

        foreach ($customers as $customer) {

            $customerData = DB::table('customer')
                ->select('customer_code', 'name')
                ->where('customer_code', $customer)
                ->first();

            if (!$customerData) {
                continue;
            }

            // Generate voucher customer
            $customerVoucher = 'VOUCHER-BIRTHDAY'
                . now()->format('Ymd')
                . strtoupper(substr((string) Str::uuid(), 0, 5));

            // Generate QR Code
            $voucherDataQrCode = [
                'voucher_code' => $customerVoucher,
            ];

            $fileName = uniqid() . '.svg';
            $qrCodePath = $folderPath . '/' . $fileName;

            $svgOutput = $writer->writeString(json_encode($voucherDataQrCode));
            Storage::disk('public')->put($qrCodePath, $svgOutput);

            // Kumpulkan voucher untuk bulk insert
            
            $data[] = [
                'customer'              => $customer,
                'voucher'               => $voucherCode,
                'customer_voucher_code' => $customerVoucher,
                'voucher_path'          => $qrCodePath,
                'status'                => 7,
                'voucher_used'          => 'N',
                'expired_date'          => now()->addDays(25),
                'created_at'            => now()
            ];

            

        }

      
            foreach ($data as $voucher) {

                    $customerName = DB::table('customer')->select('name', 'email')->where('customer_code', $voucher['customer'])->first();
                    $voucher_name = DB::table('voucher')->where('voucher_code', $voucherCode)->first();
                    if(in_array($voucher['customer'], $check_voucher_shared)){
                        continue;
                    }
                    
                    $notification = NotificationModel::create([
                        'customer' => $voucher['customer'],
                        'title' => 'Selamat Ulang Tahun!',
                        'message' => 'Selamat ulang tahun! Selamat Anda mendapatkan E-Voucher dari kami.',
                        'category' => 6,
                        'is_read' => 'N'
                    ]);

                    CustomerNotificationDetail::create([
                        'notif' => $notification->id,
                        'voucher_birthday' => $voucher['customer_voucher_code'],
                    ]);
                    
                    Mail::to($customerName->email)->sendNow(new BirthdayVoucherNotification([
                            'name' => $customerName->name,
                            'voucher' => $voucher_name->voucher_name
                    ]));

                    $insertData[] = $voucher;
                    
            }

            if (!empty($insertData)) {
                VoucherCustomer::insert($insertData);
            } 


        session()->flash('message_success', 'Voucher berhasil dibagikan ke seluruh pelanggan.');
        return redirect()->back();
    }

    public function share_voucher_only_customer(Request $request){
            $request->validate([
                'voucher_code' => 'required',
            ]);

            $customer = $request->customer ?? [];
            $voucherCode = $request->voucher_code;
            $folderPath = 'qrcode_voucher_customer';
            $customerName = DB::table('customer')->select('name', 'email')->where('customer_code', $customer)->first();
            $check_voucher_shared = DB::table('customer_vouchers as cv')->where('customer', $customer)->first();
            $checkVoucher = DB::table('v_vouchers')
            ->where('voucher_code', $voucherCode)->first();
            $voucherExpired = DB::table('v_vouchers')
            ->where('voucher_code', $voucherCode)->where('status', 7)->value('end_date');

            if($check_voucher_shared){
                session()->flash('failed_message', "Voucher sudah dibagikan ke pelanggan {$customerName->name}" );
                return redirect()->back();
            }

            if($checkVoucher->total_available == 0){
                session()->flash('failed_message', 'Kuota Voucher ini telah habis.');
                return redirect()->back();
            }

            if($voucherExpired && Carbon::now()->gt(Carbon::parse($voucherExpired)) ){
                session()->flash('failed_message', 'Voucher ini telah Expired.');
                return redirect()->back();
            }


            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            $renderer = new ImageRenderer(
                new RendererStyle(400),
                new SvgImageBackEnd()
            );

            $writer = new Writer($renderer);

            $customerVoucher = 'VOUCHER-BIRTHDAY'
                . now()->format('Ymd')
                . strtoupper(substr((string) Str::uuid(), 0, 5));

            // Generate QR Code
            $voucherDataQrCode = [
                'voucher_code' => $customerVoucher,
            ];

            $fileName = uniqid() . '.svg';
            $qrCodePath = $folderPath . '/' . $fileName;

            $svgOutput = $writer->writeString(json_encode($voucherDataQrCode));
            Storage::disk('public')->put($qrCodePath, $svgOutput);
        

            if(!$check_voucher_shared){
                VoucherCustomer::create([
                    'customer'              => $customer,
                    'voucher'               => $voucherCode,
                    'customer_voucher_code' => $customerVoucher,
                    'voucher_path'          => $qrCodePath,
                    'status'                => 7,
                    'voucher_used'          => 'N',
                    'expired_date'          => now()->addDays(25),
                    'created_at'            => now()
                ]);

                $voucher_name = DB::table('voucher')->where('voucher_code', $voucherCode)->first();
            
               
                    
                $notification = NotificationModel::create([
                        'customer' => $customer,
                        'title' => 'Selamat Ulang Tahun!',
                        'message' => 'Selamat ulang tahun! Selamat Anda mendapatkan E-Voucher dari kami.',
                        'category' => 6,
                        'is_read' => 'N'
                    ]);

                    CustomerNotificationDetail::create([
                        'notif' => $notification->id,
                        'voucher_birthday' => $customerVoucher,
                    ]);


                    Mail::to($customerName->email)->sendNow(new BirthdayVoucherNotification([
                            'name' => $customerName->name,
                            'voucher' => $voucher_name->voucher_name
                    ]));
            }

            session()->flash('message_success', "Voucher berhasil dibagikan ke pelanggan {$customerName->name}" );
            return redirect()->back();


    }
}
