<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishSize extends Model
{
	protected $fillable = [
        'dish_id', 'size', 'price'
    ];

    public function dish()
    {
        return $this->belongsTo('App\Dish');
    }

    public function order_dishes()
    {
    	return $this->hasMany('App\DishSize');
    }
}
