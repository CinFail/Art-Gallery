@extends('layouts.app')
@section('title', 'Edit Artist')
@section('breadcrumb', 'Maintenance / Artists / Edit')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('artists.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Edit Artist: {{ $artist->name }}</h1>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Artist Information</div>
            <div class="card-body">
                <form action="{{ route('artists.update', $artist) }}" method="POST">
                    @csrf @method('PUT')
                    @include('artists._form')
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Artist
                        </button>
                        <a href="{{ route('artists.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
