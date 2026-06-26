@extends('layouts.app')
@section('title', 'Artists')
@section('breadcrumb', 'Maintenance / Artists')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Artists</h1>
    @can('manage artists')
    <a href="{{ route('artists.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Artist
    </a>
    @endcan
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search by name, style, birthplace…">
            <button class="btn btn-sm btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('artists.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                        <th>Birthplace</th>
                        <th>Age</th>
                        <th>Art Style</th>
                        <th>Artworks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artists as $artist)
                    <tr>
                        <td class="text-muted small">{{ $artists->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $artist->name }}</strong>
                        </td>
                        <td class="small">{{ $artist->birthplace }}</td>
                        <td>{{ $artist->age }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $artist->art_style }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background:#fdf3e3;color:#7a5b00;">
                                {{ $artist->artworks_count }} artworks
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('artists.show', $artist) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage artists')
                            <a href="{{ route('artists.edit', $artist) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('artists.destroy', $artist) }}" method="POST" class="d-inline confirm-delete"
                                  data-name="{{ $artist->name }}">
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
                            No artists found.
                            @can('manage artists')
                            <a href="{{ route('artists.create') }}">Add the first one.</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($artists->hasPages())
    <div class="card-footer bg-white">
        {{ $artists->links() }}
    </div>
    @endif
</div>
@endsection
