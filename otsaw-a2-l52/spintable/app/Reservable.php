<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservable extends Model
{
    protected $fillable = [
        'id', 'type', 'start_date', 'start_time', 'table_no', 'merchant_id', 'customer_id', 'group_size', 'notes', 'code', 'reservation_code', 'ordered_at'
    ];

    public function order()
    {
    	return $this->hasOne('App\Order');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
