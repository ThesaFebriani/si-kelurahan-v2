<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::latest()->get();
        return view('pages.admin.bidang.index', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bidangs,code',
        ]);

        Bidang::create($request->all());

        return redirect()->back()->with('success', 'Bidang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('bidangs')->ignore($bidang->id)],
        ]);

        $bidang->update($request->all());

        return redirect()->back()->with('success', 'Bidang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        
        // Prevent deleting if in use (optional check, but good for safety)
        // For now just delete, or maybe check JenisSurat usage?
        // Let's keep it simple for now as requested.
        
        $bidang->delete();

        return redirect()->back()->with('success', 'Bidang berhasil dihapus');
    }
}
