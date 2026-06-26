@extends('layouts.app')
@section('title', 'Artworks Gallery')
@section('breadcrumb', 'Maintenance / Artworks')

@push('styles')
<style>
/* ---- Carousel ---- */
.gallery-carousel { border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.15); }
.gallery-carousel .carousel-img {
    height: 400px;
    object-fit: cover;
    filter: brightness(.78);
    width: 100%;
}
.gallery-carousel .carousel-caption {
    background: linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 100%);
    bottom: 0; left: 0; right: 0;
    text-align: left;
    padding: 2.5rem 2rem 1.75rem;
    border-radius: 0 0 12px 12px;
}
.gallery-carousel .carousel-caption h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2rem;
    font-weight: 600;
    text-shadow: 0 2px 8px rgba(0,0,0,.4);
    margin-bottom: .25rem;
}
.gallery-carousel .carousel-caption p { font-size: .95rem; opacity: .9; margin-bottom: .75rem; }

/* ---- Artwork cards ---- */
.artwork-card {
    border-radius: 10px;
    overflow: hidden;
    transition: transform .22s, box-shadow .22s;
}
.artwork-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 28px rgba(0,0,0,.13) !important;
}
.artwork-img-wrap {
    position: relative;
    height: 210px;
    overflow: hidden;
    background: #f0f0f0;
}
.artwork-card-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .35s;
}
.artwork-card:hover .artwork-card-img { transform: scale(1.06); }
.artwork-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 3.5rem; color: #ccc;
    background: linear-gradient(135deg, #f7f7f7 0%, #e8e8e8 100%);
}
.artwork-type-badge {
    position: absolute; top: 9px; right: 9px;
    background: rgba(0,0,0,.55); color: #fff;
    font-size: .68rem; border-radius: 5px; padding: .2em .55em;
}
.btn-xs { padding: .2rem .45rem; font-size: .75rem; line-height: 1.4; border-radius: .25rem; }

/* ---- Tag filter pills ---- */
.tag-filter-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .3em .85em;
    border-radius: 30px;
    font-size: .78rem; font-weight: 500;
    text-decoration: none;
    transition: all .15s;
    border: 2px solid var(--tc);
    color: var(--tc);
    background: transparent;
}
.tag-filter-pill:hover { opacity: .8; }
.tag-filter-pill.active { background: var(--tc); color: #fff; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Artworks Gallery</h1>
    <div class="d-flex gap-2">
        @can('manage artworks')
        <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-tags me-1"></i> Manage Tags
        </a>
        <a href="{{ route('artworks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Artwork
        </a>
        @endcan
    </div>
</div>

{{-- Carousel (only when artworks with images exist) --}}
@if($featured->isNotEmpty())
<div id="artworkCarousel" class="carousel slide gallery-carousel mb-4"
     data-bs-ride="carousel" data-bs-interval="4500">

    <div class="carousel-indicators">
        @foreach($featured as $i => $art)
        <button type="button"
                data-bs-target="#artworkCarousel"
                data-bs-slide-to="{{ $i }}"
                class="{{ $i === 0 ? 'active' : '' }}"
                aria-label="{{ $art->title }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($featured as $i => $art)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
            <img src="{{ asset('storage/' . $art->image_path) }}"
                 class="carousel-img d-block"
                 alt="{{ $art->title }}">
            <div class="carousel-caption">
                <h3>{{ $art->title }}</h3>
                <p>
                    <i class="bi bi-person me-1"></i>{{ $art->artist->name }}
                    &nbsp;&middot;&nbsp;
                    <i class="bi bi-calendar me-1"></i>{{ $art->year_created }}
                    &nbsp;&middot;&nbsp;
                    ₱{{ number_format($art->price, 2) }}
                </p>
                <a href="{{ route('artworks.show', $art) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-eye me-1"></i> View Artwork
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#artworkCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#artworkCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
@endif

{{-- Search & Type Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" style="max-width:240px;"
                   placeholder="Search title, artist, type…">
            <select name="type" class="form-select form-select-sm" style="max-width:170px;"
                    onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach($types as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            {{-- preserve active tag filters --}}
            @foreach(request()->input('tags', []) as $tid)
            <input type="hidden" name="tags[]" value="{{ $tid }}">
            @endforeach
            <button class="btn btn-sm btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'type', 'tags']))
            <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Clear
            </a>
            @endif
        </form>
    </div>
</div>

{{-- Tag Filter Pills --}}
@if($allTags->isNotEmpty())
<div class="d-flex flex-wrap gap-2 align-items-center mb-4">
    <span class="text-muted small fw-semibold me-1">
        <i class="bi bi-funnel me-1"></i>Tags:
    </span>
    @foreach($allTags as $tag)
    @php
        $activeTags = array_map('intval', (array) request()->input('tags', []));
        $isActive   = in_array($tag->id, $activeTags);
        $newList    = $isActive
            ? array_values(array_filter($activeTags, fn($id) => $id != $tag->id))
            : array_merge($activeTags, [$tag->id]);
        $params = array_filter(request()->except('tags', 'page'));
        if ($newList) $params['tags'] = $newList;
        $tagUrl = url()->current() . ($params ? '?' . http_build_query($params) : '');
    @endphp
    <a href="{{ $tagUrl }}"
       class="tag-filter-pill {{ $isActive ? 'active' : '' }}"
       style="--tc: {{ $tag->color }}">
        <i class="bi bi-tag-fill"></i>
        {{ $tag->name }}
        <span class="opacity-75">({{ $tag->artworks_count }})</span>
    </a>
    @endforeach
</div>
@endif

{{-- Card Grid --}}
@if($artworks->isEmpty())
<div class="text-center py-5">
    <i class="bi bi-image" style="font-size:4rem; color:#ddd;"></i>
    <p class="text-muted mt-3 mb-1">No artworks found.</p>
    @can('manage artworks')
    <a href="{{ route('artworks.create') }}" class="btn btn-sm btn-primary mt-1">
        <i class="bi bi-plus-lg me-1"></i> Add the first artwork
    </a>
    @endcan
</div>
@else

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
    @foreach($artworks as $artwork)
    <div class="col">
        <div class="card h-100 artwork-card border-0 shadow-sm">

            {{-- Image --}}
            <div class="artwork-img-wrap">
                @if($artwork->image_path)
                <img src="{{ asset('storage/' . $artwork->image_path) }}"
                     alt="{{ $artwork->title }}"
                     class="artwork-card-img">
                @else
                <div class="artwork-placeholder">
                    <i class="bi bi-image"></i>
                </div>
                @endif
                <span class="artwork-type-badge">{{ $artwork->art_type }}</span>
            </div>

            {{-- Body --}}
            <div class="card-body pb-1">
                <h6 class="card-title mb-1 text-truncate fw-semibold" title="{{ $artwork->title }}">
                    {{ $artwork->title }}
                </h6>
                <p class="text-muted small mb-1">
                    <i class="bi bi-person me-1"></i>{{ $artwork->artist->name }}
                    <span class="ms-2 text-muted">{{ $artwork->year_created }}</span>
                </p>

                @if($artwork->tags->isNotEmpty())
                <div class="d-flex flex-wrap gap-1 mt-2">
                    @foreach($artwork->tags->take(3) as $tag)
                    <span class="badge"
                          style="background:{{ $tag->color }}22; color:{{ $tag->color }}; border:1px solid {{ $tag->color }}55; font-size:.68rem;">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                    @if($artwork->tags->count() > 3)
                    <span class="badge bg-light text-muted border" style="font-size:.68rem;">
                        +{{ $artwork->tags->count() - 3 }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center px-3 pb-3 pt-1">
                <span style="font-family:'Cormorant Garamond',serif; font-size:1.05rem; font-weight:600;">
                    ₱{{ number_format($artwork->price, 2) }}
                </span>
                <div class="d-flex gap-1">
                    <a href="{{ route('artworks.show', $artwork) }}" class="btn btn-xs btn-outline-secondary">
                        <i class="bi bi-eye"></i>
                    </a>
                    @can('manage artworks')
                    <a href="{{ route('artworks.edit', $artwork) }}" class="btn btn-xs btn-outline-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('artworks.destroy', $artwork) }}" method="POST"
                          class="d-inline confirm-delete" data-name="{{ $artwork->title }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>

        </div>
    </div>
    @endforeach
</div>

@if($artworks->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $artworks->links() }}
</div>
@endif

@endif
@endsection
