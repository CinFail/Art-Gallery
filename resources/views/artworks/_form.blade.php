{{-- Artwork Image Upload --}}
<div class="mb-4">
    <label class="form-label fw-semibold">Artwork Image</label>

    @if(isset($artwork) && $artwork->image_path)
    <div class="mb-2" id="current-image-wrap">
        <img src="{{ asset('storage/' . $artwork->image_path) }}"
             alt="{{ $artwork->title }}"
             class="img-thumbnail d-block mb-2"
             style="max-height:200px; max-width:320px; object-fit:cover;">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
            <label class="form-check-label text-danger small" for="remove_image">
                <i class="bi bi-trash me-1"></i>Remove current image
            </label>
        </div>
    </div>
    @endif

    <input type="file" name="image" id="artwork-image-input"
           class="form-control @error('image') is-invalid @enderror"
           accept="image/*">

    <div id="image-preview-wrap" class="mt-2" style="display:none;">
        <img id="image-preview" src="" alt="Preview"
             class="img-thumbnail"
             style="max-height:200px; max-width:320px; object-fit:cover;">
    </div>

    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    <div class="form-text">JPG, PNG, WebP — max 2 MB.</div>
</div>

{{-- Title --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" value="{{ old('title', $artwork->title ?? '') }}"
           class="form-control @error('title') is-invalid @enderror"
           placeholder="Unique artwork title" required maxlength="255">
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label fw-semibold">Artist <span class="text-danger">*</span></label>
        <select name="artist_id" class="form-select @error('artist_id') is-invalid @enderror" required>
            <option value="">— Select Artist —</option>
            @foreach($artists as $artist)
            <option value="{{ $artist->id }}"
                @selected(old('artist_id', $artwork->artist_id ?? '') == $artist->id)>
                {{ $artist->name }}
            </option>
            @endforeach
        </select>
        @error('artist_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-5">
        <label class="form-label fw-semibold">Year Created <span class="text-danger">*</span></label>
        <input type="number" name="year_created" value="{{ old('year_created', $artwork->year_created ?? '') }}"
               class="form-control @error('year_created') is-invalid @enderror"
               min="1000" max="{{ date('Y') }}" required>
        @error('year_created') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Art Type <span class="text-danger">*</span></label>
        <select name="art_type" class="form-select @error('art_type') is-invalid @enderror" required>
            <option value="">— Select Type —</option>
            @foreach($types as $type)
            <option value="{{ $type }}" @selected(old('art_type', $artwork->art_type ?? '') == $type)>
                {{ $type }}
            </option>
            @endforeach
        </select>
        @error('art_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Price (₱) <span class="text-danger">*</span></label>
        <input type="number" name="price" value="{{ old('price', $artwork->price ?? '') }}"
               class="form-control @error('price') is-invalid @enderror"
               min="0" step="0.01" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Artwork Groups --}}
<div class="mb-3 mt-3">
    <label class="form-label fw-semibold">Artwork Groups</label>
    <select name="groups[]" multiple class="form-select @error('groups') is-invalid @enderror" size="5">
        @foreach($groups as $group)
        <option value="{{ $group->id }}"
            @if(in_array($group->id, old('groups', $selectedGroups ?? []))) selected @endif>
            {{ $group->name }}
        </option>
        @endforeach
    </select>
    <div class="form-text">Hold Ctrl / Cmd to select multiple.</div>
    @error('groups') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Tags --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Tags</label>
    @if($allTags->isEmpty())
        <p class="text-muted small mb-0">
            No tags yet.
            @can('manage artworks')
            <a href="{{ route('tags.create') }}" target="_blank">Create one.</a>
            @endcan
        </p>
    @else
    <div class="d-flex flex-wrap gap-2 mt-1">
        @foreach($allTags as $tag)
        <label class="tag-pill-label" style="--tc: {{ $tag->color }}">
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                   class="tag-pill-checkbox"
                   @if(in_array($tag->id, old('tags', $selectedTags ?? []))) checked @endif>
            <span class="tag-pill-text">{{ $tag->name }}</span>
        </label>
        @endforeach
    </div>
    @endif
    @error('tags') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Optional artwork description…">{{ old('description', $artwork->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@push('styles')
<style>
.tag-pill-label {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35em .85em;
    border-radius: 30px;
    border: 2px solid var(--tc);
    color: var(--tc);
    cursor: pointer;
    font-size: .82rem;
    font-weight: 500;
    transition: all .15s;
    user-select: none;
    background: transparent;
}
.tag-pill-label:hover {
    background: color-mix(in srgb, var(--tc) 12%, transparent);
}
.tag-pill-checkbox {
    display: none;
}
.tag-pill-checkbox:checked + .tag-pill-text {
    /* handled by JS toggling parent class */
}
.tag-pill-label.checked {
    background: var(--tc);
    color: #fff;
}
</style>
@endpush

@push('scripts')
<script>
// Image preview
document.getElementById('artwork-image-input').addEventListener('change', function () {
    var wrap = document.getElementById('image-preview-wrap');
    var preview = document.getElementById('image-preview');
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            wrap.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        wrap.style.display = 'none';
    }
});

// Tag pill toggle
document.querySelectorAll('.tag-pill-checkbox').forEach(function (cb) {
    var label = cb.closest('.tag-pill-label');
    if (cb.checked) label.classList.add('checked');
    cb.addEventListener('change', function () {
        label.classList.toggle('checked', cb.checked);
    });
});
</script>
@endpush
