<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = ['group_name', 'status'];

    public function users(){
        return $this->belongsToMany(User::class, 'user_group');
    }

    public function restaurants(){
        return $this->belongsToMany(Restaurant::class, 'restaurant_group');
    }
}
