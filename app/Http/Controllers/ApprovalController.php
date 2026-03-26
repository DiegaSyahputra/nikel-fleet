<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalService $approvalService) {}

    // Daftar pemesanan yang menunggu approval user ini
    public function index()
    {
        $user     = Auth::user();
        $bookings = $user->pendingApprovals()
            ->with(['requester', 'vehicle', 'driver'])
            ->latest()
            ->paginate(15);

        return view('approvals.index', compact('bookings'));
    }

    // Tampilkan form approve/reject
    public function show(Booking $booking)
    {
        abort_unless(
            $booking->isPendingFor(Auth::user()),
            403,
            'Pemesanan ini tidak memerlukan approval dari Anda saat ini.'
        );

        $booking->load(['requester', 'vehicle', 'driver', 'approvals.approver']);
        return view('approvals.show', compact('booking'));
    }

    // Proses approve atau reject
    public function process(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approved,rejected'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->approvalService->process(
                $booking,
                Auth::user(),
                $data['action'],
                $data['notes'] ?? null
            );

            $label = $data['action'] === 'approved' ? 'disetujui' : 'ditolak';
            return redirect()->route('approvals.index')
                ->with('success', "Pemesanan {$booking->booking_code} berhasil {$label}.");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
