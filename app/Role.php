<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Role extends Model
{

    public function users(){
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
}
