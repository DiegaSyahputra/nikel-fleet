<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pemesanan — admin & approver bisa lihat, hanya admin yang bisa buat/hapus
    Route::resource('bookings', BookingController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // Approval — semua authenticated user bisa akses, logika otorisasi di controller
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/',                           [ApprovalController::class, 'index'])->name('index');
        Route::get('/{booking}',                  [ApprovalController::class, 'show'])->name('show');
        Route::post('/{booking}/process',         [ApprovalController::class, 'process'])->name('process');
    });

    // Master data — hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers',  DriverController::class);
        Route::resource('regions',  RegionController::class);
        Route::resource('users',    UserController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',        [ReportController::class, 'index'])->name('index');
            Route::get('/export',  [ReportController::class, 'export'])->name('export');
        });
    });
});

require __DIR__.'/auth.php';
