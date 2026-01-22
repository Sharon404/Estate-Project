<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = [];

    protected $table = 'audit_logs';

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];
}
