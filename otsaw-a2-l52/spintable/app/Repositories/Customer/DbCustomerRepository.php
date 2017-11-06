<?php

namespace App\Repositories\Customer;

use App\Customer;
use App\Repositories\DbRepository;
use Carbon\Carbon;

class DbCustomerRepository extends DbRepository implements CustomerRepository
{
    public function __construct()
    {
    }

    public function getByID($id)
    {
        $customer = Customer::find($id);
        return $customer;
    }
}