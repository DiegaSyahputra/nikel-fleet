@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-floppy me-1"></i> Simpan User
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
