<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\ArtworkGroup;
use App\Models\Customer;

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

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(15)
            ->get();

        return view('dashboard.index', compact('stats', 'recentActivities'));
    }
}
