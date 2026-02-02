<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Tentukan Tipe Input (NIK atau Email)
        $inputType = filter_var($this->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';
        $inputValue = $this->input('email');

        // 2. Cek User berdasarkan Input
        $user = \App\Models\User::where($inputType, $inputValue)->first();

        // 3. Validasi Aturan Login (STRICT RULE)
        if ($user) {
            // Jika login pakai NIK, pastikan dia Role Masyarakat (Role ID 2)
            // Pejabat (Role 1, 3, 4, 5) TIDAK BOLEH login pakai NIK (harus pakai email dinas)
            if ($inputType === 'nik' && $user->role_id !== 2) {
                throw ValidationException::withMessages([
                    'email' => 'Pejabat/Staf wajib login menggunakan Email Dinas.',
                ]);
            }

            // Jika login pakai Email, pastikan dia BUKAN Masyarakat
            // Aturan Strict: Warga WAJIB pakai NIK.
            if ($inputType === 'email' && $user->role_id === 2) {
                throw ValidationException::withMessages([
                    'email' => 'Warga wajib login menggunakan NIK (Nomor Induk Kependudukan).',
                ]);
            }
        }

        // 4. Proses Autentikasi Laravel
        $credentials = [
            $inputType => $inputValue,
            'password' => $this->input('password')
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // CHECK STATUS
        $user = Auth::user();
        if ($user->status !== 'active') {
             Auth::logout();
             RateLimiter::clear($this->throttleKey());
             
             if ($user->status === 'pending') {
                 // Throw a specialized exception or just error, but ideally redirect to pending page?
                 // Since this is an API call usually, but here it's Form Request. 
                 // We can throw ValidationException
                 throw ValidationException::withMessages([
                    'email' => 'Akun Anda belum diaktifkan. Silakan tunggu verifikasi RT.',
                 ]);
             } else {
                 throw ValidationException::withMessages([
                    'email' => 'Akun Anda telah ditolak. Silakan hubungi RT setempat.',
                 ]);
             }
        }

        RateLimiter::clear($this->throttleKey());
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
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
