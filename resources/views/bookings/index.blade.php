@extends('layouts.app')
@section('title', 'Pemesanan')
@section('page-title', 'Daftar Pemesanan')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body">
        {{-- Header + tombol buat --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Semua Pemesanan</h6>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-sm" style="background:#1e3a5f;border-color:#1e3a5f">
                <i class="bi bi-plus-lg me-1"></i> Buat Pemesanan
            </a>
            @endif
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-sm-4 col-lg-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending_l1" {{ request('status') === 'pending_l1' ? 'selected' : '' }}>Menunggu L1</option>
                    <option value="pending_l2" {{ request('status') === 'pending_l2' ? 'selected' : '' }}>Menunggu L2</option>
                    <option value="approved"   {{ request('status') === 'approved'   ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected"   {{ request('status') === 'rejected'   ? 'selected' : '' }}>Ditolak</option>
                    <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-sm-3 col-lg-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="Dari">
            </div>
            <div class="col-sm-3 col-lg-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="Sampai">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Pemohon</th>
                        <th>Kendaraan</th>
                        <th>Driver</th>
                        <th>Tujuan</th>
                        <th>Berangkat</th>
                        <th>Status</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td class="fw-semibold">{{ $b->booking_code }}</td>
                        <td>{{ $b->requester->name }}</td>
                        <td>
                            {{ $b->vehicle->brand }} {{ $b->vehicle->model }}
                            <br><small class="text-muted">{{ $b->vehicle->license_plate }}</small>
                        </td>
                        <td>{{ $b->driver->name }}</td>
                        <td class="text-truncate" style="max-width:160px">{{ $b->destination }}</td>
                        <td>{{ $b->departure_at->format('d/m/Y') }}<br><small class="text-muted">{{ $b->departure_at->format('H:i') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $b->status }}" style="font-size:11px">
                                {{ $b->getStatusLabel() }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $b) }}" class="btn btn-xs btn-outline-primary" style="font-size:12px;padding:2px 8px">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin() && in_array($b->status, ['pending_l1','pending_l2']))
                            <form method="POST" action="{{ route('bookings.destroy', $b) }}" class="d-inline"
                                  onsubmit="return confirm('Batalkan pemesanan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger" style="font-size:12px;padding:2px 8px">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada pemesanan ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
