@extends('layouts.app')
@section('title', 'Edit Driver')
@section('page-title', 'Edit Driver — ' . $driver->name)

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('drivers.update', $driver) }}">
            @csrf @method('PUT')
            @include('drivers._form', ['driver' => $driver])
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-floppy me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('drivers.show', $driver) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
