<?php

namespace App\Repositories\Order;


interface OrderRepository
{
	public function validateData($data);
    public function checkout($data);
}