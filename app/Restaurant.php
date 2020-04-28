<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public const AVAILABLE = 1;
    public const NOT_AVAILABLE = 0;
    protected $fillable = ['name', 'address', 'status'];

    public function halls(){
        return $this->hasMany(Hall::class);
    }
}
