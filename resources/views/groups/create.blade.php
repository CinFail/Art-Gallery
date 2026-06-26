@extends('layouts.app')
@section('title', 'Add Artwork Group')
@section('breadcrumb', 'Maintenance / Artwork Groups / Add')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('groups.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Add Artwork Group</h1>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Group Information</div>
            <div class="card-body">
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf
                    @include('groups._form')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Group
                        </button>
                        <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
