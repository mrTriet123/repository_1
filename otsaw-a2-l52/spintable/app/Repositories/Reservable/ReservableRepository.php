<?php

namespace App\Repositories\Reservable;


interface ReservableRepository
{
	public function validateData($data, $order_type);
	public function validateTable($data);
    public function create($data, $order_type);
    public function getAvailableTable($data);
    public function orderType($reservable_id);
    public function getDetails($reservable_id);
    public function getReservableListOfCustomer($customer_id, $filter);
    public function changeTypeFromReservationToOrdered($reservable_id);

    /*******************
    *      WEB API
    ********************/

    public function getHistory($merchant_id, $type, $date);
}