@extends('layouts.app')
@section('title', 'Users')
@section('breadcrumb', 'Administration / Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add User
    </a>
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search by name or email…">
            <button class="btn btn-sm btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-muted small">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-secondary ms-1 small">You</span>
                            @endif
                        </td>
                        <td class="small">{{ $user->email }}</td>
                        <td>
                            @php $role = $user->roles->first() @endphp
                            @if($role)
                                @php
                                    $roleColors = [
                                        'Administrator' => 'background:#fde8e8;color:#9b1c1c;',
                                        'Staff'         => 'background:#d6eaff;color:#0c50a0;',
                                        'Viewer'        => 'background:#d1f0e5;color:#1a6e4a;',
                                    ];
                                    $style = $roleColors[$role->name] ?? 'background:#f0f0f0;color:#555;';
                                @endphp
                                <span class="badge badge-role" style="{{ $style }}">{{ $role->name }}</span>
                            @else
                                <span class="text-muted small">No Role</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge" style="background:#d1f0e5;color:#1a6e4a;">Active</span>
                            @else
                                <span class="badge" style="background:#fde8e8;color:#9b1c1c;">Inactive</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline confirm-delete"
                                  data-name="{{ $user->name }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection
