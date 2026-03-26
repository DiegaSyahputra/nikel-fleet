<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'requester_id', 'vehicle_id', 'driver_id',
        'approver_l1_id', 'approver_l2_id',
        'departure_at', 'return_at', 'destination', 'purpose',
        'passengers', 'status',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'return_at'    => 'datetime',
    ];

    // ── Relasi ────────────────────────────────────────────────
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function approverL1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_l1_id');
    }

    public function approverL2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_l2_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(BookingApproval::class);
    }

    // ── Helper ────────────────────────────────────────────────
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft'      => 'Draft',
            'pending_l1' => 'Menunggu Approval L1',
            'pending_l2' => 'Menunggu Approval L2',
            'approved'   => 'Disetujui',
            'rejected'   => 'Ditolak',
            'cancelled'  => 'Dibatalkan',
            default      => $this->status,
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'approved'             => 'badge bg-success',
            'pending_l1',
            'pending_l2'           => 'badge bg-warning text-dark',
            'rejected','cancelled' => 'badge bg-danger',
            default                => 'badge bg-secondary',
        };
    }

    public function isPendingFor(User $user): bool
    {
        return ($this->approver_l1_id === $user->id && $this->status === 'pending_l1')
            || ($this->approver_l2_id === $user->id && $this->status === 'pending_l2');
    }

    // Generate kode unik BK-YYYYMM-XXXX
    public static function generateCode(): string
    {
        $prefix = 'BK-' . now()->format('Ym') . '-';
        $last   = self::where('booking_code', 'like', $prefix . '%')
                      ->orderByDesc('id')->first();
        $seq    = $last ? ((int) substr($last->booking_code, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
