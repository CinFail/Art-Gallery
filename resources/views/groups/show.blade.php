@extends('layouts.app')
@section('title', $group->name)
@section('breadcrumb', 'Artwork Groups / ' . $group->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('groups.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">{{ $group->name }}</h1>
    @can('manage groups')
    <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-outline-primary ms-auto">
        <i class="bi bi-pencil me-1"></i> Edit
    </a>
    @endcan
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Group Details</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Name</dt>
                    <dd class="col-7">{{ $group->name }}</dd>
                    <dt class="col-5 text-muted">Total Artworks</dt>
                    <dd class="col-7">{{ $group->artworks->count() }}</dd>
                </dl>
                @if($group->description)
                    <hr>
                    <p class="small text-muted mb-0">{{ $group->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Artworks in "{{ $group->name }}"</div>
            <div class="card-body p-0">
                @if($group->artworks->isEmpty())
                    <p class="text-muted text-center py-4 small">No artworks in this group yet.</p>
                @else
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Title</th><th>Artist</th><th>Year</th><th>Type</th><th>Price</th></tr>
                    </thead>
                    <tbody>
                        @foreach($group->artworks as $artwork)
                        <tr>
                            <td>
                                <a href="{{ route('artworks.show', $artwork) }}">{{ $artwork->title }}</a>
                            </td>
                            <td class="small text-muted">{{ $artwork->artist->name }}</td>
                            <td>{{ $artwork->year_created }}</td>
                            <td><span class="badge bg-light text-dark border small">{{ $artwork->art_type }}</span></td>
                            <td>₱{{ number_format($artwork->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
