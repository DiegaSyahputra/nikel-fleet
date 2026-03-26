@extends('layouts.app')
@section('title', 'Detail Kendaraan')
@section('page-title', 'Detail Kendaraan')

@section('content')
<div class="row g-3">

    {{-- Info kendaraan --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $vehicle->brand }} {{ $vehicle->model }}</h5>
                        <span class="badge bg-light text-dark border" style="font-size:13px;letter-spacing:.5px">
                            {{ $vehicle->license_plate }}
                        </span>
                    </div>
                    <span class="{{ $vehicle->getStatusBadgeClass() }} py-2 px-3" style="font-size:12px">
                        {{ $vehicle->getStatusLabel() }}
                    </span>
                </div>

                <hr>

                <div class="row g-2" style="font-size:13px">
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Jenis</p>
                        <p class="fw-semibold mb-0">{{ $vehicle->getTypeLabel() }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Kepemilikan</p>
                        <p class="fw-semibold mb-0">{{ $vehicle->getOwnershipLabel() }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Tahun</p>
                        <p class="fw-semibold mb-0">{{ $vehicle->year ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Warna</p>
                        <p class="fw-semibold mb-0">{{ $vehicle->color ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Bahan Bakar</p>
                        <p class="fw-semibold mb-0 text-capitalize">{{ $vehicle->fuel_type }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1" style="font-size:11px">Region</p>
                        <p class="fw-semibold mb-0">{{ $vehicle->region->name }}</p>
                    </div>
                    @if($vehicle->notes)
                    <div class="col-12">
                        <p class="text-muted mb-1" style="font-size:11px">Catatan</p>
                        <p class="mb-0">{{ $vehicle->notes }}</p>
                    </div>
                    @endif
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat pemesanan --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Riwayat Pemesanan (10 Terakhir)</h6>
                @if($bookings->isEmpty())
                    <p class="text-muted text-center py-3" style="font-size:13px">Belum ada riwayat pemesanan</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                        <thead class="table-light">
                            <tr><th>Kode</th><th>Pemohon</th><th>Driver</th><th>Berangkat</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                            <tr>
                                <td><a href="{{ route('bookings.show', $b) }}" class="fw-semibold text-decoration-none">{{ $b->booking_code }}</a></td>
                                <td>{{ $b->requester->name }}</td>
                                <td>{{ $b->driver->name }}</td>
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
