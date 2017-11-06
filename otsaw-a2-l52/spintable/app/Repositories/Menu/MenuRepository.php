<?php

namespace App\Repositories\Menu;


interface MenuRepository
{
    public function getByMerchantID($id);
}