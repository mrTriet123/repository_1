<?php

namespace App\Repositories\OrderSetting;


interface OrderSettingRepository
{
    public function getByMerchantID($id);
}