@extends('layouts.app')
@section('title', 'Tambah Region')
@section('page-title', 'Tambah Region Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('regions.store') }}">
            @csrf
            @include('regions._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-floppy me-1"></i> Simpan Region
                </button>
                <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
