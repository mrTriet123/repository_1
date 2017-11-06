<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantTable extends Model
{
    protected $fillable = [
        'merchant_id', 'table_no', 'capacity', 'is_reserved'
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }
}
