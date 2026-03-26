{{-- Partial: resources/views/vehicles/_form.blade.php --}}
<div class="row g-3">
    {{-- Identitas --}}
    <div class="col-12">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Identitas Kendaraan</h6>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
        <input type="text" name="license_plate"
               class="form-control text-uppercase @error('license_plate') is-invalid @enderror"
               value="{{ old('license_plate', $vehicle->license_plate ?? '') }}"
               placeholder="B 1234 ABC" required>
        @error('license_plate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Merek <span class="text-danger">*</span></label>
        <input type="text" name="brand"
               class="form-control @error('brand') is-invalid @enderror"
               value="{{ old('brand', $vehicle->brand ?? '') }}"
               placeholder="Toyota, Mitsubishi, dll" required>
        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
        <input type="text" name="model"
               class="form-control @error('model') is-invalid @enderror"
               value="{{ old('model', $vehicle->model ?? '') }}"
               placeholder="Innova, Fortuner, L300, dll" required>
        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Tahun</label>
        <input type="number" name="year"
               class="form-control @error('year') is-invalid @enderror"
               value="{{ old('year', $vehicle->year ?? '') }}"
               min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Warna</label>
        <input type="text" name="color"
               class="form-control @error('color') is-invalid @enderror"
               value="{{ old('color', $vehicle->color ?? '') }}"
               placeholder="Hitam, Putih, dll">
        @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Bahan Bakar <span class="text-danger">*</span></label>
        <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror" required>
            @foreach(['solar' => 'Solar', 'bensin' => 'Bensin', 'listrik' => 'Listrik'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('fuel_type', $vehicle->fuel_type ?? 'solar') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('fuel_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Lokasi / Region <span class="text-danger">*</span></label>
        <select name="region_id" class="form-select @error('region_id') is-invalid @enderror" required>
            <option value="">— Pilih Region —</option>
            @foreach($regions as $r)
                <option value="{{ $r->id }}"
                    {{ old('region_id', $vehicle->region_id ?? '') == $r->id ? 'selected' : '' }}>
                    {{ $r->name }}
                </option>
            @endforeach
        </select>
        @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Klasifikasi --}}
    <div class="col-12 mt-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Klasifikasi</h6>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
        <div class="d-flex gap-3 mt-1">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type_passenger" value="passenger"
                       {{ old('type', $vehicle->type ?? '') === 'passenger' ? 'checked' : '' }} required>
                <label class="form-check-label" for="type_passenger">
                    <i class="bi bi-people me-1 text-primary"></i> Angkutan Orang
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type_cargo" value="cargo"
                       {{ old('type', $vehicle->type ?? '') === 'cargo' ? 'checked' : '' }}>
                <label class="form-check-label" for="type_cargo">
                    <i class="bi bi-box-seam me-1 text-warning"></i> Angkutan Barang
                </label>
            </div>
        </div>
        @error('type')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Kepemilikan <span class="text-danger">*</span></label>
        <div class="d-flex gap-3 mt-1">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="ownership" id="own_owned" value="owned"
                       {{ old('ownership', $vehicle->ownership ?? 'owned') === 'owned' ? 'checked' : '' }} required>
                <label class="form-check-label" for="own_owned">Milik Perusahaan</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="ownership" id="own_rented" value="rented"
                       {{ old('ownership', $vehicle->ownership ?? '') === 'rented' ? 'checked' : '' }}>
                <label class="form-check-label" for="own_rented">Sewa</label>
            </div>
        </div>
        @error('ownership')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="available"   {{ old('status', $vehicle->status ?? 'available') === 'available'   ? 'selected' : '' }}>Tersedia</option>
            <option value="in_use"      {{ old('status', $vehicle->status ?? '') === 'in_use'      ? 'selected' : '' }}>Sedang Digunakan</option>
            <option value="maintenance" {{ old('status', $vehicle->status ?? '') === 'maintenance' ? 'selected' : '' }}>Perawatan / Servis</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Catatan</label>
        <textarea name="notes" rows="2"
                  class="form-control @error('notes') is-invalid @enderror"
                  placeholder="Catatan tambahan mengenai kendaraan (opsional)">{{ old('notes', $vehicle->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
