{{-- Shared form fields for create/edit --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" value="{{ old('name', $artist->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Full artist name" required maxlength="255">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Birthplace <span class="text-danger">*</span></label>
        <input type="text" name="birthplace" value="{{ old('birthplace', $artist->birthplace ?? '') }}"
               class="form-control @error('birthplace') is-invalid @enderror"
               placeholder="City, Country" required maxlength="255">
        @error('birthplace') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Age <span class="text-danger">*</span></label>
        <input type="number" name="age" value="{{ old('age', $artist->age ?? '') }}"
               class="form-control @error('age') is-invalid @enderror"
               min="1" max="150" required>
        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3 mt-3">
    <label class="form-label fw-semibold">Art Style <span class="text-danger">*</span></label>
    <input type="text" name="art_style" value="{{ old('art_style', $artist->art_style ?? '') }}"
           class="form-control @error('art_style') is-invalid @enderror"
           placeholder="Impressionism, Surrealism, Abstract…" required maxlength="255">
    @error('art_style') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Biography</label>
    <textarea name="bio" rows="4"
              class="form-control @error('bio') is-invalid @enderror"
              placeholder="Short biography…">{{ old('bio', $artist->bio ?? '') }}</textarea>
    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
