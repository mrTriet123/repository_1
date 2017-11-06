<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $table = 'menu';

    protected $fillable = [
        'merchant_id'
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }

    public function categories()
    {
    	return $this->hasMany('App\Category');
    }
}
