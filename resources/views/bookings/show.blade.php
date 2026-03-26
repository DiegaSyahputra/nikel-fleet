@extends('layouts.app')
@section('title', 'Detail Pemesanan')
@section('page-title', 'Detail Pemesanan')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:10px">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $booking->booking_code }}</h5>
                        <small class="text-muted">Dibuat {{ $booking->created_at->diffForHumans() }} oleh {{ $booking->requester->name }}</small>
                    </div>
                    <span class="badge badge-{{ $booking->status }} py-2 px-3" style="font-size:12px">
                        {{ $booking->getStatusLabel() }}
                    </span>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Tujuan</p>
                        <p class="fw-semibold mb-0">{{ $booking->destination }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Jumlah Penumpang</p>
                        <p class="fw-semibold mb-0">{{ $booking->passengers }} orang</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Tanggal Berangkat</p>
                        <p class="fw-semibold mb-0">{{ $booking->departure_at->isoFormat('dddd, D MMMM Y — HH:mm') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Tanggal Kembali</p>
                        <p class="fw-semibold mb-0">{{ $booking->return_at->isoFormat('dddd, D MMMM Y — HH:mm') }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted mb-1" style="font-size:12px">Keperluan</p>
                        <p class="mb-0">{{ $booking->purpose }}</p>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Kendaraan</p>
                        <p class="fw-semibold mb-0">
                            {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                            <span class="badge bg-secondary ms-1" style="font-size:10px">{{ $booking->vehicle->license_plate }}</span>
                        </p>
                        <small class="text-muted">{{ $booking->vehicle->getTypeLabel() }} • {{ $booking->vehicle->getOwnershipLabel() }}</small>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted mb-1" style="font-size:12px">Driver</p>
                        <p class="fw-semibold mb-0">{{ $booking->driver->name }}</p>
                        <small class="text-muted">{{ $booking->driver->phone }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approval actions (untuk approver yang berhak) --}}
        @if($booking->isPendingFor(auth()->user()))
        <div class="card border-warning border-0 shadow-sm" style="border-radius:10px;border-left:4px solid #ffc107 !important">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-exclamation-circle text-warning me-1"></i> Tindakan Diperlukan</h6>
                <form method="POST" action="{{ route('approvals.process', $booking) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Tambahkan catatan persetujuan atau alasan penolakan..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="approved" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Setujui
                        </button>
                        <button type="submit" name="action" value="rejected" class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menolak pemesanan ini?')">
                            <i class="bi bi-x-lg me-1"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        {{-- Timeline approval --}}
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Alur Persetujuan</h6>

                {{-- L1 --}}
                @php $l1 = $booking->approvals->firstWhere('level', 1); @endphp
                <div class="d-flex gap-3 mb-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;background:{{ $l1?->status === 'approved' ? '#d1e7dd' : ($l1?->status === 'rejected' ? '#f8d7da' : '#fff3cd') }}">
                            <i class="bi bi-{{ $l1?->status === 'approved' ? 'check-lg text-success' : ($l1?->status === 'rejected' ? 'x-lg text-danger' : 'hourglass-split text-warning') }}" style="font-size:14px"></i>
                        </div>
                        <div style="width:1px;flex:1;background:#dee2e6;margin:4px 0"></div>
                    </div>
                    <div class="pb-3">
                        <p class="mb-0 fw-semibold" style="font-size:13px">Level 1 — {{ $booking->approverL1->name }}</p>
                        <small class="text-muted">{{ $booking->approverL1->position }}</small>
                        @if($l1?->status !== 'pending')
                            <div class="mt-1 p-2 rounded" style="background:#f8f9fa;font-size:12px">
                                <strong>{{ $l1->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</strong>
                                pada {{ $l1->approved_at?->format('d/m/Y H:i') }}
                                @if($l1->notes)<br><em>{{ $l1->notes }}</em>@endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- L2 --}}
                @php $l2 = $booking->approvals->firstWhere('level', 2); @endphp
                <div class="d-flex gap-3">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;background:{{ $l2?->status === 'approved' ? '#d1e7dd' : ($l2?->status === 'rejected' ? '#f8d7da' : '#e9ecef') }}">
                            <i class="bi bi-{{ $l2?->status === 'approved' ? 'check-lg text-success' : ($l2?->status === 'rejected' ? 'x-lg text-danger' : 'dash text-muted') }}" style="font-size:14px"></i>
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size:13px">Level 2 — {{ $booking->approverL2->name }}</p>
                        <small class="text-muted">{{ $booking->approverL2->position }}</small>
                        @if($l2?->status !== 'pending')
                            <div class="mt-1 p-2 rounded" style="background:#f8f9fa;font-size:12px">
                                <strong>{{ $l2->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</strong>
                                pada {{ $l2->approved_at?->format('d/m/Y H:i') }}
                                @if($l2->notes)<br><em>{{ $l2->notes }}</em>@endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
