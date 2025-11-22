<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Rt;
use Illuminate\Support\Facades\Auth;

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
}
