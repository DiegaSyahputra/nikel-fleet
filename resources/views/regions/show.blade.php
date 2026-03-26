@extends('layouts.app')
@section('title', 'Detail Region')
@section('page-title', 'Detail Region')

@section('content')
<div class="row g-3">

    {{-- Info region --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4">
                @php
                    $typeStyle = match($region->type) {
                        'head_office' => ['bg' => '#E6F1FB', 'color' => '#185FA5', 'icon' => 'building'],
                        'branch'      => ['bg' => '#EEEDFE', 'color' => '#3C3489', 'icon' => 'diagram-3'],
                        'mine'        => ['bg' => '#FAEEDA', 'color' => '#854F0B', 'icon' => 'geo-alt'],
                    };
                @endphp
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                         style="width:64px;height:64px;background:{{ $typeStyle['bg'] }}">
                        <i class="bi bi-{{ $typeStyle['icon'] }}" style="font-size:1.6rem;color:{{ $typeStyle['color'] }}"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $region->name }}</h5>
                    <span class="badge" style="background:{{ $typeStyle['bg'] }};color:{{ $typeStyle['color'] }};font-size:12px">
                        {{ $region->getTypeLabel() }}
                    </span>
                </div>

                @if($region->address)
                <div class="p-3 rounded mb-3" style="background:#f8f9fa;font-size:13px">
                    <i class="bi bi-geo-alt text-muted me-1"></i> {{ $region->address }}
                </div>
                @endif

                {{-- Stat counts --}}
                <div class="row g-2 text-center mb-3">
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#f8f9fa">
                            <div class="fw-bold" style="font-size:20px">{{ $region->users_count }}</div>
                            <div class="text-muted" style="font-size:11px">User</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#f8f9fa">
                            <div class="fw-bold" style="font-size:20px">{{ $region->vehicles_count }}</div>
                            <div class="text-muted" style="font-size:11px">Kendaraan</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#f8f9fa">
                            <div class="fw-bold" style="font-size:20px">{{ $region->drivers_count }}</div>
                            <div class="text-muted" style="font-size:11px">Driver</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('regions.edit', $region) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('regions.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Isi region --}}
    <div class="col-lg-8">
        {{-- Users --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:10px">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0">User di Region Ini</h6>
                    <a href="{{ route('users.create') }}" class="btn btn-xs btn-outline-primary" style="font-size:12px;padding:2px 8px">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
                @if($region->users->isEmpty())
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada user</p>
                @else
                <div class="row g-2">
                    @foreach($region->users as $u)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:#f8f9fa">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:32px;height:32px;background:#E6F1FB;font-size:11px;font-weight:600;color:#185FA5">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div style="min-width:0">
                                <a href="{{ route('users.show', $u) }}" class="fw-semibold text-decoration-none d-block text-truncate" style="font-size:13px">{{ $u->name }}</a>
                                <small class="text-muted">{{ $u->role }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Kendaraan --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Kendaraan (5 Terbaru)</h6>
                @if($region->vehicles->isEmpty())
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada kendaraan</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size:12px">
                        <thead class="table-light"><tr><th>Kendaraan</th><th>Plat</th><th>Jenis</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($region->vehicles as $v)
                            <tr>
                                <td><a href="{{ route('vehicles.show', $v) }}" class="text-decoration-none">{{ $v->brand }} {{ $v->model }}</a></td>
                                <td><code style="font-size:11px">{{ $v->license_plate }}</code></td>
                                <td>{{ $v->getTypeLabel() }}</td>
                                <td><span class="{{ $v->getStatusBadgeClass() }}" style="font-size:10px">{{ $v->getStatusLabel() }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Driver --}}
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Driver (5 Terbaru)</h6>
                @if($region->drivers->isEmpty())
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada driver</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size:12px">
                        <thead class="table-light"><tr><th>Nama</th><th>No. SIM</th><th>Kadaluarsa</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($region->drivers as $d)
                            <tr>
                                <td><a href="{{ route('drivers.show', $d) }}" class="text-decoration-none">{{ $d->name }}</a></td>
                                <td><code style="font-size:11px">{{ $d->license_number }}</code></td>
                                <td class="{{ $d->isLicenseExpiringSoon() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $d->license_expiry->format('d/m/Y') }}
                                </td>
                                <td><span style="font-size:10px" class="badge {{ $d->status === 'available' ? 'bg-success' : ($d->status === 'on_duty' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ $d->getStatusLabel() }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
