<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanTable extends Model
{
    protected $fillable = ['plan_id', 'table_id', 'hall_id', 'coords'];

    public function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function table(){
        return $this->belongsTo(Table::class, 'table_id');
    }
}
