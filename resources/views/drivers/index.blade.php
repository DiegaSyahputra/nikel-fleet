@extends('layouts.app')
@section('title', 'Master Driver')
@section('page-title', 'Master Data Driver')

@section('content')

    {{-- Alert SIM hampir kadaluarsa --}}
    @php
        $expiringSoon = \App\Models\Driver::whereBetween('license_expiry', [now(), now()->addDays(30)])
            ->where('status', '!=', 'off')
            ->count();
    @endphp
    @if ($expiringSoon > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3" style="font-size:13px;border-radius:10px">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>Ada <strong>{{ $expiringSoon }} driver</strong> dengan SIM yang akan kadaluarsa dalam 30 hari ke
                depan.</span>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius:10px">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary">{{ $drivers->total() }} driver</span>
                <a href="{{ route('drivers.create') }}" class="btn btn-primary btn-sm"
                    style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Driver
                </a>
            </div>

            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-sm-4 col-lg-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama / SIM / telepon..." value="{{ request('search') }}">
                </div>
                <div class="col-sm-3 col-lg-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia
                        </option>
                        <option value="on_duty" {{ request('status') === 'on_duty' ? 'selected' : '' }}>Sedang Bertugas
                        </option>
                        <option value="off" {{ request('status') === 'off' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
                <div class="col-sm-3 col-lg-2">
                    <select name="region_id" class="form-select form-select-sm">
                        <option value="">Semua Region</option>
                        @foreach ($regions as $r)
                            <option value="{{ $r->id }}" {{ request('region_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                    <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i
                            class="bi bi-x"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Driver</th>
                            <th>No. SIM</th>
                            <th>Kadaluarsa SIM</th>
                            <th>Telepon</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th style="width:110px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $d)
                            <tr>
                                <td class="fw-semibold">{{ $d->name }}</td>
                                <td><code style="font-size:12px">{{ $d->license_number }}</code></td>
                                <td>
                                    <span
                                        class="{{ $d->isLicenseExpiringSoon() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                        @if ($d->isLicenseExpiringSoon())
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        @endif
                                        {{ $d->license_expiry->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>{{ $d->phone }}</td>
                                <td><small>{{ $d->region->name }}</small></td>
                                <td>
                                    @php
                                        $badgeStyle = match ($d->status) {
                                            'available' => 'background:#d1e7dd;color:#0a3622',
                                            'on_duty' => 'background:#fff3cd;color:#664d03',
                                            default => 'background:#e2e3e5;color:#41464b',
                                        };
                                    @endphp
                                    <span class="badge" style="{{ $badgeStyle }};font-size:11px">
                                        {{ $d->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('drivers.show', $d) }}" class="btn btn-xs btn-outline-secondary"
                                            style="font-size:12px;padding:2px 7px">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('drivers.edit', $d) }}" class="btn btn-xs btn-outline-primary"
                                            style="font-size:12px;padding:2px 7px">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('drivers.destroy', $d) }}"
                                            onsubmit="return confirm('Hapus driver {{ $d->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger"
                                                style="font-size:12px;padding:2px 7px">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada driver ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $drivers->links() }}</div>
        </div>
    </div>
@endsection
