<?php

namespace App\Repositories\Merchant;

use App\Merchant;
use App\User;
use App\Repositories\DbRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\OrderSetting\OrderSettingRepository;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;

class DbMerchantRepository extends DbRepository implements MerchantRepository
{
    protected $r_restaurant;
    protected $r_order_setting;
    protected $r_order;

    public function __construct(RestaurantRepository $resRepo, OrderSettingRepository $ordRepo, OrderRepository $orRepo)
    {
        $this->r_restaurant = $resRepo;
        $this->r_order_setting = $ordRepo;
        $this->r_order = $orRepo;
    }

    public function getAll()
    {
        $merchantData = Merchant::paginate(10);
        $merchants = array(
            'total' => $merchantData,
            'data' => array(
                            'featured' => $this->getInfo($this->featured()),
                            'list' => $this->getInfo($merchantData)
                        )
        );
        return $merchants;
    }

    private function getInfo($merchants)
    {
        if($merchants->all())
        {
            $i = 0;
            $data = [];
            foreach ($merchants as $merchant)
            {
                $restaurant = $this->r_restaurant->getByMerchantID($merchant['id']);
                $order_setting = $this->r_order_setting->getByMerchantID($merchant['id']);

                $data[$i]['merchant_id'] = $merchant['id'];
                $data[$i]['restaurant_name'] = $merchant->restaurant->name;
                $data[$i]['firstname'] = $merchant->user->firstname;
                $data[$i]['lastname'] = $merchant->user->lastname;
                $data[$i]['email'] = $merchant->user->email;
                $data[$i]['type'] = isset($restaurant->type) ? $restaurant->type : '';
                $data[$i]['price'] = "S$10-S$15"; // Dish sizes prize
                $data[$i]['open_time'] = isset($order_setting->reservation_start_time) ? $order_setting->reservation_start_time : '';
                $data[$i]['closed_time'] = isset($order_setting->reservation_end_time) ? $order_setting->reservation_end_time : '';
                $data[$i]['mobile_no'] = isset($restaurant->mobile_no) ? $restaurant->mobile_no : '';
                $data[$i]['address'] = $merchant->restaurant->locations;
                $data[$i]['images'] = $merchant->restaurant->images;
                $data[$i]['available_dates'] = $this->availableDates($merchant);
                $data[$i]['time_slots'] = $this->calculateTimeslots($merchant['id'], Carbon::today());
                $i++;
            }
            return $data;
        }
        return $merchants->all();
    }

    public function getByID($id)
    {
        $merchant = Merchant::where('id', $id)->get();
        return $this->getInfo($merchant);
    }

    public function saveStripeAccountID($merchant_id, $connected_stripe_account_id)
    {
        $merchant = Merchant::find($merchant_id);
        $data = array('connected_stripe_account_id' => $connected_stripe_account_id);
        $merchant->save($data);
    }
}