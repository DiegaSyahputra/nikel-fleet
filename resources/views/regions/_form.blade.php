{{-- Partial: resources/views/regions/_form.blade.php --}}
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nama Region <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $region->name ?? '') }}"
               placeholder="Contoh: Tambang Sorowako" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">— Pilih Tipe —</option>
            <option value="head_office" {{ old('type', $region->type ?? '') === 'head_office' ? 'selected' : '' }}>Kantor Pusat</option>
            <option value="branch"      {{ old('type', $region->type ?? '') === 'branch'      ? 'selected' : '' }}>Kantor Cabang</option>
            <option value="mine"        {{ old('type', $region->type ?? '') === 'mine'        ? 'selected' : '' }}>Tambang</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Alamat</label>
        <textarea name="address" rows="3"
                  class="form-control @error('address') is-invalid @enderror"
                  placeholder="Alamat lengkap lokasi (opsional)">{{ old('address', $region->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
