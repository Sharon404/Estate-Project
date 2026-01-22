<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTransaction extends Model
{
    protected $guarded = [];

    protected $table = 'booking_transactions';

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'json',
        'posted_at' => 'timestamp',
    ];

    public $timestamps = false;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(PaymentIntent::class);
    }
}
