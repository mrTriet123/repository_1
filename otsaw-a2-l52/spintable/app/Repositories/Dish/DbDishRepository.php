<?php

namespace App\Repositories\Dish;

use App\Dish;
use App\DishCategory;
use App\DishSize;
use App\DishAddon;
use App\Repositories\DbRepository;

class DbDishRepository extends DbRepository implements DishRepository
{
    protected $default_dish_image;

    public function __construct()
    {
        $this->default_dish_image = "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png";
    }

    public function getDishInfo($dish_id)
    {
        $dish = Dish::find($dish_id);
        return $this->formatInfo($dish);
    }

    private function formatInfo($dish)
    {
        $data = [];

        if ($dish)
        {
            $data['dish_id'] = $dish->id;
            $data['name'] = $dish->name;
            $data['description'] = $dish->description;
            $data['price'] = $this->getBasePriceOfDish($dish->id);
            $data['sizes'] = $this->getDishSizes($dish->sizes);
            $data['images'] = $this->getDishImages($dish->images);
            $data['addons'] = $this->getDishAddons($dish->id);
        }

        return $data;
    }

    public function getDishesByCategoryID($category_id)
    {
        $dish_categories = DishCategory::where('category_id', $category_id)->get();

        if ($dish_categories)
        {
            $i = 0;
            $data = [];

            foreach ($dish_categories as $dish_category) {
                $data[$i]['dish_id'] = $dish_category->dish->id;
                $data[$i]['name'] = $dish_category->dish->name;
                $data[$i]['description'] = $dish_category->dish->description;
                $data[$i]['price'] = $this->getBasePriceOfDish($dish_category->dish->id);
                $data[$i]['sizes'] = $this->getDishSizes($dish_category->dish->sizes);
                $data[$i]['images'] = $this->getDishImages($dish_category->dish->images);
                $data[$i]['addons'] = $this->getDishAddons($dish_category->dish->id);
                $data[$i]['average_price'] = $dish_category->dish->sizes->avg('price');
                $i++;
            }

            return $data;
        }
            
        return $dish_categories;
    }

    private function getBasePriceOfDish($dish_id)
    {
        $dish_size = DishSize::where('dish_id', $dish_id)->orderBy('price')->first();
        return floatval(($dish_size) ? $dish_size->price : 0);
    }

    private function getDishSizes($dish_sizes)
    {
        $i = 0;
        $data = [];

        if ($dish_sizes)
        {
            foreach ($dish_sizes as $size) {
                $data[$i]['size_id'] = $size->id;
                $data[$i]['name'] = $size->size;
                $data[$i]['price'] = floatval($size->price);
                $i++;
            }

            return $data;
        }
        
        return $data;
    }

    public function getDishImages($dish_images)
    {
        $i = 0;
        $data = [];

        if ($dish_images && count($dish_images) > 0)
        {
            foreach ($dish_images as $image) 
            {
                $data[$i]['image_id'] = $image['id'];
                $data[$i]['url'] = $image['path'];
                $i++;
            }

            return $data;
        }

        return array(array('image_id' => 0, 'url' => $this->default_dish_image));
    }

    private function getDishAddons($dish_id)
    {
        $dish_addons = DishAddon::where('dish_id', $dish_id)->get();

        if ($dish_addons)
        {
            $i = 0;
            $data = [];

            foreach ($dish_addons as $dish_addon) {
                $data[$i]['addon_id'] = $dish_addon->addon_id;
                $data[$i]['name'] = $dish_addon->addon->name;
                $data[$i]['price'] = floatval($dish_addon->addon->price);
                $i++;
            }

            return $data;
        }
            
        return $dish_addons;
    }

}