<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN_ROLE = 'admin';
    public const STUFF_ROLE = 'stuff';

    public function users(){
        return $this->belongsToMany(User::class, 'role_user');
    }
}
