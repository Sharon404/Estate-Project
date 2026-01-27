<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'receipt_data' => 'json',
        'issued_at' => 'datetime',
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

    /**
     * Get a value from receipt_data metadata
     */
    public function getMetadataValue($key, $default = null)
    {
        return data_get($this->receipt_data, $key, $default);
    }
}
