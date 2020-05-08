<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    public const TABLE_AVAILABLE = 0;
    public const TABLE_BOOKED = 1;

    protected $fillable = ['table_number', 'hall_id', 'restaurant_id', 'people_amount'];

    public function hall(){
        return $this->belongsTo(Hall::class);
    }

    public function reservation(){
        return $this->hasOne(Reservation::class);
    }
}
