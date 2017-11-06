<?php

namespace App\Repositories\Customer;


interface CustomerRepository
{
    public function getByID($customer_id);
}