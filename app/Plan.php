<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['hall_id', 'img_name'];

    public function hall(){
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function tables(){
        return $this->belongsToMany(Table::class, 'plan_tables');
    }
}
