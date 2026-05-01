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
        $this->ensureIsNotRateLimited();

        $login = $this->only('login')['login'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Mencari pengguna berdasarkan email atau username
        $user_available = User::where($field, $login)->first();

        // Jika pengguna tidak ditemukan
        if (!$user_available) {
            RateLimiter::hit($this->throttleKey());
            return redirect()->route('login_kencana_bakery')->with(
                'failed_message',
                'Email/Username dan kata sandi tidak sesuai!'
             );
        }


        if ($user_available->is_active == null) {
            RateLimiter::hit($this->throttleKey());
            return redirect()->route('login_kencana_bakery')->with(
                'failed_message',
                'Email belum diaktivasi!'
             );
        }elseif($user_available->is_active == 'N'){
            RateLimiter::hit($this->throttleKey());
            return redirect()->route('login_kencana_bakery')->with(
                'failed_message',
                'Akun anda sudah tidak aktif silahkan hubungi IT!'
             );
        
        } elseif (!Auth::attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
             return redirect()->route('login_kencana_bakery')->with(
                'failed_message',
                'Kata sandi salah, silahkan coba lagi!'
             );
        }

        // Jika semua pemeriksaan berhasil, login pengguna
        Auth::login($user_available);
        RateLimiter::clear($this->throttleKey());
        $this->session()->regenerate();
        return redirect()->intended('dashboard_main');
    }

    public function customer_login_request(): RedirectResponse
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        

        $user = CustomerModel::where($field, $login)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            return redirect()->route('login_app')->with(
                'failed_message',
                'Email atau nomor telepon tidak sesuai!'
             );
        } elseif ($user->status == 8) {
            RateLimiter::hit($this->throttleKey());
             return redirect()->route('login_app')->with(
                'failed_message',
                'Akun anda sudah tidak aktif!'
             );

        } elseif (!Auth::guard('customer')->attempt([$field => $login, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
             return redirect()->route('login_app')->with(
                'failed_message',
                'Kata sandi salah, silahkan coba lagi!'
             );
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
