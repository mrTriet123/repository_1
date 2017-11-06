<?php

namespace App\Repositories\Restaurant;

use App\Restaurant;
use App\RestaurantType;
use App\Reservable;
use App\Merchant;
use App\MerchantTable;
use App\Menu;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\DbRepository;
use Carbon\Carbon;

class DbRestaurantRepository extends DbRepository implements RestaurantRepository
{
    protected $r_category;
    protected $default_restaurant_image;

    public function __construct(CategoryRepository $catRepo)
    {
        $this->r_category = $catRepo;
        $this->default_restaurant_image = "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png";
    }

    public function getMerchantIdOfRestaurant($restaurant_id)
    {
        $restaurant = Restaurant::where('id', $restaurant_id)->first(); // Move this to RestaurantRepo
        return $restaurant->merchant_id;
    }

    public function getAll()
    {
        $restaurantData = Restaurant::where('is_featured', 0)->paginate(10);
        $restaurants = array(
            'total' => $restaurantData,
            'data' => array(
                            'featured' => $this->getInfo($this->featured()),
                            'list' => $this->getInfo($restaurantData)
                        )
        );
        return $restaurants;
    }

    public function getInfo($restaurants)
    {
        if($restaurants->all())
        {
            $i = 0;
            $data = [];
            foreach ($restaurants as $restaurant)
            {
                $data[$i]['restaurant_id'] = $restaurant['id'];
                $data[$i]['restaurant_name'] = $restaurant['name'];
                $data[$i]['type'] = $this->getRestaurantType($restaurant['restaurant_type_id']);
                $data[$i]['start_time'] = $restaurant['operating_hour_start'];
                $data[$i]['end_time'] = $restaurant['operating_hour_end'];
                $data[$i]['price'] = $this->getAveragePrice($restaurant);
                $data[$i]['tel_no'] = $restaurant['tel_no'];
                $data[$i]['images'] = $this->getImages($restaurant);
                $i++;
            }
            return $data;
        }
        return $restaurants->all();
    }

    private function featured()
    {
        $restaurant = Restaurant::where('is_featured', 1)->get();
        return $restaurant;
    }

    private function getAveragePrice($restaurant)
    {
        if ($restaurant)
        {
            $merchant_id = $restaurant->merchant_id;
            
            $menu = Menu::where('merchant_id', $merchant_id)->first();

            if ($menu)
            {
                $i = 0;
                $total_price = 0;
                $categories = $this->r_category->getCategoriesOfMenu($menu['id']);
                $dish_array = array(); // count unique dishes only
                
                foreach ($categories as $category) {
                    foreach ($category['dishes'] as $value) {
                        if (!in_array($value['dish_id'], $dish_array)) {
                            array_push($dish_array, $value['dish_id']);
                            $total_price += $value['average_price'];
                            $i++;
                        }
                    }
                }

                return ($i == 0) ? $i : round($total_price / $i);
            }
        }
        
        return 0;
    }

    public function getByRestaurantID($id, $with_timeslot = TRUE)
    {
        $restaurant = Restaurant::where('id', $id)->first();

        if ($restaurant)
        {
            $data = [];
            $data['restaurant_id'] = $restaurant->id;
            $data['restaurant_name'] = $restaurant->name;
            $data['type'] = $this->getRestaurantType($restaurant->restaurant_type_id);
            $data['start_time'] = $restaurant->operating_hour_start;
            $data['end_time'] = $restaurant->operating_hour_end;
            $data['price'] = $this->getAveragePrice($restaurant);
            $data['tel_no'] = $restaurant->tel_no;
            $data['address'] = $this->getAddress($restaurant);
            $data['images'] = $this->getImages($restaurant);

            if ($with_timeslot){
                $data['date_timeslot'] = $this->availableTimeslots($restaurant);
            }
            
            $data['gst'] = $restaurant->gst;
            $data['service_charge'] = $restaurant->service_charge;
            return $data;
        }

        return [];
    }

    public function getByMerchantID($merchant_id, $with_timeslot = TRUE)
    {
        $restaurant = Restaurant::where('merchant_id', $merchant_id)->first();
        return $this->getByRestaurantID($restaurant->id, $with_timeslot);
    }

    private function getImages($restaurant)
    {
        if (isset($restaurant->images)) 
        {
            $i = 0;
            $data = [];
            foreach ($restaurant->images as $image) 
            {
                $data[$i]['image_id'] = $image['id'];
                $data[$i]['url'] = $image['path'];
                $i++;
            }

            if ($data) {
                return $data;
            }
        }

        return array(array("image_id" => 0, "url" => $this->default_restaurant_image));
    }

    private function getAddress($restaurant)
    {
        if (isset($restaurant->locations)) 
        {
            $data = [];
            foreach ($restaurant->locations as $location) 
            {
                $data[] = $location['location'];
            }
            return $data;
        }

        return [];
    }

    private function availableTimeslots($restaurant)
    {
        $dates = [];

        $in_advance = 2; // in days
        for ($i = 0; $i <= $in_advance; $i++)
        {
            $date = Carbon::today()->addDays($i);
            $dates[$date->format('Y-m-d')] = $this->filterTimeslots($restaurant, $date);
        }

        return array($dates);
    }

    private function filterTimeslots($restaurant, $date)
    {
        $reserved_tables = MerchantTable::where('merchant_id', $restaurant->merchant_id)->where('is_reserved', 1)->get();

        $table_array = [];
        foreach ($reserved_tables as $table) {
            $table_array[] = $table->table_no;
        }

        $timeslots = [];

        if (isset($restaurant->merchant->order_setting))
        {
            $start_time = $restaurant->merchant->order_setting->reservation_start_time;
            $end_time = $restaurant->merchant->order_setting->reservation_end_time;
            $eating_hours = $restaurant->merchant->order_setting->eating_hours;

            while(Carbon::parse($start_time) < Carbon::parse($end_time))
            {
                $start_time = Carbon::parse($start_time);
                if ($date->isToday()) // If date is today
                {
                    if (Carbon::now() < Carbon::parse($start_time)) // Filter only future time slots
                    {
                        $tmp_time = $start_time;
                        // to get range of timeslot ex. 08:00:00 - 09:59:59 2 hours eating time
                        $tmp_time = Carbon::parse($tmp_time)->addHours($eating_hours)->subSecond(); // End time of timeslot

                        $count = Reservable::whereIn('table_no', $table_array)
                                    ->where('merchant_id', $restaurant->merchant_id)
                                    ->where('start_date', $date->toDateString())
                                    ->where('start_time', '>=', $start_time->toTimeString())
                                    ->where('start_time', '<=', $tmp_time->toTimeString())
                                    ->count();

                        if ($count < count($reserved_tables)) // if orders for this timeslot still less than reserved tables, some table is available = timeslot is available
                        {
                            if (!in_array($start_time->toTimeString(), $timeslots))
                            {
                                array_push($timeslots, Carbon::parse($start_time)->toTimeString());
                            }
                        }
                    }
                }
                else // If not
                {
                        $tmp_time = $start_time;
                        // to get range of timeslot ex. 08:00:00 - 09:59:59 2 hours eating time
                        $tmp_time = Carbon::parse($tmp_time)->addHours($eating_hours)->subSecond(); // End time of timeslot

                        $count = Reservable::whereIn('table_no', $table_array)
                                    ->where('merchant_id', $restaurant->merchant_id)
                                    ->where('start_date', $date->toDateString())
                                    ->where('start_time', '>=', $start_time->toTimeString())
                                    ->where('start_time', '<=', $tmp_time->toTimeString())
                                    ->count();

                        if ($count < count($reserved_tables)) // if orders for this timeslot still less than reserved tables, some table is available = timeslot is available
                        {
                            if (!in_array($start_time->toTimeString(), $timeslots))
                            {
                                array_push($timeslots, Carbon::parse($start_time)->toTimeString());
                            }
                        }
                }

                $start_time = Carbon::parse($start_time)->addHours($eating_hours); // Add time by eating hours
            }
        }
        return $timeslots;
    }

    public function searchBy($field,$value,$open_now)
    {
        $timenow = Carbon::now()->toTimeString();

        if ($open_now) {
            $restaurantData = Restaurant::where($field, 'LIKE', '%' . $value . '%')
                            ->where('operating_hour_start', '<=', $timenow)
                            ->where('operating_hour_end', '>=', $timenow)
                            ->paginate(10);
        } else {
            $restaurantData = Restaurant::where($field, 'LIKE', '%' . $value . '%')->paginate(10);
        }

        $restaurants = array(
            'total' => $restaurantData,
            'data' => $this->getInfo($restaurantData)
        );
        return $restaurants;
    }

    public function getMenu($restaurant_id)
    {
        $restaurant = Restaurant::find($restaurant_id);

        if ($restaurant)
        {
            $merchant_id = $restaurant->merchant_id;
            
            $menu = Menu::where('merchant_id', $merchant_id)->first();

            if ($menu)
            {
                $categories = $this->r_category->getCategoriesOfMenu($menu['id']);
                return $categories;
            }
        }

        return [];
    }

    private function getRestaurantType($restaurant_type_id)
    {
        $type = RestaurantType::find($restaurant_type_id);
        return (count($type) > 0) ? $type->type : "";
    }

}