<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantLocation extends Model
{
    protected $fillable = [
        'restaurant_id', 'location'
    ];

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
