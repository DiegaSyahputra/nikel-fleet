<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'license_plate', 'brand', 'model', 'year', 'type',
        'ownership', 'status', 'region_id', 'color', 'fuel_type', 'notes',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'passenger' => 'Angkutan Orang',
            'cargo'     => 'Angkutan Barang',
            default     => $this->type,
        };
    }

    public function getOwnershipLabel(): string
    {
        return match($this->ownership) {
            'owned'  => 'Milik Perusahaan',
            'rented' => 'Sewa',
            default  => $this->ownership,
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'available'   => 'Tersedia',
            'in_use'      => 'Sedang Digunakan',
            'maintenance' => 'Perawatan',
            default       => $this->status,
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'available'   => 'badge bg-success',
            'in_use'      => 'badge bg-warning text-dark',
            'maintenance' => 'badge bg-danger',
            default       => 'badge bg-secondary',
        };
    }
}
