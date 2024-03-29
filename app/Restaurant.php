<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;

    public const AVAILABLE = 1;
    public const NOT_AVAILABLE = 0;

    protected $table = 'restaurants';

    protected $fillable = ['name', 'address', 'status'];

    public function halls(){
        return $this->hasMany(Hall::class, 'restaurant_id');
    }

    public function reservations(){
        return $this->hasMany(Reservation::class, 'res_restaurant_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class, 'restaurant_group');
    }
}
