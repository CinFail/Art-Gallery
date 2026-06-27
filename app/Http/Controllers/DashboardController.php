<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\ArtworkGroup;
use App\Models\Customer;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'artists'    => Artist::count(),
            'artworks'   => Artwork::count(),
            'customers'  => Customer::count(),
            'categories' => ArtworkGroup::count(),
        ];

        $user = auth()->user();

        if ($user->hasRole('Viewer')) {
            // Viewer sees no activity logs on the dashboard
            $recentActivities = collect();
        } elseif ($user->hasRole('Staff')) {
            // Staff sees only Viewer-role users' logs — not their own, not Admin's
            $viewerIds = Role::findByName('Viewer')->users->pluck('id')->toArray();
            $recentActivities = ActivityLog::with('user')
                ->whereIn('user_id', $viewerIds)
                ->latest()
                ->take(15)
                ->get();
        } else {
            // Administrator sees all logs
            $recentActivities = ActivityLog::with('user')->latest()->take(15)->get();
        }

        return view('dashboard.index', compact('stats', 'recentActivities'));
    }
}
