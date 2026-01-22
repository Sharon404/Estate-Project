<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaC2bTransaction extends Model
{
    protected $guarded = [];

    protected $table = 'mpesa_c2b_transactions';

    protected $casts = [
        'trans_amount' => 'decimal:2',
        'raw_payload' => 'json',
    ];

    public $timestamps = false;

    protected $dates = ['received_at'];
}
