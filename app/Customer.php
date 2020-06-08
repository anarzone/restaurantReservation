<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    const STATUS_NEW     = 0;
    const STATUS_REGULAR = 1;
    const STATUS_BLOCKED = 2;

    protected $fillable = ['firstname', 'lastname', 'phone', 'status', 'birthdate', 'note'];

    public function reservations(){
        return $this->hasMany(Reservation::class, 'customer_id');
    }
}
