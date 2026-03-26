@extends('layouts.app')
@section('title', 'Master User')
@section('page-title', 'Master Data User')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary">{{ $users->total() }} user</span>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" style="background:#1e3a5f;border-color:#1e3a5f">
                <i class="bi bi-plus-lg me-1"></i> Tambah User
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-sm-4 col-lg-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama / email / jabatan..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="approver" {{ request('role') === 'approver' ? 'selected' : '' }}>Approver</option>
                </select>
            </div>
            <div class="col-sm-3 col-lg-2">
                <select name="region_id" class="form-select form-select-sm">
                    <option value="">Semua Region</option>
                    @foreach($regions as $r)
                    <option value="{{ $r->id }}" {{ request('region_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-x"></i></a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Jabatan</th>
                        <th>Region</th>
                        <th>Telepon</th>
                        <th style="width:110px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:34px;height:34px;background:{{ $u->role === 'admin' ? '#E6F1FB' : '#EEEDFE' }};font-size:11px;font-weight:600;color:{{ $u->role === 'admin' ? '#185FA5' : '#3C3489' }}">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span class="fw-semibold">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-success" style="font-size:10px">Saya</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td>
                            <span class="badge"
                                  style="{{ $u->role === 'admin' ? 'background:#E6F1FB;color:#185FA5' : 'background:#EEEDFE;color:#3C3489' }};font-size:11px">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $u->position ?? '-' }}</td>
                        <td><small>{{ $u->region->name }}</small></td>
                        <td class="text-muted">{{ $u->phone ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.show', $u) }}"
                                   class="btn btn-xs btn-outline-secondary" style="font-size:12px;padding:2px 7px">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $u) }}"
                                   class="btn btn-xs btn-outline-primary" style="font-size:12px;padding:2px 7px">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}"
                                      onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger"
                                            style="font-size:12px;padding:2px 7px">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada user ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
