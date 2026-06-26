@extends('layouts.app')
@section('title', 'Artwork Groups')
@section('breadcrumb', 'Maintenance / Artwork Groups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Artwork Groups</h1>
    @can('manage groups')
    <a href="{{ route('groups.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Group
    </a>
    @endcan
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search by name or description…">
            <button class="btn btn-sm btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('groups.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                        <th>#</th>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>Artworks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $group)
                    <tr>
                        <td class="text-muted small">{{ $groups->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $group->name }}</strong></td>
                        <td class="small text-muted">
                            {{ Str::limit($group->description, 80) ?? '—' }}
                        </td>
                        <td>
                            <span class="badge" style="background:#fdf3e3;color:#7a5b00;">
                                {{ $group->artworks_count }} artworks
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage groups')
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline confirm-delete"
                                  data-name="{{ $group->name }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No groups found.
                            @can('manage groups')
                            <a href="{{ route('groups.create') }}">Add the first one.</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($groups->hasPages())
    <div class="card-footer bg-white">{{ $groups->links() }}</div>
    @endif
</div>
@endsection
