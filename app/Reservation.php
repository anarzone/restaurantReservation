<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Reservation extends Model
{
    use SoftDeletes;

    public const STATUS_PENDING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_DONE = 2;

    protected $fillable  = [
        'res_firstname', 'res_lastname', 'res_phone', 'res_people', 'note', 'table_id',
        'res_restaurant_id', 'res_hall_id', 'datetime', 'status', 'customer_id',
    ];

    public function halls(){
        return $this->belongsTo(Hall::class, 'res_hall_id');
    }


    public function restaurants(){
        //bad code todo: fix it
        $user_id = Auth::user()->id;
        $restaurants = \DB::select("
            Select t1.id from restaurants as t1
            LEFT JOIN restaurant_group  as t2 on t1.id = t2.restaurant_id
            WHERE t2.group_id in (
                Select group_id FROM user_group WHERE user_id = {$user_id}
            );
        ");

        $rest_ids = [];
        foreach ($restaurants as $val){
            $rest_ids[] = $val->id;
        }

        return $this->belongsTo(Restaurant::class, 'res_restaurant_id')->whereIn('restaurants.id', $rest_ids ?? 0);
    }

    public function table(){
        return $this->belongsTo(Table::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
