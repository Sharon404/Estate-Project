<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'nightly_rate' => 'decimal:2'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    // Alias for images (for backward compatibility with views that use 'photos')
    public function photos(): HasMany
    {
        return $this->images();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
