@extends('layouts.app')
@section('title', 'Roles')
@section('breadcrumb', 'Administration / Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Roles & Permissions</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Role
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Permissions</th>
                    <th>Users</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $role->name }}</strong>
                        @if(in_array($role->name, ['Administrator','Staff','Viewer']))
                            <span class="badge bg-secondary ms-1 small">System</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $role->description ?? '—' }}</td>
                    <td>
                        <span class="badge" style="background:#f0f8ff;color:#1a5276;">
                            {{ $role->permissions->count() }} permissions
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background:#fdf3e3;color:#7a5b00;">
                            {{ $role->users_count }} users
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(!in_array($role->name, ['Administrator','Staff','Viewer']))
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline confirm-delete"
                              data-name="{{ $role->name }}">
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
                    <td colspan="6" class="text-center text-muted py-4">No roles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
