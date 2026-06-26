{{-- Shared fields for customer create / edit --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
    <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Full name" required maxlength="255">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
    <textarea name="address" rows="2"
              class="form-control @error('address') is-invalid @enderror"
              placeholder="Full mailing address" required>{{ old('address', $customer->address ?? '') }}</textarea>
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Total Amount Spent (₱)</label>
    <input type="number" name="total_spent" value="{{ old('total_spent', $customer->total_spent ?? 0) }}"
           class="form-control @error('total_spent') is-invalid @enderror"
           min="0" step="0.01">
    @error('total_spent') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Preferred Artists</label>
        <select name="preferred_artists[]" multiple
                class="form-select @error('preferred_artists') is-invalid @enderror" size="6">
            @foreach($artists as $artist)
            <option value="{{ $artist->id }}"
                @if(in_array($artist->id, old('preferred_artists', $selectedArtists ?? []))) selected @endif>
                {{ $artist->name }}
            </option>
            @endforeach
        </select>
        @error('preferred_artists') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Preferred Artwork Groups</label>
        <select name="preferred_groups[]" multiple
                class="form-select @error('preferred_groups') is-invalid @enderror" size="6">
            @foreach($groups as $group)
            <option value="{{ $group->id }}"
                @if(in_array($group->id, old('preferred_groups', $selectedGroups ?? []))) selected @endif>
                {{ $group->name }}
            </option>
            @endforeach
        </select>
        @error('preferred_groups') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
