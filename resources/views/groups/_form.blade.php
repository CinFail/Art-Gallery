{{-- Shared form fields for group create/edit --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Group Name <span class="text-danger">*</span></label>
    <input type="text" name="name" value="{{ old('name', $group->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Impressionism, Modern Art, Portraits…" required maxlength="255">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Description</label>
    <textarea name="description" rows="4"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Brief description of what this group represents…">{{ old('description', $group->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
