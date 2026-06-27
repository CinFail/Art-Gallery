@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Dashboard</h1>
    <span class="text-muted small">{{ now()->format('l, F j, Y') }}</span>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf3e3;">
                <i class="bi bi-person-badge" style="color:#c9a84c;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['artists']) }}</div>
                <div class="stat-label">Total Artists</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;">
                <i class="bi bi-image" style="color:#2980b9;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['artworks']) }}</div>
                <div class="stat-label">Total Artworks</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0faf4;">
                <i class="bi bi-people" style="color:#27ae60;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['customers']) }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf0f0;">
                <i class="bi bi-collection" style="color:#c0392b;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($stats['categories']) }}</div>
                <div class="stat-label">Total Categories</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities (hidden for Viewer role) -->
@if(!auth()->user()->hasRole('Viewer'))
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-text me-2"></i>Recent Activities</span>
        @can('view activity logs')
        <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">
            View All
        </a>
        @endcan
    </div>
    <div class="card-body p-0">
        @if($recentActivities->isEmpty())
            <p class="text-muted text-center py-4">No activities recorded yet.</p>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $log)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $log->action }} badge-role">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="small">{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->user_name }}</td>
                        <td>
                            @if($log->module)
                                <span class="badge bg-light text-dark border small">
                                    {{ $log->module }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endif
@endsection
