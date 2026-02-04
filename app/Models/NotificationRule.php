<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRule extends Model
{
    protected $fillable = [
        'name',
        'event',
        'channels',
        'recipients',
        'template',
        'conditions',
        'active',
    ];

    protected $casts = [
        'channels' => 'array',
        'recipients' => 'array',
        'conditions' => 'array',
        'active' => 'boolean',
    ];
}
