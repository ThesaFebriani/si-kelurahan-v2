<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SystemSettingsController extends Controller
{
    public function index()
    {
        return view('pages.admin.settings.index');
    }
}
