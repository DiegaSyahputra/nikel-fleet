<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(private ApprovalService $approvalService) {}

    public function index(Request $request)
    {
        $query = Booking::with(['requester', 'vehicle', 'driver', 'approverL1', 'approverL2'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('departure_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('departure_at', '<=', $request->date_to);
        }

        // Approver hanya lihat booking yang melibatkan mereka
        $user = Auth::user();
        if ($user->isApprover()) {
            $query->where(function ($q) use ($user) {
                $q->where('approver_l1_id', $user->id)
                  ->orWhere('approver_l2_id', $user->id);
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $vehicles  = Vehicle::where('status', 'available')->orderBy('license_plate')->get();
        $drivers   = Driver::where('status', 'available')->orderBy('name')->get();
        $approvers = User::where('role', 'approver')->orderBy('name')->get();

        return view('bookings.create', compact('vehicles', 'drivers', 'approvers'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'vehicle_id'     => ['required', 'exists:vehicles,id'],
            'driver_id'      => ['required', 'exists:drivers,id'],
            'approver_l1_id' => ['required', 'exists:users,id', 'different:approver_l2_id'],
            'approver_l2_id' => ['required', 'exists:users,id'],
            'departure_at'   => ['required', 'date', 'after:now'],
            'return_at'      => ['required', 'date', 'after:departure_at'],
            'destination'    => ['required', 'string', 'max:255'],
            'purpose'        => ['required', 'string'],
            'passengers'     => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $data['booking_code'] = Booking::generateCode();
        $data['requester_id'] = Auth::id();
        $data['status']       = 'pending_l1';

        $booking = Booking::create($data);
        $this->approvalService->initApprovals($booking);

        return redirect()->route('bookings.show', $booking)
            ->with('success', "Pemesanan {$booking->booking_code} berhasil dibuat.");
    }

    public function show(Booking $booking)
    {
        $this->authorizeView($booking);
        $booking->load(['requester', 'vehicle', 'driver', 'approverL1', 'approverL2', 'approvals.approver']);
        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $this->authorizeAdmin();

        if (!in_array($booking->status, ['draft', 'pending_l1', 'pending_l2'])) {
            return back()->with('error', 'Pemesanan yang sudah diproses tidak dapat dihapus.');
        }

        $booking->update(['status' => 'cancelled']);
        return redirect()->route('bookings.index')->with('success', 'Pemesanan dibatalkan.');
    }

    // ── Helpers ────────────────────────────────────────────────
    private function authorizeAdmin(): void
    {
        abort_unless(Auth::user()->isAdmin(), 403, 'Hanya admin yang dapat melakukan aksi ini.');
    }

    private function authorizeView(Booking $booking): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) return;

        abort_unless(
            $booking->approver_l1_id === $user->id || $booking->approver_l2_id === $user->id,
            403, 'Anda tidak berwenang melihat pemesanan ini.'
        );
    }
}
