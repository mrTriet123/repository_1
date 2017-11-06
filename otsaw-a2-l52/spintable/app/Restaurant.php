<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'merchant_id', 'tel_no', 'restaurant_type_id', 'operating_hour_start', 'operating_hour_end', 'gst', 'service_charge', 'is_featured'
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }

    public function images()
    {
        return $this->hasMany('App\RestaurantImage');
    }

    public function locations()
    {
        return $this->hasMany('App\RestaurantLocation');
    }
}
