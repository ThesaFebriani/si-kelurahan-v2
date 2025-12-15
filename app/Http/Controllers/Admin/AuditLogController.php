<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Filter by Date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('pages.admin.audit-logs.index', compact('logs', 'users'));
    }

    public function show(AuditLog $auditLog)
    {
        // Check if ajax request for modal
        if (request()->ajax()) {
            return view('pages.admin.audit-logs._detail_modal', compact('auditLog'))->render();
        }
        return abort(404);
    }
}
