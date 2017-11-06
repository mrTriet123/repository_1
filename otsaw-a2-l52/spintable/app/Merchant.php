<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'user_id', 'mobile_no', 'connected_stripe_account_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function menu()
    {
        return $this->hasOne('App\Menu');
    }

    public function restaurant()
    {
    	return $this->hasOne('App\Restaurant');
    }

    public function order_setting()
    {
    	return $this->hasOne('App\OrderSetting');
    }

    public function tables()
    {
        return $this->hasMany('App\MerchantTable');
    }

    public function reservables()
    {
        return $this->hasMany('App\Reservable');
    }

    public function happyhour()
    {
        return $this->hasOne('App\HappyHour');
    }

    public function addons()
    {
        return $this->hasMany('App\Addon');
    }
}
