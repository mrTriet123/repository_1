<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryImage extends Model
{
    protected $fillable = [
        'category_id', 'name', 'path', 'name_origin'
    ];

    public function category()
    {
        return $this->belongsTo('App\Dish');
    }
}
