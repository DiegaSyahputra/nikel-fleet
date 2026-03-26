{{-- Partial: resources/views/drivers/_form.blade.php --}}
<div class="row g-3">
    <div class="col-12">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Data Diri</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $driver->name ?? '') }}"
               placeholder="Nama sesuai KTP" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text" style="font-size:13px">+62</span>
            <input type="text" name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $driver->phone ?? '') }}"
                   placeholder="081234567890" required>
        </div>
        @error('phone')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mt-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Data SIM</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nomor SIM <span class="text-danger">*</span></label>
        <input type="text" name="license_number"
               class="form-control @error('license_number') is-invalid @enderror"
               value="{{ old('license_number', $driver->license_number ?? '') }}"
               placeholder="SIM-001-2024" required>
        @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Tanggal Kadaluarsa SIM <span class="text-danger">*</span></label>
        <input type="date" name="license_expiry"
               class="form-control @error('license_expiry') is-invalid @enderror"
               value="{{ old('license_expiry', isset($driver) ? $driver->license_expiry->format('Y-m-d') : '') }}"
               required>
        @error('license_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror

        @if(isset($driver) && $driver->isLicenseExpiringSoon())
        <div class="mt-1 d-flex align-items-center gap-1" style="font-size:12px;color:#854F0B">
            <i class="bi bi-exclamation-triangle-fill"></i>
            SIM akan kadaluarsa dalam waktu dekat!
        </div>
        @endif
    </div>

    <div class="col-12 mt-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Penugasan</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Region / Lokasi Tugas <span class="text-danger">*</span></label>
        <select name="region_id" class="form-select @error('region_id') is-invalid @enderror" required>
            <option value="">— Pilih Region —</option>
            @foreach($regions as $r)
                <option value="{{ $r->id }}"
                    {{ old('region_id', $driver->region_id ?? '') == $r->id ? 'selected' : '' }}>
                    {{ $r->name }} ({{ $r->getTypeLabel() }})
                </option>
            @endforeach
        </select>
        @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="available" {{ old('status', $driver->status ?? 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="on_duty"   {{ old('status', $driver->status ?? '') === 'on_duty'   ? 'selected' : '' }}>Sedang Bertugas</option>
            <option value="off"       {{ old('status', $driver->status ?? '') === 'off'       ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
