<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $fillable = [
        'id', 'order_id', 'total_amount', 'received_card', 'tax'
    ];

    public function order()
    {
    	return $this->belongsTo('App\Order');
    }
}
