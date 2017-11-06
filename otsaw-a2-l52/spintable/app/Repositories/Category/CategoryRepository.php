<?php

namespace App\Repositories\Category;


interface CategoryRepository
{
    public function getCategoriesOfMenu($id);
}