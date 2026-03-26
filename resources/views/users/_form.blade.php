{{-- Partial: resources/views/users/_form.blade.php --}}
<div class="row g-3">
    <div class="col-12">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Informasi Akun</h6>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}"
               placeholder="Nama lengkap" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="nama@perusahaan.com" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Password <span class="text-danger">*</span>
            @isset($user)
                <small class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</small>
            @endisset
        </label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="{{ isset($user) ? '••••••••' : 'Min. 8 karakter' }}"
               {{ isset($user) ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Konfirmasi Password
            @if(!isset($user))<span class="text-danger">*</span>@endif
        </label>
        <input type="password" name="password_confirmation"
               class="form-control"
               placeholder="Ulangi password"
               {{ isset($user) ? '' : 'required' }}>
    </div>

    <div class="col-12 mt-2">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:11px;letter-spacing:.5px">Profil & Penugasan</h6>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="admin"    {{ old('role', $user->role ?? '') === 'admin'    ? 'selected' : '' }}>Admin</option>
            <option value="approver" {{ old('role', $user->role ?? '') === 'approver' ? 'selected' : '' }}>Approver</option>
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Admin: kelola semua data. Approver: hanya menyetujui pemesanan.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Jabatan</label>
        <input type="text" name="position"
               class="form-control @error('position') is-invalid @enderror"
               value="{{ old('position', $user->position ?? '') }}"
               placeholder="Contoh: Kepala Bagian Umum">
        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Nomor Telepon</label>
        <input type="text" name="phone"
               class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $user->phone ?? '') }}"
               placeholder="081234567890">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
        <select name="region_id" class="form-select @error('region_id') is-invalid @enderror" required>
            <option value="">— Pilih Region —</option>
            @foreach($regions as $r)
                <option value="{{ $r->id }}"
                    {{ old('region_id', $user->region_id ?? '') == $r->id ? 'selected' : '' }}>
                    {{ $r->name }} — {{ $r->getTypeLabel() }}
                </option>
            @endforeach
        </select>
        @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
