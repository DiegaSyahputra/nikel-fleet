<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['requester', 'vehicle', 'driver', 'approverL1', 'approverL2'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('departure_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('departure_at', '<=', $request->date_to);
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('reports.index', compact('bookings'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);
        $filename = 'laporan-pemesanan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new BookingsExport($filters), $filename);
    }
}
