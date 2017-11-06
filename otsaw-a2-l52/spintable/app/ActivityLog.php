<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'merchant_id', 'reservable_id', 'description', 'is_read'
    ];
}
