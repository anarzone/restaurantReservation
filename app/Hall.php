<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    public const TABLE_AVAILABLE = 0;
    public const TABLE_BOOKED = 1;

    protected $fillable = ['name', 'restaurant_id'];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }

    public function halls(){
        return $this->hasMany(Table::class);
    }
}
