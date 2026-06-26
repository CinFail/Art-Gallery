@extends('layouts.app')
@section('title', 'Customers')
@section('breadcrumb', 'Maintenance / Customers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Customers</h1>
    @can('manage customers')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Customer
    </a>
    @endcan
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search by name or address…">
            <button class="btn btn-sm btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                        <th>Address</th>
                        <th>Total Spent</th>
                        <th>Pref. Artists</th>
                        <th>Pref. Groups</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="text-muted small">{{ $customers->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td class="small text-muted">{{ Str::limit($customer->address, 50) }}</td>
                        <td class="fw-semibold">₱{{ number_format($customer->total_spent, 2) }}</td>
                        <td>
                            <span class="badge" style="background:#fdf3e3;color:#7a5b00;">
                                {{ $customer->preferred_artists_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background:#f0f8ff;color:#1a5276;">
                                {{ $customer->preferred_groups_count }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage customers')
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline confirm-delete"
                                  data-name="{{ $customer->name }}">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            No customers found.
                            @can('manage customers')
                            <a href="{{ route('customers.create') }}">Add the first one.</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer bg-white">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
