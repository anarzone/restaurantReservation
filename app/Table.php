<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    public const TABLE_AVAILABLE = 0;
    public const TABLE_BOOKED = 1;

    protected $fillable = ['table_number', 'hall_id', 'restaurant_id', 'people_amount'];

    public function hall(){
        return $this->belongsTo(Hall::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function plans(){
        return $this->belongsToMany(Plan::class, 'plan_tables');
    }
}
