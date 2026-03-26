@extends('layouts.app')
@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="row g-3">

    {{-- Profil --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                         style="width:72px;height:72px;background:{{ $user->role === 'admin' ? '#E6F1FB' : '#EEEDFE' }};font-size:1.6rem;font-weight:600;color:{{ $user->role === 'admin' ? '#185FA5' : '#3C3489' }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <span class="badge"
                          style="{{ $user->role === 'admin' ? 'background:#E6F1FB;color:#185FA5' : 'background:#EEEDFE;color:#3C3489' }};font-size:12px;padding:4px 12px">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <hr>

                <div style="font-size:13px">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Email</span>
                        <span class="fw-semibold text-truncate ms-2" style="max-width:170px">{{ $user->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Jabatan</span>
                        <span class="fw-semibold">{{ $user->position ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Telepon</span>
                        <span class="fw-semibold">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Region</span>
                        <span class="fw-semibold">{{ $user->region->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Bergabung</span>
                        <span class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Aktivitas --}}
    <div class="col-lg-8">
        {{-- Pemesanan yang dibuat (hanya admin) --}}
        @if($user->isAdmin())
        <div class="card border-0 shadow-sm mb-3" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Pemesanan yang Dibuat (5 Terakhir)</h6>
                @if($bookingsMade->isEmpty())
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada pemesanan</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                        <thead class="table-light"><tr><th>Kode</th><th>Kendaraan</th><th>Tujuan</th><th>Berangkat</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($bookingsMade as $b)
                            <tr>
                                <td><a href="{{ route('bookings.show', $b) }}" class="fw-semibold text-decoration-none">{{ $b->booking_code }}</a></td>
                                <td>{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</td>
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
        @endif

        {{-- Riwayat approval --}}
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Riwayat Persetujuan (5 Terakhir)</h6>
                @if($approvalsHandled->isEmpty())
                    <p class="text-muted mb-0" style="font-size:13px">Belum ada riwayat persetujuan</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                        <thead class="table-light"><tr><th>Kode Pemesanan</th><th>Level</th><th>Keputusan</th><th>Catatan</th><th>Tanggal</th></tr></thead>
                        <tbody>
                            @foreach($approvalsHandled as $a)
                            <tr>
                                <td>
                                    <a href="{{ route('bookings.show', $a->booking) }}" class="fw-semibold text-decoration-none">
                                        {{ $a->booking->booking_code }}
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary" style="font-size:10px">Level {{ $a->level }}</span></td>
                                <td>
                                    @if($a->status === 'approved')
                                        <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                    @elseif($a->status === 'rejected')
                                        <span class="text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                    @else
                                        <span class="text-muted">Menunggu</span>
                                    @endif
                                </td>
                                <td class="text-muted text-truncate" style="max-width:120px">{{ $a->notes ?? '-' }}</td>
                                <td>{{ $a->approved_at ? $a->approved_at->format('d/m/Y') : '-' }}</td>
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
