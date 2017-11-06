<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantImage extends Model
{
    protected $fillable = [
        'restaurant_id', 'name', 'path', 'name_origin'
    ];

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
