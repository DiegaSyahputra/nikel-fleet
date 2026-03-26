@extends('layouts.app')
@section('title', 'Persetujuan')
@section('page-title', 'Menunggu Persetujuan Saya')

@section('content')
    <div class="card border-0 shadow-sm" style="border-radius:10px">
        <div class="card-body">
            @if ($bookings->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check2-circle" style="font-size:3rem;opacity:.3"></i>
                    <p class="mt-2">Tidak ada pemesanan yang menunggu persetujuan Anda saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:13px">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pemohon</th>
                                <th>Kendaraan</th>
                                <th>Tujuan</th>
                                <th>Berangkat</th>
                                <th>Level</th>
                                <th style="width:100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $b)
                                @php
                                    $myLevel =
                                        $b->approver_l1_id === auth()->id() && $b->status === 'pending_l1' ? 1 : 2;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $b->booking_code }}</td>
                                    <td>{{ $b->requester->name }}</td>
                                    <td>
                                        {{ $b->vehicle->brand }} {{ $b->vehicle->model }}
                                        <br><small class="text-muted">{{ $b->vehicle->license_plate }}</small>
                                    </td>
                                    <td class="text-truncate" style="max-width:160px">{{ $b->destination }}</td>
                                    <td>{{ $b->departure_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge"
                                            style="background:{{ $myLevel === 1 ? '#cfe2ff' : '#d1e7dd' }};color:{{ $myLevel === 1 ? '#084298' : '#0a3622' }}">
                                            Level {{ $myLevel }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('bookings.show', $b) }}" class="btn btn-sm btn-primary"
                                            style="background:#1e3a5f;border-color:#1e3a5f;font-size:12px">
                                            <i class="bi bi-pencil-square me-1"></i> Proses
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $bookings->links() }}</div>
            @endif
        </div>
    </div>
@endsection
