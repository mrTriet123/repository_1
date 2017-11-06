<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['status', 'reservable_id'];

    public function dishes()
    {
        return $this->hasMany('App\OrderDish');
    }

    public function reservable()
    {
    	return $this->belongsTo('App\Reservable');
    }

    public function payment_detail()
    {
    	return $this->hasOne('App\PaymentDetail');
    }
}
