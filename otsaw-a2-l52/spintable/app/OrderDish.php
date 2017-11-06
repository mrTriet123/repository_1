<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDish extends Model
{
    protected $fillable = ['order_id', 'dish_size_id', 'quantity', 'notes'];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function dish_size()
    {
    	return $this->belongsTo('App\DishSize');
    }

    public function addons()
    {
    	return $this->hasMany('App\OrderDishAddon', 'order_dishes_id');
    }
}
