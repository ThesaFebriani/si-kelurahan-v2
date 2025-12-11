<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $rts = Rt::aktif()->with('rw')->get(); // AMBIL RT + RW

        return view('auth.register', compact('rts'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:users,nik'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Cek Data Penduduk (Strict Mode)
        // Load relasi keluarga untuk mengambil alamat & RT
        $penduduk = \App\Models\AnggotaKeluarga::where('nik', $request->nik)->with('keluarga')->first();

        if (!$penduduk) {
            return back()->withInput()->withErrors(['nik' => 'NIK Anda belum terdaftar di Data Kependudukan Desa. Silakan hubungi RT atau Admin Kelurahan.']);
        }

        // 3. Susun Data User Otomatis dari Database Penduduk
        $userData = [
            'role_id' => 2, // Masyarakat
            'status' => User::STATUS_ACTIVE, // Auto Activate

            // Input User
            'nik' => $request->nik,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'password' => Hash::make($request->password),

            // Auto-Fill dari Master Data Penduduk
            'name' => $penduduk->nama_lengkap,
            'jk' => ($penduduk->jk == 'L' ? 'laki-laki' : 'perempuan'), // Convert L/P to full string if needed
            'tempat_lahir' => $penduduk->tempat_lahir,
            'tanggal_lahir' => $penduduk->tanggal_lahir,
            'agama' => $penduduk->agama,
            'status_perkawinan' => $penduduk->status_perkawinan,
            'pekerjaan' => $penduduk->pekerjaan,
            
            // Auto-Fill dari Kartu Keluarga
            'alamat' => $penduduk->keluarga ? $penduduk->keluarga->alamat_lengkap : '-',
            'rt_id' => $penduduk->keluarga ? $penduduk->keluarga->rt_id : null,
        ];

        // 4. Buat Akun
        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
