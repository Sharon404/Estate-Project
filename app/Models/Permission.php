<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'category', 'description'];

    public function roles()
    {
        return $this->belongsToMany(User::class, 'role_permissions', 'permission_id', 'role');
    }
}
