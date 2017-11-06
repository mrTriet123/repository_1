<?php

namespace App\Repositories\Reservable;

use App\Reservable;
use App\Restaurant;
use App\MerchantTable;
use Validator;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\DbRepository;
use Carbon\Carbon;
use Helpers;

class DbReservableRepository extends DbRepository implements ReservableRepository
{
    protected $r_restaurant;

    public function __construct(RestaurantRepository $resRepo)
    {
        $this->r_restaurant = $resRepo;
    }

    public function create($data, $order_type)
    {
        $data['merchant_id'] = $this->r_restaurant->getMerchantIdOfRestaurant($data['restaurant_id']);
        $data['type'] = $order_type;

        if ($order_type == "Walkin") {
            $now = Carbon::now();
            $data['start_date'] = $now->toDateString();
            $data['start_time'] = $now->toTimeString();
        }

        $data['code'] = $this->generateCode($data['merchant_id'], $order_type);

        $result = Reservable::create($data)->id;
        return array("reservable_id" => $result);
    }

    private function generateCode($merchant_id, $type)
    {
        if ($type == "Walkin") {
            $last_reservable = Reservable::where('merchant_id', $merchant_id)
                                        ->where('type', $type)
                                        ->orderBy('code', 'DESC')->first();
        } else {
            $last_reservable = Reservable::where('merchant_id', $merchant_id)
                                        ->whereIn('type', ['Reservation', 'Ordered'])
                                        ->orderBy('code', 'DESC')->first();
        }

        $next_code = (count($last_reservable) > 0) ? ($last_reservable->code + 1) : 1;
        return $next_code;
    }

    public function validateData($data, $order_type)
    {
        $rules = array(
                        'restaurant_id' => 'required|integer|exists:restaurants,id',
                        'customer_id' => 'required|integer|exists:customers,id'
                    );

        if ($order_type == 'Reservation') // Walkin does not have group size input
        {
            $rules['start_date'] = 'required';
            $rules['start_time'] = 'required';
            $rules['group_size'] = 'required';
        }

        if ($order_type == 'Walkin')
        {
            $rules['table_no'] = 'required|integer';
        }

        $validator = Validator::make($data, $rules);
        return $validator;
    }

    public function validateTable($data)
    {
        $response = array('has_errors' => true, 'message' => '');

        $date_time_now = Carbon::now();

        $restaurant = Restaurant::where('id', $data['restaurant_id'])->first();

        $table = MerchantTable::where('merchant_id', $restaurant->merchant_id)->where('table_no', $data['table_no'])->count();

        if (!$table) {
            $response['message'] = "Invalid Table No.";
            return $response;
        }

        if (!isset($restaurant->merchant->order_setting))
        {
            $response['message'] = "Something went wrong.";
            return $response;
        }

        $start_time = $restaurant->merchant->order_setting->reservation_start_time;
        $end_time = $restaurant->merchant->order_setting->reservation_end_time;
        $eating_hours = $restaurant->merchant->order_setting->eating_hours;
        $current_time_flag = 0; // This is to get only the on-going time slot; Tied up with time now

        if ($date_time_now > Carbon::parse($end_time))
        {
            $response['message'] = "Restaurant is currently closed";
            return $response;
        }

        while((Carbon::parse($start_time) < Carbon::parse($end_time)) && ($current_time_flag == 0))
        {
            $start_time = Carbon::parse($start_time);
            $tmp_time = $start_time;
            $tmp_time = Carbon::parse($tmp_time)->addHours($eating_hours)->subSecond(); // End time of timeslot

            if (Carbon::now()->between($start_time, $tmp_time))
            { 
                $current_time_flag = 1;

                // check if table is currently in-use
                $count = Reservable::where('table_no', $data['table_no'])
                                ->where('merchant_id', $restaurant->merchant_id)
                                ->where('start_date', $date_time_now->toDateString())
                                ->where('start_time', '>=', $start_time->toTimeString())
                                ->where('start_time', '<=', $tmp_time->toTimeString())
                                ->count();

                if ($count) // If already taken
                {
                    $response['message'] = "Table is already taken.";
                    return $response;
                }

                // Check next time slot if there are no reservations
                $next_time_slot_start_time = $tmp_time->addSecond(); // ex. 09:59:59 + 1sec = 10:00:00
                $next_time_slot_end_time = Carbon::parse($next_time_slot_start_time)->addHours($eating_hours)->subSecond(); // End time

                $count = Reservable::where('table_no', $data['table_no'])
                            ->where('merchant_id', $restaurant->merchant_id)
                            ->where('start_date', $date_time_now->toDateString())
                            ->where('start_time', '>=', $next_time_slot_start_time->toTimeString())
                            ->where('start_time', '<=', $next_time_slot_end_time->toTimeString())
                            ->count();

                if ($count) // Next time slot also taken
                {
                    $response['message'] = "This table has a reservation at " . $next_time_slot_start_time->format('g:ia');
                    return $response;
                }
            }

            $start_time->addHours($eating_hours); // Add time by eating hours
        }

        $response['has_errors'] = false;
        return $response;
    }

    public function getAvailableTable($data)
    {
        $restaurant = Restaurant::where('id', $data['restaurant_id'])->first();

        $reserved_tables = MerchantTable::where('merchant_id', $restaurant->merchant_id)->where('is_reserved', 1)->get();

        $i = 0;
        $table_array = [];
        $tableno_capacity = [];
        foreach ($reserved_tables as $table) {
            $table_array[] = $table->table_no;
            $tableno_capacity[$table->table_no] = $table->capacity;
        }

        if (isset($restaurant->merchant->order_setting))
        {
            $eating_hours = $restaurant->merchant->order_setting->eating_hours;
            $start_time = Carbon::parse($data['start_time']);

            $tmp_time = $start_time;
            // to get range of timeslot ex. 08:00:00 - 09:59:59 2 hours eating time
            $tmp_time = Carbon::parse($tmp_time)->addHours($eating_hours)->subSecond(); // End time of

            $taken_tables = Reservable::whereIn('table_no', $table_array)
                        ->where('merchant_id', $restaurant->merchant_id)
                        ->where('start_date', $data['start_date'])
                        ->where('start_time', '>=', $start_time->toTimeString())
                        ->where('start_time', '<=', $tmp_time->toTimeString())
                        ->lists('table_no')->all();

            if (count($taken_tables) < count($reserved_tables)) // if orders for this timeslot still less than reserved 
            {
                foreach ($tableno_capacity as $key => $value) {
                    if (in_array($key, $taken_tables)) {
                        unset($tableno_capacity[$key]); // Remove all taken tables
                    }
                }

                 // Compare table capacity to group size
                $table_no = $this->getTableByClosestCapacity($data['group_size'], $tableno_capacity);
                return $table_no;
            }
        }

        return false;
    }

    private function getTableByClosestCapacity($search, $tableno_capacity) {
       $closest = null;

       $arr = array_values($tableno_capacity);

       foreach ($arr as $item) {
          if ($closest === null || abs($search - $closest) > abs($item - $search)) {
             $closest = $item;
          }
       }

       $table_no = array_search($closest, $tableno_capacity);
       return $table_no;
    }

    public function getDetails($reservable_id)
    {
        $reservable = Reservable::find($reservable_id);
        if ($reservable) {
            $reservable->code = Helpers::maskOrderCode($reservable->type, $reservable->code);
        }
        return $reservable;
    }

    public function orderType($reservable_id)
    {
        return $this->getDetails($reservable_id)->type;
    }

    public function getReservableListOfCustomer($customer_id, $filter)
    {
        $datenow = Carbon::now();
        if ($filter == 'past'){
            $reservables = Reservable::where('customer_id', $customer_id)
                    ->where('start_date', '<', $datenow->toDateString())
                    ->orWhere(function ($query) use ($datenow){
                        $query->where('start_date', '=', $datenow->toDateString())
                                ->where('start_time', '<', $datenow->toTimeString());
                    })
                    ->orderBy('start_date', 'DESC')
                    ->orderBy('start_time', 'DESC')
                    ->paginate(50);
        } else{
            $reservables = Reservable::where('customer_id', $customer_id)
                    ->where('start_date', '>', $datenow->toDateString())
                    ->orWhere(function ($query) use ($datenow){
                        $query->where('start_date', '=', $datenow->toDateString())
                                ->where('start_time', '>', $datenow->toTimeString());
                    })
                    ->orderBy('start_date', 'ASC')
                    ->orderBy('start_time', 'ASC')
                    ->paginate(50);
        }

        $i = 0;
        if ($reservables->all()){
            foreach ($reservables->all() as $reservable) {
                $tmpRes[$i++] = $this->formatReservableInfo($reservable);
            }
        } else {
            $tmpRes = $reservables->all();
        }
        
        $data = array('total' => $reservables, 'data' => $tmpRes);
        return $data;
    }

    private function formatReservableInfo($reservable)
    {
        $restaurant = $this->r_restaurant->getByMerchantID($reservable->merchant_id, FALSE);

        $data = [];
        $data['reservable_id'] = $reservable->id;
        $data['restaurant_name'] = $restaurant['restaurant_name'];
        $data['order_datetime'] = $reservable->start_date .' '. $reservable->start_time;
        $data['type'] = $reservable->type;
        $data['images'] = $restaurant['images'];
        return $data;
    }

    public function changeTypeFromReservationToOrdered($reservable_id)
    {
        $reservable = Reservable::find($reservable_id);
        $reservable->reservation_code = Helpers::maskOrderCode($reservable->type, $reservable->code);
        $reservable->type = 'Ordered';
        $reservable->ordered_at = Carbon::now()->toDateTimeString();
        $reservable->save();
    }

    /*******************
    *      WEB API
    ********************/

    public function getReservableListOfMerchant($merchant_id, $type, $date)
    {
        $timenow = Carbon::now();
        $result = [];
        $result['less_than_one_hour'] = [];
        $result['more_than_one_hour'] = [];
        $i = 0;
        $n = 0;

        if ($type == 'Ordered'){ // If reservable has completed the payment
            if ($date == $timenow->toDateString()) {
                $reservables = Reservable::has('order') //  has orders
                                ->where('merchant_id', $merchant_id)
                                ->where('type', $type)
                                ->where('start_date', '=', $date)
                                ->where('start_time', '>', $timenow->toTimeString())
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            } else {
                $reservables = Reservable::has('order') //  has orders
                                ->where('merchant_id', $merchant_id)
                                ->where('type', $type)
                                ->where('start_date', '=', $date)
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            }
        }

        if ($type == 'Reservation'){ // If reservable has not completed the payment
            if ($date == $timenow->toDateString()) {
                $reservables = Reservable::doesntHave('order') // doesnt have orders
                                ->where('merchant_id', $merchant_id)
                                ->where('type', $type)
                                ->where('start_date', '=', $date)
                                ->where('start_time', '>', $timenow->toTimeString())
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            } else {
                $reservables = Reservable::doesntHave('order') // doesnt have orders
                                ->where('merchant_id', $merchant_id)
                                ->where('type', $type)
                                ->where('start_date', '=', $date)
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            }
        }

        if ($type == 'Walkin'){ // If reservable has completed the payment
            $reservables = Reservable::has('order') //  has orders
                            ->where('merchant_id', $merchant_id)
                            ->where('type', $type)
                            ->where('start_date', '=', $date)
                            // ->where('start_time', '>', $timenow->toTimeString())
                            ->orderBy('start_time', 'ASC')
                            ->paginate(10);
        }

        foreach ($reservables->all() as $reservable) {
            if ($type != 'Walkin') {
                if ($date == $timenow->toDateString()) { // if today
                    if (Carbon::parse($reservable->start_time) >= $timenow) { // select only current/future time *
                        if (Carbon::parse($reservable->start_time)->diffInHours($timenow) < 1) {
                            $key = 'less_than_one_hour';
                            $result[$key][$i++] = $this->formatReservableInfoMerchant($reservable);
                        } else {
                            $key = 'more_than_one_hour';
                            $result[$key][$n++] = $this->formatReservableInfoMerchant($reservable);
                        }
                    }
                } else { // if not today, all are in more_than_one_hour
                    $key = 'more_than_one_hour';
                    $result[$key][$n++] = $this->formatReservableInfoMerchant($reservable);
                }
            } else {
                $result[] = $this->formatReservableInfoMerchant($reservable);
            }
        }

        if ($type == 'Walkin') {
            unset($result['less_than_one_hour']);
            unset($result['more_than_one_hour']);
        }

        $data = array('total' => $reservables, 'data' => $result);
        return $data;
    }

    public function formatReservableInfoMerchant($reservable)
    {
        $result = [];
        $result['reservable_id'] = $reservable->id;
        $result['order_code'] = Helpers::maskOrderCode($reservable->type, $reservable->code);
        $result['datetime'] = Carbon::parse($reservable->start_date .' ' .$reservable->start_time)->toIso8601String();
        $result['table_no'] = $reservable->table_no;
        $result['customer_name'] = $reservable->customer->user->firstname;
        $result['mobile_no'] = $reservable->customer->mobile_no;
        $result['email'] = $reservable->customer->user->email;
        $result['group_size'] = $reservable->group_size;
        $result['total_amount'] = 0;

        if ($reservable->type == 'Ordered' || $reservable->type == 'Walkin') {
            $result['total_amount'] = $reservable->order->payment_detail->total_amount;
        }

        return $result;
    }

    public function getHistory($merchant_id, $type, $date)
    {
        $date = empty($date) ? Carbon::today() : Carbon::parse($date);
        $timenow = Carbon::now();

        if ($type == 'transactions')
        {
            if ($date->isToday()) {
                $reservables = Reservable::has('order') //  has orders
                                ->where('start_date', $date->toDateString())
                                ->where('start_time', '<', $timenow->toTimeString())
                                ->where('merchant_id', $merchant_id)
                                ->whereIn('type', ['Ordered', 'Walkin'])
                                ->orderBy('start_date', 'DESC')
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            } else {
                $reservables = Reservable::has('order') //  has orders
                                ->where('start_date', $date->toDateString())
                                ->where('merchant_id', $merchant_id)
                                ->whereIn('type', ['Ordered', 'Walkin'])
                                ->orderBy('start_date', 'DESC')
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            }
            
        }

        if ($type == 'bookings')
        {
            if ($date->isToday()) {
                $reservables = Reservable::where('start_date', $date->toDateString())
                                ->where('start_time', '<', $timenow->toTimeString())
                                ->where('merchant_id', $merchant_id)
                                ->where('type', 'Reservation')
                                ->orderBy('start_date', 'DESC')
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            } else {
                $reservables = Reservable::where('start_date', $date->toDateString())
                                ->where('merchant_id', $merchant_id)
                                ->where('type', 'Reservation')
                                ->orderBy('start_date', 'DESC')
                                ->orderBy('start_time', 'ASC')
                                ->paginate(50);
            }
        }

        $i = 0;
        if ($reservables->all()){
            foreach ($reservables->all() as $reservable) {
                $tmpRes[$i++] = $this->formatReservableInfoMerchant($reservable);
            }
        } else {
            $tmpRes = $reservables->all();
        }
        
        $data = array('total' => $reservables, 'data' => $tmpRes);
        return $data;
    }
}