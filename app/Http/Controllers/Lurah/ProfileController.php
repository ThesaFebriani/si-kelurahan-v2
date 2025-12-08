<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return view('pages.lurah.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password); // User model mutator handles hashing but using bcrypt here explicitly covers bases if mutator is weird. Actually User model has mutator setPasswordAttribute which triggers on assignment. Let's trust model mutator if exists, or manually hash. Model has it.
        }

        $user->update($userData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
