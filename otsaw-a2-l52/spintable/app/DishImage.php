<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishImage extends Model
{
    protected $fillable = [
        'dish_id', 'name', 'path', 'name_origin'
    ];

    public function dish()
    {
        return $this->belongsTo('App\Dish');
    }
}
