<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    /**
     * Membuat record approval saat booking dibuat.
     * Dipanggil sekali oleh BookingController@store.
     */
    public function initApprovals(Booking $booking): void
    {
        BookingApproval::create([
            'booking_id'  => $booking->id,
            'approver_id' => $booking->approver_l1_id,
            'level'       => 1,
            'status'      => 'pending',
        ]);

        BookingApproval::create([
            'booking_id'  => $booking->id,
            'approver_id' => $booking->approver_l2_id,
            'level'       => 2,
            'status'      => 'pending',
        ]);
    }

    /**
     * Proses aksi approve atau reject dari approver.
     *
     * @throws \Exception jika user tidak berhak
     */
    public function process(Booking $booking, User $actor, string $action, ?string $notes): void
    {
        if (!$booking->isPendingFor($actor)) {
            throw new \Exception('Anda tidak berwenang memproses pemesanan ini.');
        }

        DB::transaction(function () use ($booking, $actor, $action, $notes) {
            $level = $booking->status === 'pending_l1' ? 1 : 2;

            // Update record approval untuk level ini
            BookingApproval::where('booking_id', $booking->id)
                ->where('level', $level)
                ->update([
                    'status'      => $action,        // 'approved' atau 'rejected'
                    'notes'       => $notes,
                    'approved_at' => now(),
                ]);

            if ($action === 'approved') {
                if ($level === 1) {
                    // L1 setuju → lanjut ke L2
                    $booking->update(['status' => 'pending_l2']);
                } else {
                    // L2 setuju → selesai, kunci kendaraan & driver
                    $booking->update(['status' => 'approved']);
                    $booking->vehicle->update(['status' => 'in_use']);
                    $booking->driver->update(['status'  => 'on_duty']);
                }
            } else {
                // Ditolak di level manapun → booking langsung rejected
                $booking->update(['status' => 'rejected']);
            }
        });
    }
}
