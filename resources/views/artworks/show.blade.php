@extends('layouts.app')
@section('title', $artwork->title)
@section('breadcrumb', 'Artworks / ' . $artwork->title)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">{{ $artwork->title }}</h1>
    @can('manage artworks')
    <a href="{{ route('artworks.edit', $artwork) }}" class="btn btn-sm btn-outline-primary ms-auto">
        <i class="bi bi-pencil me-1"></i> Edit
    </a>
    @endcan
</div>

<div class="row g-4">

    {{-- Artwork Image --}}
    <div class="col-md-6">
        @if($artwork->image_path)
        <div class="rounded-3 overflow-hidden shadow-sm" style="max-height:480px;">
            <img src="{{ asset('storage/' . $artwork->image_path) }}"
                 alt="{{ $artwork->title }}"
                 class="w-100"
                 style="object-fit:cover; max-height:480px;">
        </div>
        @else
        <div class="rounded-3 d-flex align-items-center justify-content-center bg-light border"
             style="height:320px;">
            <div class="text-center text-muted">
                <i class="bi bi-image" style="font-size:4rem; opacity:.3;"></i>
                <p class="mt-2 small">No image uploaded</p>
                @can('manage artworks')
                <a href="{{ route('artworks.edit', $artwork) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-upload me-1"></i> Upload Image
                </a>
                @endcan
            </div>
        </div>
        @endif
    </div>

    {{-- Details --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Artwork Details</div>
            <div class="card-body">
                <dl class="row mb-0 small">

                    <dt class="col-5 text-muted">Artist</dt>
                    <dd class="col-7">
                        <a href="{{ route('artists.show', $artwork->artist) }}">
                            {{ $artwork->artist->name }}
                        </a>
                    </dd>

                    <dt class="col-5 text-muted">Year Created</dt>
                    <dd class="col-7">{{ $artwork->year_created }}</dd>

                    <dt class="col-5 text-muted">Art Type</dt>
                    <dd class="col-7">
                        <span class="badge bg-light text-dark border">{{ $artwork->art_type }}</span>
                    </dd>

                    <dt class="col-5 text-muted">Price</dt>
                    <dd class="col-7 fw-semibold" style="font-family:'Cormorant Garamond',serif; font-size:1.05rem;">
                        ₱{{ number_format($artwork->price, 2) }}
                    </dd>

                    <dt class="col-5 text-muted">Groups</dt>
                    <dd class="col-7">
                        @forelse($artwork->groups as $group)
                        <span class="badge mb-1" style="background:#f0f8ff; color:#1a5276;">
                            {{ $group->name }}
                        </span>
                        @empty
                        <span class="text-muted">None</span>
                        @endforelse
                    </dd>

                    <dt class="col-5 text-muted">Tags</dt>
                    <dd class="col-7">
                        @forelse($artwork->tags as $tag)
                        <span class="badge mb-1"
                              style="background:{{ $tag->color }}22; color:{{ $tag->color }}; border:1px solid {{ $tag->color }}55;">
                            {{ $tag->name }}
                        </span>
                        @empty
                        <span class="text-muted">None</span>
                        @endforelse
                    </dd>

                </dl>

                @if($artwork->description)
                <hr>
                <p class="small text-muted mb-0">{{ $artwork->description }}</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
