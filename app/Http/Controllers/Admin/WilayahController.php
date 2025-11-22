<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use App\Models\Rt;

class WilayahController extends Controller
{
    public function rwIndex()
    {
        $rw = Rw::withCount('rt')->latest()->get();
        return view('pages.admin.wilayah.rw-index', compact('rw'));
    }

    public function rtIndex()
    {
        $rt = Rt::with(['rw'])->latest()->get();
        return view('pages.admin.wilayah.rt-index', compact('rt'));
    }

    public function rwCreate()
    {
        return view('pages.admin.wilayah.rw-create');
    }

    public function rtCreate()
    {
        $rw = Rw::where('is_active', true)->get();
        return view('pages.admin.wilayah.rt-create', compact('rw'));
    }
}
