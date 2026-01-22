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
    ];

    public $timestamps = false;

    protected $dates = ['submitted_at', 'reviewed_at'];

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(PaymentIntent::class);
    }
}
