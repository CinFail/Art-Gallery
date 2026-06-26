@extends('layouts.app')
@section('title', 'Tags')
@section('breadcrumb', 'Maintenance / Tags')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Tags</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Gallery
        </a>
        <a href="{{ route('tags.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Tag
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Color</th>
                        <th>Tag Name</th>
                        <th>Artworks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                    <tr>
                        <td class="text-muted small align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                            <span class="d-inline-block rounded-2"
                                  style="width:26px; height:26px; background:{{ $tag->color }}; border:1px solid rgba(0,0,0,.1);"></span>
                        </td>
                        <td class="align-middle">
                            <span class="badge"
                                  style="background:{{ $tag->color }}22; color:{{ $tag->color }}; border:1px solid {{ $tag->color }}55; font-size:.85rem; padding:.4em .85em;">
                                {{ $tag->name }}
                            </span>
                        </td>
                        <td class="align-middle">{{ $tag->artworks_count }}</td>
                        <td class="align-middle">
                            <a href="{{ route('tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                                  class="d-inline confirm-delete" data-name="{{ $tag->name }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No tags yet.
                            <a href="{{ route('tags.create') }}">Create the first one.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
