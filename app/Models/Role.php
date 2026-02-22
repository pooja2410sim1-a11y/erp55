<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

   public function users()
{
    return $this->belongsToMany(User::class, 'role_user');
}

public function permissions()
{
    return $this->belongsToMany(Permission::class, 'permission_role');
}
    public function roles()
    {
    return $this->belongsToMany(\App\Models\Role::class, 'role_user');
    }
}