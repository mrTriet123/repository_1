<?php
namespace App\Repositories\Dish;

interface DishRepository
{
    public function getDishesByCategoryID($category_id);
    public function getDishInfo($dish_id);
    public function getDishImages($dish_images);
}