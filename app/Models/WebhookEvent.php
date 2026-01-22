<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $guarded = [];

    protected $table = 'webhook_events';

    protected $casts = [
        'payload' => 'json',
    ];

    public $timestamps = false;

    protected $dates = ['received_at', 'processed_at'];
}
