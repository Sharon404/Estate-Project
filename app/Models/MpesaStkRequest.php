<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MpesaStkRequest extends Model
{
    protected $guarded = [];

    protected $table = 'mpesa_stk_requests';

    protected $casts = [
        'request_payload' => 'json',
        'response_payload' => 'json',
    ];

    public function paymentIntent(): BelongsTo
    {
        return $this->belongsTo(PaymentIntent::class);
    }

    public function mpesaStkCallbacks(): HasMany
    {
        return $this->hasMany(MpesaStkCallback::class, 'stk_request_id');
    }
}
