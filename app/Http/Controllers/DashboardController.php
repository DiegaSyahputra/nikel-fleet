<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik ringkasan
        $stats = [
            'total_bookings'   => Booking::count(),
            'pending'          => Booking::whereIn('status', ['pending_l1', 'pending_l2'])->count(),
            'approved'         => Booking::where('status', 'approved')->count(),
            'vehicles_in_use'  => Vehicle::where('status', 'in_use')->count(),
        ];

        // Untuk approver: berapa yang menunggu approval mereka
        if ($user->isApprover()) {
            $stats['my_pending'] = $user->pendingApprovals()->count();
        }

        // Data grafik: jumlah booking per bulan (12 bulan terakhir)
        $chartData = Booking::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(status = 'approved') as approved"),
                DB::raw("SUM(status = 'rejected') as rejected")
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Data grafik: pemakaian per kendaraan (top 10)
        $vehicleChart = Booking::select(
                'vehicles.license_plate',
                DB::raw('COUNT(bookings.id) as total')
            )
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->where('bookings.status', 'approved')
            ->groupBy('vehicles.id', 'vehicles.license_plate')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Pemesanan terbaru
        $recentBookings = Booking::with(['requester', 'vehicle', 'driver'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 'chartData', 'vehicleChart', 'recentBookings'
        ));
    }
}
