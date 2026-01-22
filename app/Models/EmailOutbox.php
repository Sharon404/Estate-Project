<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOutbox extends Model
{
    protected $guarded = [];

    protected $table = 'email_outbox';

    protected $casts = [
        'payload' => 'json',
    ];
}
