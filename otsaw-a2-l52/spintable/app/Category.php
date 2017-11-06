<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'menu_id', 'name', 'drink_category'
    ];

    public function menu()
    {
        //return $this->belongsTo('App\Merchant');
        return $this->belongsTo('App\Menu');
    }
    public function images()
    {
    	return $this->hasMany('App\CategoryImage');
    }

    public function dishes()
    {
        return $this->belongsToMany('App\Dish', 'dish_categories');
    }
}
