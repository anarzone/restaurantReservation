<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public const AVAILABLE = 1;
    public const NOT_AVAILABLE = 0;
    protected $fillable = ['name', 'address', 'status'];

    public function halls(){
        return $this->hasMany(Hall::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class, 'res_restaurant_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class, 'restaurant_group');
    }
}
