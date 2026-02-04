<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    protected $fillable = [
        'payout_ref',
        'property_id',
        'booking_id',
        'payee_type',
        'payee_name',
        'payee_phone',
        'payee_account',
        'gross_amount',
        'commission_amount',
        'deductions',
        'net_amount',
        'status',
        'payment_method',
        'mpesa_ref',
        'notes',
        'dispute_reason',
        'approved_by',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payout) {
            $payout->payout_ref = 'PAY-' . strtoupper(uniqid());
        });
    }
}
