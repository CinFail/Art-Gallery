<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view activity logs');
    }

    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = ActivityLog::with('user')->latest();

        if ($user->hasRole('Viewer')) {
            // Viewer sees only their own logs
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('Staff')) {
            // Staff sees their own logs + logs from Viewer-role users
            $viewerIds = Role::findByName('Viewer')->users->pluck('id')->toArray();
            $query->where(function ($q) use ($user, $viewerIds) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('user_id', $viewerIds);
            });
        }
        // Administrator sees all — no filter applied

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
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
