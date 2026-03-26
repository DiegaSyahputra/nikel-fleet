<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'region_id', 'phone', 'position',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'requester_id');
    }

    public function approvalsL1(): HasMany
    {
        return $this->hasMany(Booking::class, 'approver_l1_id');
    }

    public function approvalsL2(): HasMany
    {
        return $this->hasMany(Booking::class, 'approver_l2_id');
    }

    public function approvalActions(): HasMany
    {
        return $this->hasMany(BookingApproval::class, 'approver_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isApprover(): bool
    {
        return $this->role === 'approver';
    }

    // Semua pemesanan yang menunggu approval dari user ini
    public function pendingApprovals()
    {
        return Booking::where(function ($q) {
            $q->where('approver_l1_id', $this->id)->where('status', 'pending_l1');
        })->orWhere(function ($q) {
            $q->where('approver_l2_id', $this->id)->where('status', 'pending_l2');
        });
    }
}
