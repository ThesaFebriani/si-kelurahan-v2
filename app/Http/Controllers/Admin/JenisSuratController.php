<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenis_surats = JenisSurat::latest()->get();
        return view('pages.admin.jenis-surat.index', compact('jenis_surats'));
    }

    public function create()
    {
        $bidangs = \App\Models\Bidang::all();
        return view('pages.admin.jenis-surat.create', compact('bidangs'));
    }

    public function edit($id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        $bidangs = \App\Models\Bidang::all();
        return view('pages.admin.jenis-surat.edit', compact('jenis_surat', 'bidangs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode_surat' => 'required|string|max:50|unique:jenis_surats',
            'name' => 'required|string|max:255',
            'bidang' => 'required|exists:bidangs,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jenisSurat = JenisSurat::create($request->all());

        return redirect()->route('admin.jenis-surat.edit', $jenisSurat->id)
            ->with('success', 'Jenis Surat berhasil dibuat. Silakan tambahkan persyaratan dokumen di bawah.');
    }

    public function update(Request $request, $id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);

        $request->validate([
            'kode_surat' => ['required', 'string', 'max:50', Rule::unique('jenis_surats')->ignore($jenis_surat->id)],
            'name' => 'required|string|max:255',
            'bidang' => 'required|exists:bidangs,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jenis_surat->update($request->all());

        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis Surat berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        $jenis_surat->delete();

        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis Surat berhasil dihapus');
    }
}
