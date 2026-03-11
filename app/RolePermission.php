<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission_key',
        'can_view',
        'can_create',
        'can_edit',
        'can_approve',
        'can_delete',
    ];
}