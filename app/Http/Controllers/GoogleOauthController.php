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


class GoogleOauthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){

        $date = Carbon::now()->format('ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $customer_code = 'cust'. $date . $unique_code;
        $googleUser = Socialite::driver('google')->user();

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

        

        $user = ModelsCustomerModel::updateOrCreate([
            'email'=> $googleUser->getEmail(),

        ], 
        [
            'customer_code' => $customer_code,
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'status' => 7,
            'qr_code' => $qrCodePath,
            'member_date' => Carbon::now()->format('y-m-d'),
            'account_email_verified' => 'Y',
            'account_email_verified_at' => now()
        ]);

        Auth::guard('customer')->login($user);

        return redirect()->route('home');
    }

}
