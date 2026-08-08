<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\App\Models\CustomerModel;
use App\Models\CustomerModel as ModelsCustomerModel;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerNotification;


class GoogleOauthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){

  
        $googleUser = Socialite::driver('google')->user();
        $customer = ModelsCustomerModel::where('email', $googleUser->getEmail())->first();

        $nonactive_account = DB::table('customer')
            ->where('email', $googleUser->getEmail())
            ->where('status', 8)
            ->whereNotNull('deleted_at')->exists();
        
        
        if($nonactive_account){
            session()->flash('failed_message', 'Akun anda sudah tidak aktif');
            return redirect()->route('nonactive-account-information',['email' => $googleUser->getEmail()]);
        }

        if (!$customer) {

            $date = Carbon::now()->format('Ymd');
            $uuid = (string) Str::uuid();
            $unique_code = substr($uuid, 0, 6);
            $customer_code = 'cust'. $date . $unique_code;

             // QR CODE CUSTOMER:
            $customer_data_qr_code  = [
                'customer_code' => $customer_code,
                'name' => $googleUser->getName()
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

            $customer = new ModelsCustomerModel();
            $customer->email = $googleUser->getEmail();
            $customer->name = $googleUser->getName();
            $customer->google_id = $googleUser->getId();
            $customer->customer_code = $customer_code;
            $customer->status = 7;
            $customer->qr_code = $qrCodePath;
            $customer->account_email_verified = 'Y';
            $customer->account_email_verified_at = now();
            $customer->member_date = Carbon::now()->format('d M Y');
            $customer->save();
            CustomerNotification::log(
                customer: $customer_code,
                title: 'Daftar Akun',
                message:'Anda telah berhasil daftar akun!',
                category: 3,
                is_read: 'N'
            );
        }else{
             if (!$customer->google_id) {
                $customer->google_id = $googleUser->getId();
                $customer->save();
            }
        }

        Auth::guard('customer')->login($customer);
        return redirect()->route('home');
    }

}
