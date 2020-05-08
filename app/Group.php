<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['group_name', 'status'];

    public function users(){
        return $this->belongsToMany(User::class, 'user_group');
    }

    public function restaurants(){
        return $this->belongsToMany(Restaurant::class, 'restaurant_group');
    }
}
