@extends('layouts.app')
@section('title', 'Add Tag')
@section('breadcrumb', 'Maintenance / Tags / Add')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Add Tag</h1>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Tag Information</div>
            <div class="card-body">
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Filipino Art" required maxlength="255">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Color <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" id="tag-color"
                                   value="{{ old('color', '#6c757d') }}"
                                   class="form-control form-control-color @error('color') is-invalid @enderror"
                                   style="width:60px; height:40px;">
                            <span id="tag-preview"
                                  class="badge"
                                  style="font-size:.9rem; padding:.45em .9em; background:#6c757d22; color:#6c757d; border:2px solid #6c757d55;">
                                Preview
                            </span>
                        </div>
                        <div class="form-text">Pick a color to identify this tag.</div>
                        @error('color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Tag
                        </button>
                        <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var colorInput = document.getElementById('tag-color');
    var preview    = document.getElementById('tag-preview');
    var nameInput  = document.querySelector('input[name="name"]');

    function updatePreview() {
        var c = colorInput.value;
        var name = nameInput.value || 'Preview';
        preview.textContent = name;
        preview.style.background = c + '22';
        preview.style.color      = c;
        preview.style.borderColor = c + '55';
    }

    colorInput.addEventListener('input', updatePreview);
    nameInput.addEventListener('input', updatePreview);
    updatePreview();
})();
</script>
@endpush
