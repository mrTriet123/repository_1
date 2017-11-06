<?php

namespace App\Repositories\Category;

use App\Category;
use App\Repositories\Dish\DishRepository;
use App\Repositories\DbRepository;

class DbCategoryRepository extends DbRepository implements CategoryRepository
{
    protected $r_dish;

    public function __construct(DishRepository $dishRepo)
    {
        $this->r_dish = $dishRepo;
    }

    public function getCategoriesOfMenu($menu_id)
    {
        $categories = Category::where('menu_id', $menu_id)->get();
        
        if ($categories)
        {
            $i = 0;
            $data = [];

            foreach ($categories as $category) {
                $data[$i]['category_id'] = $category->id;
                $data[$i]['name'] = $category->name;
                $data[$i]['dishes'] = $this->r_dish->getDishesByCategoryID($category->id);
                $i++;
            }

            return $data;
        }

        return $categories;
    }
}