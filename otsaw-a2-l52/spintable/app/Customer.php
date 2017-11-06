<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'mobile_no', 'stripe_customer_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reservables()
    {
    	return $this->hasMany('App\Reservable');
    }
}
