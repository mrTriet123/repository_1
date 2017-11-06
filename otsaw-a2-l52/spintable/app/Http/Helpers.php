<?php 

use Carbon\Carbon;
use App\Http\Controllers\API\ApiController as ApiController;


class Helpers{

	public static function testing()
	{
		return "hello";

	}

	public static function timeformat ($dt)
    {
    	$datetime = strtotime( $dt );
    	$convertedDT = date( 'Y-m-d H:i:s', $datetime );
    	return $convertedDT;
    }


	public static function globleData()
	{
		$data = array(
			'version' => '1.0.0.0'
			);
		return $data;

	}


	public static function dataTimeToTime($data)
	{
		$date = strtotime($data);
	    $time = date('H:i:s', $date);
	    return $time;
	}

	public static function now()
	{
		$now = Carbon::now('Asia/Singapore');
        return $now;
	}


	public static function encodeObjectToJsonString($timeslots)
	{
		// $stores = Input::get('location'); //get selected data
		foreach ($timeslots as $timeslot) {
			$display_name = TimeSlot::find($timeslot)->displayname;
			$availability["$display_name"] = true;
		};
		$result = json_encode($availability);
	}

	public static function decodeJsonToObject($data)//get sql data 
	{
		$object_time = json_decode($data);//decode json to object
		$result = get_object_vars($object_time); //get the object value
		return $result;
	}

    // Log activity
    public static function logActivity($reservable_id, $log_type)
    {
        // ($merchant_id, $reservable['reservable_id'], "Booking for " .$data['group_size'] . " from " . $customer->user->firstname

        $reservable = App\Reservable::find($reservable_id);

        if ($reservable) {
            $reservable->code = Helpers::maskOrderCode($log_type, $reservable->code);
        }

        if ($log_type == 'Reservation') {
            $description = 'Booking for ' . $reservable->group_size . ' from ' . $reservable->customer->user->firstname;
        }

        if ($log_type == 'Walkin') {
            $description = 'New Walkin: ' . $reservable->code;
        }

        if ($log_type == 'Ordered') {
            $description = 'New Order: ' . $reservable->code;
        }

        $data = array();
        $data['merchant_id'] = $reservable->merchant_id;
        $data['reservable_id'] = $reservable->id;
        $data['description'] = $description;
        $data['is_read'] = 0;
        App\ActivityLog::create($data);
    }

    public static function maskOrderCode($type, $code)
    {
        $prefix = $type[0];

        $max_digits = 5;
        $number_of_zeros = $max_digits - strlen($code);

        $leading_zeros = '';

        for ($i=0; $i < $number_of_zeros; $i++) { 
            $leading_zeros .= '0';
        }

        $code = $prefix . $leading_zeros . $code;
        return $code;
    }
}