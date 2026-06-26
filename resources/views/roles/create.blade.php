@extends('layouts.app')
@section('title', 'Add Role')
@section('breadcrumb', 'Administration / Roles / Add')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Add Role</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Role Information</div>
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Manager, Curator…" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="form-control" placeholder="Optional description">
                    </div>

                    <label class="form-label fw-semibold">Permissions</label>
                    <div class="row g-3">
                        @foreach($permissions as $module => $perms)
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header py-2 small fw-semibold text-capitalize">
                                    {{ $module }}
                                </div>
                                <div class="card-body py-2">
                                    @foreach($perms as $perm)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="permissions[]" value="{{ $perm->name }}"
                                               id="perm_{{ $perm->id }}"
                                               @checked(in_array($perm->name, old('permissions', [])))>
                                        <label class="form-check-label small" for="perm_{{ $perm->id }}">
                                            {{ ucfirst($perm->name) }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Create Role
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
