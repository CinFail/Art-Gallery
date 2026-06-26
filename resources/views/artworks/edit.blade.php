@extends('layouts.app')
@section('title', 'Edit Artwork')
@section('breadcrumb', 'Maintenance / Artworks / Edit')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Edit: {{ $artwork->title }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Artwork Information</div>
            <div class="card-body">
                <form action="{{ route('artworks.update', $artwork) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('artworks._form')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Artwork
                        </button>
                        <a href="{{ route('artworks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
