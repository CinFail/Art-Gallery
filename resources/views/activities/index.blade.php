@extends('layouts.app')
@section('title', 'Activity Logs')
@section('breadcrumb', 'Administration / Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Activity Logs</h1>
    <span class="text-muted small">Audit trail of all system actions</span>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" style="max-width:220px;"
                   placeholder="Search description or user…">

            <select name="action" class="form-select form-select-sm" style="max-width:140px;">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                <option value="{{ $action }}" @selected(request('action') === $action)>
                    {{ ucfirst($action) }}
                </option>
                @endforeach
            </select>

            <select name="module" class="form-select form-select-sm" style="max-width:160px;">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                <option value="{{ $module }}" @selected(request('module') === $module)>
                    {{ $module }}
                </option>
                @endforeach
            </select>

            <button class="btn btn-sm btn-primary">Filter</button>
            @if(request()->hasAny(['search','action','module']))
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Action</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted text-nowrap">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                            <br>
                            <span style="font-size:.7rem;">{{ $log->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $log->action }} badge-role">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="small">
                            <strong>{{ $log->user_name ?? '—' }}</strong>
                        </td>
                        <td>
                            @if($log->module)
                                <span class="badge bg-light text-dark border small">{{ $log->module }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="small">{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No activity logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
