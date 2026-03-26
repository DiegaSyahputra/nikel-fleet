@extends('layouts.app')
@section('title', 'Buat Pemesanan')
@section('page-title', 'Buat Pemesanan Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('bookings.store') }}">
            @csrf

            <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:.5px">Detail Perjalanan</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal & Jam Berangkat <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="departure_at" class="form-control @error('departure_at') is-invalid @enderror"
                           value="{{ old('departure_at') }}" required>
                    @error('departure_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal & Jam Kembali <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="return_at" class="form-control @error('return_at') is-invalid @enderror"
                           value="{{ old('return_at') }}" required>
                    @error('return_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror"
                           value="{{ old('destination') }}" placeholder="Nama lokasi tujuan" required>
                    @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Penumpang <span class="text-danger">*</span></label>
                    <input type="number" name="passengers" class="form-control @error('passengers') is-invalid @enderror"
                           value="{{ old('passengers', 1) }}" min="1" max="50" required>
                    @error('passengers')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Keperluan / Tujuan Perjalanan <span class="text-danger">*</span></label>
                    <textarea name="purpose" rows="3" class="form-control @error('purpose') is-invalid @enderror"
                              placeholder="Jelaskan keperluan perjalanan..." required>{{ old('purpose') }}</textarea>
                    @error('purpose')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:.5px">Kendaraan & Driver</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kendaraan <span class="text-danger">*</span></label>
                    <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                        <option value="">— Pilih Kendaraan —</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->license_plate }} — {{ $v->brand }} {{ $v->model }} ({{ $v->getTypeLabel() }})
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Driver <span class="text-danger">*</span></label>
                    <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                        <option value="">— Pilih Driver —</option>
                        @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:.5px">Alur Persetujuan</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Approver Level 1 <span class="text-danger">*</span></label>
                    <select name="approver_l1_id" class="form-select @error('approver_l1_id') is-invalid @enderror" required>
                        <option value="">— Pilih Approver L1 —</option>
                        @foreach($approvers as $a)
                        <option value="{{ $a->id }}" {{ old('approver_l1_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }} — {{ $a->position }}
                        </option>
                        @endforeach
                    </select>
                    @error('approver_l1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Approver Level 2 <span class="text-danger">*</span></label>
                    <select name="approver_l2_id" class="form-select @error('approver_l2_id') is-invalid @enderror" required>
                        <option value="">— Pilih Approver L2 —</option>
                        @foreach($approvers as $a)
                        <option value="{{ $a->id }}" {{ old('approver_l2_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }} — {{ $a->position }}
                        </option>
                        @endforeach
                    </select>
                    @error('approver_l2_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-send me-1"></i> Kirim Pemesanan
                </button>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
