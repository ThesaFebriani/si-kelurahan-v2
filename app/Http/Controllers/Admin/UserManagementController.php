<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Rt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'rt'])->latest()->get();
        $roles = Role::where('is_active', true)->get();
        $rt_list = Rt::where('is_active', true)->get();

        return view('pages.admin.user-management.index', compact('users', 'roles', 'rt_list'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $rt_list = Rt::where('is_active', true)->get();

        return view('pages.admin.user-management.create', compact('roles', 'rt_list'));
    }

    public function edit($id)
    {
        $user = User::with(['role', 'rt'])->findOrFail($id);
        $roles = Role::where('is_active', true)->get();
        $rt_list = Rt::where('is_active', true)->get();

        return view('pages.admin.user-management.edit', compact('user', 'roles', 'rt_list'));
    }
    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'rt_id' => 'nullable|exists:rt,id',
            'jabatan' => 'nullable|string',
        ]);

        // 2. Validasi Conditional (NIP vs NIK)
        $role = Role::find($request->role_id);
        $rules = [];

        if (in_array($role->name, [Role::LURAH, Role::KASI])) {
            $rules['nip'] = 'required|string|unique:users,nip';
            $request->merge(['nik' => $request->nik ?? $request->nip]); // Fallback NIK = NIP jika kosong untuk PNS? Atau biarkan nullable? 
            // Better: User table constraint usually unique NIK. 
            // Let's assume PNS uses NIP as NIK if not provided, OR allow NIK to be optional if NIP is present?
            // Schema has NIK as unique string. So it must be provided.
            // Let's require NIK for everyone as Identity, but NIP mandatory specifically for PNS.
            $rules['nik'] = 'required|string|unique:users,nik'; 
        } else {
            // Warga / RT
            $rules['nik'] = 'required|string|unique:users,nik';
            $rules['nip'] = 'nullable|string';
            
            // Khusus Masyarakat wajib isi alamat, Role lain opsional (default '-')
            if ($role->name === Role::MASYARAKAT) {
                $rules['alamat'] = 'required|string';
            }
        }
        
        $request->validate($rules);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        
        // Auto assign JK
        if(!isset($data['jk'])) $data['jk'] = 'laki-laki';
        
        // Handle Alamat Default
        if (empty($data['alamat'])) {
            $data['alamat'] = '-';
        }

        // Admin create selalu ACTIVE
        $data['status'] = User::STATUS_ACTIVE;

        // Cek Data Penduduk via NIK
        $penduduk = \App\Models\AnggotaKeluarga::where('nik', $data['nik'])->first();
        if ($penduduk) {
            // Auto fill data from Kependudukan if match
            $data['name'] = $penduduk->nama_lengkap;
            $data['jk'] = strtolower($penduduk->jk) == 'l' ? 'laki-laki' : 'perempuan';
            $data['tempat_lahir'] = $penduduk->tempat_lahir;
            $data['tanggal_lahir'] = $penduduk->tanggal_lahir;
            $data['agama'] = $penduduk->agama;
            $data['status_perkawinan'] = $penduduk->status_perkawinan;
            $data['pekerjaan'] = $penduduk->pekerjaan;
            
            // Jika alamat kosong, ambil dari KK
            if ($data['alamat'] == '-') {
                $data['alamat'] = $penduduk->keluarga->alamat_lengkap;
            }
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. Validasi Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'rt_id' => 'nullable|exists:rt,id',
            'jabatan' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Validasi Conditional
        $role = Role::find($request->role_id);
        $rules = [];

        if (in_array($role->name, [Role::LURAH, Role::KASI])) {
            $rules['nip'] = ['required', 'string', Rule::unique('users')->ignore($user->id)];
            $rules['nik'] = ['required', 'string', Rule::unique('users')->ignore($user->id)];
        } else {
            $rules['nik'] = ['required', 'string', Rule::unique('users')->ignore($user->id)];
            $rules['nip'] = 'nullable|string';
        }

        $request->validate($rules);

        $data = $request->except(['password', 'password_confirmation']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
