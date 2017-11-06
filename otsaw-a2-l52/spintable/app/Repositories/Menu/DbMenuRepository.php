<?php

namespace App\Repositories\Menu;

use App\Menu;
use App\Repositories\DbRepository;

class DbMenuRepository extends DbRepository implements MenuRepository
{
    public function getByMerchantID($id)
    {
        $menu = Menu::select('id')->where('merchant_id', $id)->first();

        $menu['categories'] = $menu->categories;

        foreach ($menu->categories as $category) {  
            $category['dishes'] = $category->dishes;

            foreach ($category->dishes as $dish) {
                $dish['images'] = $dish->images;
                $dish['sizes'] = $dish->sizes;
            }
        }

        return $menu;
    }
}