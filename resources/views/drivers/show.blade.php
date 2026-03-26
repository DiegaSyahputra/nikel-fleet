@extends('layouts.app')
@section('title', 'Detail Driver')
@section('page-title', 'Detail Driver')

@section('content')
<div class="row g-3">

    {{-- Info driver --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4">
                {{-- Avatar --}}
                <div class="text-center mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-2"
                         style="width:72px;height:72px;background:#e6f1fb;font-size:1.5rem;font-weight:600;color:#185FA5">
                        {{ strtoupper(substr($driver->name, 0, 2)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $driver->name }}</h5>
                    @php
                        $badgeStyle = match($driver->status) {
                            'available' => 'background:#d1e7dd;color:#0a3622',
                            'on_duty'   => 'background:#fff3cd;color:#664d03',
                            default     => 'background:#e2e3e5;color:#41464b',
                        };
                    @endphp
                    <span class="badge" style="{{ $badgeStyle }};font-size:12px;padding:4px 12px">
                        {{ $driver->getStatusLabel() }}
                    </span>
                </div>

                <hr>

                <div style="font-size:13px">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Telepon</span>
                        <span class="fw-semibold">{{ $driver->phone }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Nomor SIM</span>
                        <code style="font-size:12px">{{ $driver->license_number }}</code>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Kadaluarsa SIM</span>
                        <span class="{{ $driver->isLicenseExpiringSoon() ? 'text-danger fw-semibold' : 'fw-semibold' }}">
                            @if($driver->isLicenseExpiringSoon())
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            @endif
                            {{ $driver->license_expiry->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Region</span>
                        <span class="fw-semibold">{{ $driver->region->name }}</span>
                    </div>
                </div>

                @if($driver->isLicenseExpiringSoon())
                <div class="alert alert-warning py-2 mt-3 mb-0" style="font-size:12px">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    SIM perlu segera diperpanjang!
                </div>
                @endif

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat pemesanan --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Riwayat Penugasan (10 Terakhir)</h6>
                @if($bookings->isEmpty())
                    <p class="text-muted text-center py-3" style="font-size:13px">Belum ada riwayat penugasan</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                        <thead class="table-light">
                            <tr><th>Kode</th><th>Pemohon</th><th>Kendaraan</th><th>Tujuan</th><th>Berangkat</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                            <tr>
                                <td><a href="{{ route('bookings.show', $b) }}" class="fw-semibold text-decoration-none">{{ $b->booking_code }}</a></td>
                                <td>{{ $b->requester->name }}</td>
                                <td>{{ $b->vehicle->brand }} {{ $b->vehicle->model }}<br><small class="text-muted">{{ $b->vehicle->license_plate }}</small></td>
                                <td class="text-truncate" style="max-width:120px">{{ $b->destination }}</td>
                                <td>{{ $b->departure_at->format('d/m/Y') }}</td>
                                <td><span class="badge badge-{{ $b->status }}" style="font-size:10px">{{ $b->getStatusLabel() }}</span></td>
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
