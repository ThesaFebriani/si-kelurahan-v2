<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequiredDocument;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'document_name' => 'required|string|max:255',
            'is_required' => 'boolean',
        ]);

        RequiredDocument::create([
            'jenis_surat_id' => $request->jenis_surat_id,
            'document_name' => $request->document_name,
            'is_required' => $request->has('is_required'),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Persyaratan dokumen berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $document = RequiredDocument::findOrFail($id);
        $document->delete();

        return back()->with('success', 'Persyaratan dokumen berhasil dihapus.');
    }
}
