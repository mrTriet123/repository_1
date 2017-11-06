<?php

namespace App\Repositories\Order;

use App\Order;
use App\OrderDish;
use App\OrderDishAddon;
use App\PaymentDetail;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\Reservable\ReservableRepository;
use App\Repositories\Dish\DishRepository;
use App\Repositories\DbRepository;
use Carbon\Carbon;
use Validator;

class DbOrderRepository extends DbRepository implements OrderRepository
{
    protected $r_restaurant;
    protected $r_reservable;
    protected $r_dish;
    protected $default_restaurant_image;

    public function __construct(RestaurantRepository $resRepo, ReservableRepository $revRepo, DishRepository $disRepo)
    {
        $this->r_restaurant = $resRepo;
        $this->r_reservable = $revRepo;
        $this->r_dish = $disRepo;
        $this->default_restaurant_image = "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png";
    }
    
    public function validateData($data)
    {
        $rules = array(
                        'reservable_id' => 'required|integer|exists:reservables,id|unique:orders',
                        'dishes.*.size_id' => 'required|integer|exists:dish_sizes,id',
                        'dishes.*.addons.*' => 'integer|exists:addons,id',
                        'total_amount' => 'required|numeric'
                    );

        $validator = Validator::make($data, $rules);
        return $validator;
    }

    public function checkout($data)
    {
        if ($data['type'] == 'Reservation') {
            $this->r_reservable->changeTypeFromReservationToOrdered($data['reservable_id']);
        }

        $order = Order::create($data);
        
        if ($order) {
            if(is_string($data['dishes']))  $data['dishes'] = json_decode($data['dishes'], true);
            foreach ($data['dishes'] as $dish) {
                $order_dish = $this->createOrderDish($order->id, $dish);

                foreach ($dish['addons'] as $addon_id) {
                    $order_dish_addon = $this->createOrderDishAddons($order_dish->id, $addon_id);
                }
            }

            $payment_detail = [];
            $payment_detail['order_id'] = $order->id;
            $payment_detail['total_amount'] = $data['total_amount'];
            PaymentDetail::create($payment_detail);

            return $this->getOrderDetails($data['reservable_id']);
        }

        return $order;
    }

    private function createOrderDish($order_id, $dish)
    {
        $order_dish_data = [];
        $order_dish_data['order_id'] = $order_id;
        $order_dish_data['dish_size_id'] = $dish['size_id'];
        $order_dish_data['quantity'] = $dish['quantity'];
        $order_dish_data['notes'] = $dish['notes'];
        $order_dish = OrderDish::create($order_dish_data);
        return $order_dish;
    }

    private function createOrderDishAddons($order_dish_id, $addon_id)
    {
        $dish_addon_data = [];
        $dish_addon_data['order_dishes_id'] = $order_dish_id;
        $dish_addon_data['addon_id'] = $addon_id;
        $dish_addon_data['quantity'] = 1; // Later
        $dish_addon = OrderDishAddon::create($dish_addon_data);
        return $dish_addon;
    }

    public function getOrderDetails($reservable_id)
    {
        $reservable = $this->r_reservable->getDetails($reservable_id);

        if (!$reservable){
            return false;
        }

        $order = Order::where('reservable_id', $reservable_id)->first();
        $order_id = (count($order) > 0) ? $order->id : 0;

        $order_details = [];
        $order_details['order_id'] = $order_id;
        $order_details['order_code'] = $reservable->code;
        $with_timeslot = FALSE;
        $order_details['restaurant'] = $this->r_restaurant->getByMerchantID($reservable->merchant_id, $with_timeslot);
        $order_details['type'] = $reservable->type;
        $order_details['group_size'] = $reservable->group_size;
        $order_details['dishes'] = $this->getOrderDishes($order_id);
        $order_details['total_amount'] = isset($order->payment_detail->total_amount) ? $order->payment_detail->total_amount : 0;
        return $order_details;
    }

    private function getOrderDishes($order_id)
    {
        if ($order_id != 0){
            $order_dishes = OrderDish::where('order_id', $order_id)->get();

            if ($order_dishes) {

                $i = 0;
                $order = [];
                foreach ($order_dishes as $order_dish) {
                    $dish = $order_dish->dish_size->dish;

                    $order[$i]['dish_id'] = $dish->id;
                    $order[$i]['name'] = $dish->name;
                    $order[$i]['description'] = $dish->description;
                    $order[$i]['images'] = $this->r_dish->getDishImages($dish->images);
                    $order[$i]['sizes'] = $this->formatOrderDishSize($order_dish);
                    $order[$i]['addons'] = $this->formatOrderDishAddons($order_dish->addons);
                    $i++;
                }

                return $order;
            }
        }
        return [];
    }

    private function formatOrderDishSize($order_dish)
    {
        $size = [];
        $size['size_id'] = $order_dish->dish_size->id;
        $size['name'] = $order_dish->dish_size->size;
        $size['price'] = floatval($order_dish->dish_size->price);
        $size['quantity'] = $order_dish->quantity;
        $size['notes'] = $order_dish->notes;
        return $size;
    }

    private function formatOrderDishAddons($order_dish_addons)
    {
        $i = 0;
        $size = [];
        foreach ($order_dish_addons as $addon) {
            $size[$i]['addon_id'] = $addon->addon_id;
            $size[$i]['name'] = $addon->addon->name;
            $size[$i]['price'] = floatval($addon->addon->price);
            $i++;
        }
        return $size;
    }
}