@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 h-100" style="background:#1e3a5f;color:#fff;border-radius:10px">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="font-size:12px;opacity:.7">Total Pemesanan</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_bookings'] }}</h3>
                        </div>
                        <i class="bi bi-calendar3" style="font-size:1.8rem;opacity:.4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 h-100" style="background:#fff3cd;border-radius:10px">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted" style="font-size:12px">Menunggu Approval</p>
                            <h3 class="mb-0 fw-bold text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split text-warning" style="font-size:1.8rem;opacity:.6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 h-100" style="background:#d1e7dd;border-radius:10px">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted" style="font-size:12px">Disetujui</p>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['approved'] }}</h3>
                        </div>
                        <i class="bi bi-check-circle text-success" style="font-size:1.8rem;opacity:.6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 h-100" style="background:#f8d7da;border-radius:10px">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 text-muted" style="font-size:12px">Kendaraan Terpakai</p>
                            <h3 class="mb-0 fw-bold text-danger">{{ $stats['vehicles_in_use'] }}</h3>
                        </div>
                        <i class="bi bi-truck text-danger" style="font-size:1.8rem;opacity:.6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Pemesanan per Bulan</h6>
                    <canvas id="chartMonthly" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Pemakaian per Kendaraan</h6>
                    <canvas id="chartVehicle" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Pemesanan terbaru --}}
    <div class="card border-0 shadow-sm" style="border-radius:10px">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Pemesanan Terbaru</h6>
                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Pemohon</th>
                            <th>Kendaraan</th>
                            <th>Tujuan</th>
                            <th>Berangkat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $b)
                            <tr>
                                <td><a href="{{ route('bookings.show', $b) }}"
                                        class="text-decoration-none fw-semibold">{{ $b->booking_code }}</a></td>
                                <td>{{ $b->requester->name }}</td>
                                <td>{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</td>
                                <td class="text-truncate" style="max-width:150px">{{ $b->destination }}</td>
                                <td>{{ $b->departure_at->format('d/m/Y') }}</td>
                                <td><span class="badge badge-{{ $b->status }}"
                                        style="font-size:11px">{{ $b->getStatusLabel() }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada pemesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const monthly = @json($chartData);
        const vehicles = @json($vehicleChart);

        // Grafik bulanan
        new Chart(document.getElementById('chartMonthly'), {
            type: 'bar',
            data: {
                labels: monthly.map(d => d.month),
                datasets: [{
                        label: 'Disetujui',
                        data: monthly.map(d => d.approved),
                        backgroundColor: '#198754',
                        borderRadius: 4
                    },
                    {
                        label: 'Ditolak',
                        data: monthly.map(d => d.rejected),
                        backgroundColor: '#dc3545',
                        borderRadius: 4
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Grafik kendaraan
        new Chart(document.getElementById('chartVehicle'), {
            type: 'doughnut',
            data: {
                labels: vehicles.map(v => v.license_plate),
                datasets: [{
                    data: vehicles.map(v => v.total),
                    backgroundColor: ['#1e3a5f', '#2563eb', '#0ea5e9', '#06b6d4', '#14b8a6', '#10b981',
                        '#84cc16', '#f59e0b', '#f97316', '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    </script>
@endsection
