<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public const STATUS_PENDING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = 2;

    protected $fillable  = [
        'res_firstname', 'res_lastname', 'res_phone', 'res_people', 'res_restaurant_id', 'res_hall_id', 'datetime', 'status'
    ];
}
