<?php

namespace App\Repositories\OrderSetting;

use App\OrderSetting;
use App\Repositories\DbRepository;

class DbOrderSettingRepository extends DbRepository implements OrderSettingRepository
{
    public function getAll()
    {
        $merchantData = Restaurant::paginate(10);
        $merchants = array(
            'total' => $merchantData,
            'data' => $this->getUserInfo($merchantData)
        );
        return $merchants;
    }

    public function getByMerchantID($id)
    {
        $order_setting = OrderSetting::where('merchant_id', $id)->first();
        return  $order_setting;
    }
}