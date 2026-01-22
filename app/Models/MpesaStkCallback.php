<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaStkCallback extends Model
{
    protected $guarded = [];

    protected $table = 'mpesa_stk_callbacks';

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_payload' => 'json',
    ];

    public $timestamps = false;

    public function mpesaStkRequest(): BelongsTo
    {
        return $this->belongsTo(MpesaStkRequest::class, 'stk_request_id');
    }
}
