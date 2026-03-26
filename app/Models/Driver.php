<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'name', 'license_number', 'license_expiry', 'phone', 'status', 'region_id',
    ];

    protected $casts = ['license_expiry' => 'date'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'on_duty'   => 'Sedang Bertugas',
            'off'       => 'Tidak Aktif',
            default     => $this->status,
        };
    }

    public function isLicenseExpiringSoon(): bool
    {
        return $this->license_expiry >= now() &&
           $this->license_expiry <= now()->addDays(30);
        // return $this->license_expiry->diffInDays(now()) <= 30;
    }
}
