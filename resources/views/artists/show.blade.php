@extends('layouts.app')
@section('title', $artist->name)
@section('breadcrumb', 'Artists / ' . $artist->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('artists.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">{{ $artist->name }}</h1>
    @can('manage artists')
    <a href="{{ route('artists.edit', $artist) }}" class="btn btn-sm btn-outline-primary ms-auto">
        <i class="bi bi-pencil me-1"></i> Edit
    </a>
    @endcan
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Artist Details</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Birthplace</dt>
                    <dd class="col-7">{{ $artist->birthplace }}</dd>

                    <dt class="col-5 text-muted">Age</dt>
                    <dd class="col-7">{{ $artist->age }}</dd>

                    <dt class="col-5 text-muted">Art Style</dt>
                    <dd class="col-7">
                        <span class="badge bg-light text-dark border">{{ $artist->art_style }}</span>
                    </dd>

                    <dt class="col-5 text-muted">Total Works</dt>
                    <dd class="col-7">{{ $artist->artworks->count() }}</dd>
                </dl>
                @if($artist->bio)
                    <hr>
                    <p class="small text-muted mb-0">{{ $artist->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Artworks by {{ $artist->name }}</div>
            <div class="card-body p-0">
                @if($artist->artworks->isEmpty())
                    <p class="text-muted text-center py-4 small">No artworks yet.</p>
                @else
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Title</th><th>Year</th><th>Type</th><th>Price</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($artist->artworks as $artwork)
                        <tr>
                            <td>{{ $artwork->title }}</td>
                            <td>{{ $artwork->year_created }}</td>
                            <td><span class="badge bg-light text-dark border small">{{ $artwork->art_type }}</span></td>
                            <td>₱{{ number_format($artwork->price, 2) }}</td>
                            <td>
                                <a href="{{ route('artworks.show', $artwork) }}" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
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
