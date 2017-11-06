<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    public $spicyLevelData = [
        0   =>  null,
        1   =>"Normal",
        2   =>"Midd",
        3   =>"Hot"
    ];

    public $dishFolder = 'dishs/';

    protected $fillable = [
        'name'
    ];

    public function images()
    {
    	return $this->hasMany('App\DishImage');
    }

    public function image()
    {
        return $this->hasOne('App\DishImage');
    }

    public function sizes()
    {
    	return $this->hasMany('App\DishSize');
    }

    public function dish_addons()
    {
        return $this->hasMany('App\DishAddon');
    }

    public function addons()
    {
        return $this->belongsToMany('App\Addon', 'dish_addons');
    }

    public function dish_categories()
    {
        return $this->hasMany('App\DishCategory');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'dish_categories');
    }
}
