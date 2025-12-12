<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateField;
use Illuminate\Http\Request;

class TemplateFieldController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'field_key' => 'required|string|max:50',
            'field_label' => 'required|string|max:100',
            'field_type' => 'required|in:text,number,date,textarea,dropdown',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        TemplateField::create($request->all());

        return back()->with('success', 'Kolom isian berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $field = TemplateField::findOrFail($id);
        $field->delete();

        return back()->with('success', 'Kolom isian berhasil dihapus.');
    }
}
