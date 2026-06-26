@extends('layouts.app')
@section('title', $customer->name)
@section('breadcrumb', 'Customers / ' . $customer->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">{{ $customer->name }}</h1>
    @can('manage customers')
    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary ms-auto">
        <i class="bi bi-pencil me-1"></i> Edit
    </a>
    @endcan
</div>

<div class="row g-4">
    <!-- Details -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Customer Details</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Name</dt>
                    <dd class="col-7">{{ $customer->name }}</dd>

                    <dt class="col-5 text-muted">Address</dt>
                    <dd class="col-7">{{ $customer->address }}</dd>

                    <dt class="col-5 text-muted">Total Spent</dt>
                    <dd class="col-7 fw-semibold text-success">
                        ₱{{ number_format($customer->total_spent, 2) }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Preferences -->
    <div class="col-md-8">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Preferred Artists</div>
                    <div class="card-body">
                        @forelse($customer->preferredArtists as $artist)
                            <a href="{{ route('artists.show', $artist) }}"
                               class="badge text-decoration-none me-1 mb-1"
                               style="background:#fdf3e3;color:#7a5b00;font-size:.85rem;padding:.4em .75em;">
                                <i class="bi bi-person-badge me-1"></i>{{ $artist->name }}
                            </a>
                        @empty
                            <p class="text-muted small mb-0">No preferred artists set.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Preferred Artwork Groups</div>
                    <div class="card-body">
                        @forelse($customer->preferredGroups as $group)
                            <a href="{{ route('groups.show', $group) }}"
                               class="badge text-decoration-none me-1 mb-1"
                               style="background:#f0f8ff;color:#1a5276;font-size:.85rem;padding:.4em .75em;">
                                <i class="bi bi-collection me-1"></i>{{ $group->name }}
                            </a>
                        @empty
                            <p class="text-muted small mb-0">No preferred groups set.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
