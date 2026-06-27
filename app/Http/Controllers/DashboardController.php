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

        $user  = auth()->user();
        $activityQuery = ActivityLog::with('user')->latest();

        if ($user->hasRole('Viewer')) {
            $activityQuery->where('user_id', $user->id);
        } elseif ($user->hasRole('Staff')) {
            $viewerIds = Role::findByName('Viewer')->users->pluck('id')->toArray();
            $activityQuery->where(function ($q) use ($user, $viewerIds) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('user_id', $viewerIds);
            });
        }

        $recentActivities = $activityQuery->take(15)->get();

        return view('dashboard.index', compact('stats', 'recentActivities'));
    }
}
