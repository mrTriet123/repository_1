<?php

namespace App\Repositories\Restaurant;


interface RestaurantRepository
{
	public function getMerchantIdOfRestaurant($restaurant_id);
    public function getAll();
    public function getByRestaurantID($id, $with_timeslot);
    public function getByMerchantID($id, $with_timeslot);
    public function searchBy($field, $keyword, $open_now);
    public function getMenu($id);
}