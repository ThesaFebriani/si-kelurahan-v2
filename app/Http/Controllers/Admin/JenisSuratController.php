<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenis_surats = JenisSurat::latest()->get();
        return view('pages.admin.jenis-surat.index', compact('jenis_surats'));
    }

    public function create()
    {
        return view('pages.admin.jenis-surat.create');
    }

    public function edit($id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        return view('pages.admin.jenis-surat.edit', compact('jenis_surat'));
    }
}
