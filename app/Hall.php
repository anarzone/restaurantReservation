<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hall extends Model
{
    use SoftDeletes;

    public const TABLE_AVAILABLE = 0;
    public const TABLE_BOOKED = 1;

    protected $fillable = ['name', 'restaurant_id'];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }

    public function tables(){
        return $this->hasMany(Table::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class, 'res_hall_id');
    }

    public function plan(){
        return $this->hasOne(Plan::class, 'hall_id');
    }
}
