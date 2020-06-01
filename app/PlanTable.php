<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTable extends Model
{
    protected $fillable = ['plan_id', 'table_id', 'hall_id', 'coords'];

    public function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
