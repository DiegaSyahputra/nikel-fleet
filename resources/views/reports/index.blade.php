@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Pemesanan')

@section('content')
    <div class="card border-0 shadow-sm" style="border-radius:10px">
        <div class="card-body">
            {{-- Filter & Export --}}
            <form method="GET" class="row g-2 align-items-end mb-4">
                <div class="col-sm-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="pending_l1" {{ request('status') === 'pending_l1' ? 'selected' : '' }}>Menunggu L1
                        </option>
                        <option value="pending_l2" {{ request('status') === 'pending_l2' ? 'selected' : '' }}>Menunggu L2
                        </option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold" style="font-size:12px">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('reports.export', request()->only('status', 'date_from', 'date_to')) }}"
                        class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </form>

            <p class="text-muted mb-2" style="font-size:12px">Menampilkan {{ $bookings->total() }} data</p>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Pemohon</th>
                            <th>Kendaraan</th>
                            <th>Driver</th>
                            <th>Tujuan</th>
                            <th>Berangkat</th>
                            <th>Approver L1</th>
                            <th>Approver L2</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $i => $b)
                            <tr>
                                <td class="text-muted">{{ $bookings->firstItem() + $i }}</td>
                                <td><a href="{{ route('bookings.show', $b) }}"
                                        class="fw-semibold text-decoration-none">{{ $b->booking_code }}</a></td>
                                <td>{{ $b->requester->name }}</td>
                                <td>{{ $b->vehicle->brand }} {{ $b->vehicle->model }}<br><small
                                        class="text-muted">{{ $b->vehicle->license_plate }}</small></td>
                                <td>{{ $b->driver->name }}</td>
                                <td class="text-truncate" style="max-width:140px">{{ $b->destination }}</td>
                                <td>{{ $b->departure_at->format('d/m/Y') }}</td>
                                <td>{{ $b->approverL1->name }}</td>
                                <td>{{ $b->approverL2->name }}</td>
                                <td><span class="badge badge-{{ $b->status }}"
                                        style="font-size:11px">{{ $b->getStatusLabel() }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bookings->links() }}</div>
        </div>
    </div>
@endsection
