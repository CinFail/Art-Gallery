<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view activity logs');
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
        }

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        if ($module = $request->input('module')) {
            $query->where('module', $module);
        }

        $logs    = $query->paginate(20)->withQueryString();
        $actions = ActivityLog::distinct()->pluck('action');
        $modules = ActivityLog::distinct()->whereNotNull('module')->pluck('module');

        return view('activities.index', compact('logs', 'actions', 'modules'));
    }
}
