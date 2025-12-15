<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pages.masyarakat.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|string|in:laki-laki,perempuan',
            'agama' => 'nullable|string|max:50',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|string|max:50',
            'kewarganegaraan' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jk' => $request->jk,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'status_perkawinan' => $request->status_perkawinan,
            'kewarganegaraan' => $request->kewarganegaraan ?? 'WNI',
        ];

        // Jika ada password baru
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            
            // Log Password Change
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'change_password',
                'description' => 'User mengubah password akun.',
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        $user->update($userData);

        return redirect()->route('masyarakat.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
