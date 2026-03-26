<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'type', 'address'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'head_office' => 'Kantor Pusat',
            'branch'      => 'Kantor Cabang',
            'mine'        => 'Tambang',
            default       => $this->type,
        };
    }
}
