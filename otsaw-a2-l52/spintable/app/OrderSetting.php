<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSetting extends Model
{
    protected $fillable = [
        'merchant_id', 'eating_hours', 'reservation_start_time', 'reservation_end_time'
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
