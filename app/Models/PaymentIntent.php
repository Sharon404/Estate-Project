<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentIntent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function bookingTransactions(): HasMany
    {
        return $this->hasMany(BookingTransaction::class);
    }

    public function mpesaStkRequests(): HasMany
    {
        return $this->hasMany(MpesaStkRequest::class);
    }

    public function mpesaManualSubmissions(): HasMany
    {
        return $this->hasMany(MpesaManualSubmission::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }
}
