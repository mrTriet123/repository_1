<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishAddon extends Model
{
	protected $fillable = [
        'dish_id', 'addon_id'
    ];

    public function dish()
    {
        return $this->belongsTo('App\Dish');
    }

    public function addon()
    {
    	return $this->belongsTo('App\Addon');
    }
}
