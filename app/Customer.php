<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const STATUS_NEW     = 0;
    const STATUS_REGULAR = 1;
    const STATUS_BLOCKED = 2;

    protected $fillable = ['firstname', 'lastname', 'phone', 'status'];

    public function reservations(){
        return $this->hasMany(Reservation::class, 'customer_id');
    }
}
