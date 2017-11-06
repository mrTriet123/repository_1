<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HappyHour extends Model
{
    protected $fillable = [
        'merchant_id', 'name', 'start_time', '  end_time','repeat','discount_type','total_discount'
    ];
    protected $table = 'happy_hour';
    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
