<?php

namespace App\Http\Requests\Auth;

use App\Models\CustomerModel;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Mail\VerificationUsersAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Services\UserLogActivity;
use App\Models\UserLogActivities;


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function authenticate(): RedirectResponse
    {

        $this->validate([
            'login' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ],
        [
            'login.required' => 'Harap masukan email atau nomor handphone anda',
            'password.required' => 'Harap masukan kata sandi',
            'g-recaptcha-response.required' => 'Harap Verifikasi dahulu'
        ]);

        $this->ensureIsNotRateLimited();

        $login = $this->only('login')['login'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $email = filter_var($login, FILTER_VALIDATE_EMAIL) ? $login : null;

        // Mencari pengguna berdasarkan email atau username
        $user_available = User::where($field, $login)->first();


         // Verifikasi reCAPTCHA
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $this->input('g-recaptcha-response'),
                    'remoteip' => $this->ip(),
                ]
            );

        $result = $response->json();


        $nik = DB::table('users')->where('username', $login)->orWhere('email', $login)->first();



        if (!($result['success'] ?? false)) {
            return back()->withErrors([
                        'captcha' => 'Verifikasi reCAPTCHA gagal.',
                    ])->withInput();
        }


        // Jika pengguna tidak ditemukan
        if (!$user_available) {
            RateLimiter::hit($this->throttleKey());
           return back()
                ->withErrors([
                    'login' => 'Email/Username dan kata sandi tidak sesuai!'
                ])
                ->with('failed_message', 'Email/Username dan kata sandi tidak sesuai!')
                ->withInput();
        }


        if ($user_available->is_active == null) {
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Email belum diaktivasi!'
                ])
                ->with('failed_message', 'Email belum diaktivasi!')
                ->withInput();
        }elseif($user_available->account_verified == 'N' || $user_available->account_verified_at == null){

            $HasRegistered = DB::table('users as u')
                            ->select('u.nik', 'e.name', 'u.email')
                            ->join('employee as e', 'u.nik', '=', 'e.nik')
                            ->where('u.email', $user_available->email)->first();

            if($HasRegistered){
                Mail::to($user_available->email)->sendNow(new VerificationUsersAccount([
                    'email' => $email,
                    'nik' => $HasRegistered->nik,
                    'name' => $HasRegistered->name
                ]));

                RateLimiter::hit($this->throttleKey());
                 return back()
                ->with('message_success', 'Silahkan verifikasi akun anda melalui email anda!')
                ->withInput();
            }

        }elseif($user_available->is_active == 8){
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Akun sudah tidak aktif silahkan hubungi IT!'
                ])
                ->with('failed_message', 'Akun sudah tidak aktif silahkan hubungi IT!')
                ->withInput();
        
        } elseif (!Auth::attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Kata sandi salah!'
                ])
                ->with('failed_message', 'Kata sandi salah!')
                ->withInput();
        }

        $findPosition = DB::table('users as u')
                            ->select('u.nik', 'e.name', 'u.email', 'e.position')
                            ->join('employee as e', 'u.nik', '=', 'e.nik')
                            ->join('job_position as jp', 'e.position', '=', 'jp.position_code')
                            ->where('u.email', $user_available->email)->first();

        UserLogActivities::create([
            'user' => $nik->nik,
            'module' => 'Session Login',
            'method_type' => 'LOGIN',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'activity_date' => now(),
            'description' => 'User Login to Web'
        ]);

        // Jika semua pemeriksaan berhasil, login pengguna
        Auth::login($user_available);
        RateLimiter::clear($this->throttleKey());
        $this->session()->regenerate();
        if($findPosition->position == 'CSR'){
            return redirect()->intended('welcome');
        }
        return redirect()->intended('dashboard_main');
    }

    public function customer_login_request(): RedirectResponse
    {

        $this->validate([
            'login' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ],
        [
            'login.required' => 'Harap masukan email atau nomor handphone anda',
            'password.required' => 'Harap masukan kata sandi',
            'g-recaptcha-response.required' => 'Harap Verifikasi dahulu'
        ]);

        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $user = CustomerModel::where($field, $login)->first();

        // Verifikasi reCAPTCHA
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $this->input('g-recaptcha-response'),
                    'remoteip' => $this->ip(),
                ]
            );

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            return back()->withErrors([
                        'captcha' => 'Verifikasi reCAPTCHA gagal.',
                    ])->withInput();
        }

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Email/Username dan no hp tidak sesuai!'
                ])
                ->with('failed_message', 'Email/Username dan no hp tidak sesuai!')
                ->withInput();
        } elseif ($user->status == 8) {
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Akun sudah tidak aktif'
                ])
                ->with('failed_message', 'Akun sudah tidak aktif')
                ->withInput();
        }elseif ($user->account_email_verified == 'N' || $user->account_email_verified_at == null) {
            RateLimiter::hit($this->throttleKey());
            return back()
                ->withErrors([
                    'login' => 'Akun anda belum diverifikasi'
                ])
                ->with('failed_message', 'Akun anda belum diverifikasi')
                ->withInput();

        } elseif (!Auth::guard('customer')->attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
             return back()
                ->withErrors([
                    'login' => 'Kata sandi salah!'
                ])
                ->with('failed_message', 'Kata sandi salah!')
                ->withInput();
        }

        RateLimiter::clear($this->throttleKey());
        return redirect()->intended(route('home'));
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
