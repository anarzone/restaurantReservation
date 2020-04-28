<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public function halls(){
        return $this->hasMany(Hall::class);
    }
}
