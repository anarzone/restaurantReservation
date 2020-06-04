<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['hall_id', 'img_name', 'img_size'];

    public function hall(){
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function tables(){
        return $this->belongsToMany(Table::class, 'plan_tables');
    }
}
