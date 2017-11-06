<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    public function dish()
    {
        return $this->belongsTo('App\Dish');
    }
}
