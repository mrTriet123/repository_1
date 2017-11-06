<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name', 'type'
    ];

    public function dish_addons()
    {
        return $this->hasMany('App\DishAddon');
    }

    public function order_dish_addons()
    {
    	return $this->hasMany('App\OrderDishAddon');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }

    public function dishes()
    {
        return $this->belongsToMany('App\Dish', 'dish_addons');
    }
}
