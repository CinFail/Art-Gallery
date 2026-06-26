@extends('layouts.app')
@section('title', 'Edit Artwork Group')
@section('breadcrumb', 'Maintenance / Artwork Groups / Edit')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('groups.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Edit Group: {{ $group->name }}</h1>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Group Information</div>
            <div class="card-body">
                <form action="{{ route('groups.update', $group) }}" method="POST">
                    @csrf @method('PUT')
                    @include('groups._form')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Group
                        </button>
                        <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
