<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpEmail;
use App\Models\RequestForgotPassword as ModelsRequestForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RequestForgotPassword extends Controller
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
        return view('layouts.main_pages.request_forgot_password.request');
    }

    
    public function password_proccess_auth(Request $rq)
    {
        $rq->validate([
            'email' => 'required'
        ],
        [
            'email.required' => 'Harap masukan email anda'
        ]);

        $otp = rand(100000, 9999);
        $checking_email_exists = User::where('email', $rq->email)->first();

        if(!$checking_email_exists){
            session()->flash('failed_message', 'Email anda tidak ditemukan!');
            return redirect()->back()->withInput();
        }else{



        $data = ModelsRequestForgotPassword::create([
                'email' => $rq->email,
                'otp' => $otp,
                'status' => 13,
                'expired' => 'N',
                'request_date' => now(),
            ]);

            Mail::to($rq->email)->sendNow(new SendOtpEmail([
                'otp' => $data->otp,
                'email' => $data->email
            ]));

            session()->flash('message_success', 'Kode OTP sudah terkirim ke alamat email anda!');
            return redirect()->route('otp-confirmation', ['email' => $data->email]);
        }


    }


    public function otp_confirmation(Request $rq){

        $email = $rq->email;

        $userData = DB::table('request_forgot_password')->where('email', $email)->first();

        if(!$userData){
            session()->flash('failed_message', 'Email invalid!');
             return redirect()->back();
        }

        return view('layouts.main_pages.request_forgot_password.otp_confirmation');
    }


    public function otp_auth_confirmation(Request $rq){
        $rq->validate([
            'otp' => 'required',
            'email' => 'required'
        ],
        [
            'email.required' => 'email tidak ada',
            'otp.required' => 'OTP harus diisi'
        ]);


        $otp = $rq->otp;
        $email = $rq->email;


        if($rq->email == null){
            session()->flash('failed_message', 'Email tidak ada!');
            return redirect()->back();
        }
        

        $checking_otp_exists = DB::table('request_forgot_password')
        ->where('otp', $otp)
        ->where('email', $email)
        ->orderBy('created_at', 'DESC')
        ->first();


        if(!$checking_otp_exists){
             session()->flash('failed_message', 'kode OTP Invalid!');
            return redirect()->back();
        }

        if($checking_otp_exists->expired == 'Y'){
            session()->flash('failed_message', 'kode OTP Expired!');
            return redirect()->route('request-forgot-password');
        }

        if($checking_otp_exists->status == 13){
            return redirect()->route('change-password', ['email' => $email, 'otp' => $otp]);
        }else{
            session()->flash('failed_message', 'kode OTP Invalid!');
            return redirect()->back();
        }
    }

    public function change_password_proccess(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ],
        [
            'password.required' => 'Kata sandi harus disii',
            'confirm_password.required' => 'Konfirmasi kata sandi harus diisi',
            'email.required' => 'Email harus ada!'
        ]);

        $email = $request->email;
        $otp = $request->otp;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $user = User::where('email', $email)->first();

        if($request->password){

            if($password !== $confirm_password){
                session()->flash('failed_message', 'Your password not matching!');
                return redirect()->back();
            }

            if($user){
                $user->update([
                    'password' => Hash::make($request->password),
                    'updated_at' => now()
                ]);
            
                ModelsRequestForgotPassword::where('email', $email)->where('otp', $otp)->update([
                    'nik' => $user->nik,
                    'status' => 17,
                    'updated_at' => now(),
                    
                ]);

                session()->flash('message_success', 'Kata sandi berhasil diperbarui');
                return redirect()->route('login_kencana_bakery');
            }

        }


    }

    public function change_password_layouts(Request $rq){

        $email = $rq->email;
        $otp = $rq->otp;
        $checking_email_exists = DB::table('request_forgot_password')
        ->where('email', $email)
        ->where('otp', $otp)->first();


        if(!$checking_email_exists){
            session()->flash('failed_message', 'Request invalid or auth not found!');
            return redirect()->route('otp-confirmation', ['email' => $email]);
        }

        if($checking_email_exists->status == 17){
            session()->flash('failed_message', 'OTP Invalid!');
            return redirect()->back();
        }

        return view('layouts.main_pages.request_forgot_password.change-password');
    }


    public function store(Request $request)
    {
        //
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
