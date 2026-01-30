<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaManualSubmission extends Model
{
    protected $guarded = [];

    protected $table = 'mpesa_manual_submissions';

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_check_response' => 'array',
        'verified_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Get the booking this submission belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the admin user who verified this submission
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
