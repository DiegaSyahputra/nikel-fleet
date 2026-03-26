@extends('layouts.app')
@section('title', 'Master Kendaraan')
@section('page-title', 'Master Data Kendaraan')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary me-1">{{ $vehicles->total() }} kendaraan</span>
            </div>
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary btn-sm" style="background:#1e3a5f;border-color:#1e3a5f">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kendaraan
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-sm-4 col-lg-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari plat / merek / model..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="passenger" {{ request('type') === 'passenger' ? 'selected' : '' }}>Angkutan Orang</option>
                    <option value="cargo"     {{ request('type') === 'cargo'     ? 'selected' : '' }}>Angkutan Barang</option>
                </select>
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="ownership" class="form-select form-select-sm">
                    <option value="">Semua Kepemilikan</option>
                    <option value="owned"  {{ request('ownership') === 'owned'  ? 'selected' : '' }}>Milik Perusahaan</option>
                    <option value="rented" {{ request('ownership') === 'rented' ? 'selected' : '' }}>Sewa</option>
                </select>
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="available"   {{ request('status') === 'available'   ? 'selected' : '' }}>Tersedia</option>
                    <option value="in_use"      {{ request('status') === 'in_use'      ? 'selected' : '' }}>Sedang Digunakan</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px">
                <thead class="table-light">
                    <tr>
                        <th>Kendaraan</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Kepemilikan</th>
                        <th>BBM</th>
                        <th>Region</th>
                        <th>Status</th>
                        <th style="width:110px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $v)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $v->brand }} {{ $v->model }}</span>
                            @if($v->year)<br><small class="text-muted">{{ $v->year }}{{ $v->color ? ' • ' . $v->color : '' }}</small>@endif
                        </td>
                        <td><span class="badge bg-light text-dark border" style="font-size:12px;letter-spacing:.5px">{{ $v->license_plate }}</span></td>
                        <td>
                            @if($v->type === 'passenger')
                                <span class="text-primary"><i class="bi bi-people me-1"></i>Orang</span>
                            @else
                                <span class="text-warning"><i class="bi bi-box-seam me-1"></i>Barang</span>
                            @endif
                        </td>
                        <td>
                            @if($v->ownership === 'owned')
                                <span class="text-muted"><i class="bi bi-building me-1"></i>Milik</span>
                            @else
                                <span class="text-info"><i class="bi bi-arrow-repeat me-1"></i>Sewa</span>
                            @endif
                        </td>
                        <td class="text-capitalize">{{ $v->fuel_type }}</td>
                        <td><small>{{ $v->region->name }}</small></td>
                        <td>
                            <span class="{{ $v->getStatusBadgeClass() }}" style="font-size:11px">
                                {{ $v->getStatusLabel() }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('vehicles.show', $v) }}"
                                   class="btn btn-xs btn-outline-secondary" style="font-size:12px;padding:2px 7px"
                                   title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('vehicles.edit', $v) }}"
                                   class="btn btn-xs btn-outline-primary" style="font-size:12px;padding:2px 7px"
                                   title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('vehicles.destroy', $v) }}"
                                      onsubmit="return confirm('Hapus kendaraan {{ $v->license_plate }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger"
                                            style="font-size:12px;padding:2px 7px" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada kendaraan ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $vehicles->links() }}</div>
    </div>
</div>
@endsection
