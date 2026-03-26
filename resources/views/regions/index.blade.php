@extends('layouts.app')
@section('title', 'Master Region')
@section('page-title', 'Master Data Region')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary">{{ $regions->total() }} region</span>
            <a href="{{ route('regions.create') }}" class="btn btn-primary btn-sm" style="background:#1e3a5f;border-color:#1e3a5f">
                <i class="bi bi-plus-lg me-1"></i> Tambah Region
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-sm-5 col-lg-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama region..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Tipe</option>
                    <option value="head_office" {{ request('type') === 'head_office' ? 'selected' : '' }}>Kantor Pusat</option>
                    <option value="branch"      {{ request('type') === 'branch'      ? 'selected' : '' }}>Kantor Cabang</option>
                    <option value="mine"        {{ request('type') === 'mine'        ? 'selected' : '' }}>Tambang</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('regions.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px">
                <thead class="table-light">
                    <tr>
                        <th>Nama Region</th>
                        <th>Tipe</th>
                        <th>Alamat</th>
                        <th class="text-center">User</th>
                        <th class="text-center">Kendaraan</th>
                        <th class="text-center">Driver</th>
                        <th style="width:110px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $r)
                    <tr>
                        <td class="fw-semibold">{{ $r->name }}</td>
                        <td>
                            @php
                                $typeStyle = match($r->type) {
                                    'head_office' => 'background:#E6F1FB;color:#185FA5',
                                    'branch'      => 'background:#EEEDFE;color:#3C3489',
                                    'mine'        => 'background:#FAEEDA;color:#854F0B',
                                };
                            @endphp
                            <span class="badge" style="{{ $typeStyle }};font-size:11px">
                                {{ $r->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="text-muted text-truncate" style="max-width:200px">{{ $r->address ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $r->users_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $r->vehicles_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $r->drivers_count }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('regions.show', $r) }}"
                                   class="btn btn-xs btn-outline-secondary" style="font-size:12px;padding:2px 7px">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('regions.edit', $r) }}"
                                   class="btn btn-xs btn-outline-primary" style="font-size:12px;padding:2px 7px">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('regions.destroy', $r) }}"
                                      onsubmit="return confirm('Hapus region {{ $r->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger"
                                            style="font-size:12px;padding:2px 7px">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada region ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $regions->links() }}</div>
    </div>
</div>
@endsection
