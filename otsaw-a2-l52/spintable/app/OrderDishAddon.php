<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDishAddon extends Model
{
	protected $table = 'order_dishes_addons';

    protected $fillable = ['order_dishes_id', 'addon_id', 'quantity'];

    public function order_dish()
    {
        return $this->belongsTo('App\OrderDish');
    }

    public function addon()
    {
        return $this->belongsTo('App\Addon');
    }
}
