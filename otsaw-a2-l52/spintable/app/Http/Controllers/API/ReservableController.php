<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Reservable\ReservableRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Repositories\ActivityLog\ActivityLogRepository;
use App\Http\Controllers\UploadFileController;
use Carbon\Carbon;
use Helpers;
use App\Customer;
use Mail;

class ReservableController extends ApiController{

    protected $statusCode = 200;
    protected $r_reservable;
    protected $r_restaurant;
    protected $r_activitylog;

    function __construct(ReservableRepository $resRepo, RestaurantRepository $restRepo, ActivityLogRepository $actRepo)
    {
        $this->r_reservable = $resRepo;
        $this->r_restaurant = $restRepo;
        $this->r_activitylog = $actRepo;
        // $this->folder = "item_images";
    }

    public function reserve()
    {
        $order_type = 'Reservation';

        $data = Input::all();

        $validator = $this->r_reservable->validateData($data, $order_type);
        if ($validator->fails()){
            return $this->respondPostValidationError($validator->messages());
        }

        $table_no = $this->r_reservable->getAvailableTable($data);

        if (!$table_no){
            return $this->respondPostError('No more available table for this timeslot.');
        }

        $data['table_no'] = $table_no; // Assign Table
        
        $reservable = $this->r_reservable->create($data, $order_type);

        $merchant_id = $this->r_restaurant->getMerchantIdOfRestaurant($data['restaurant_id']);

        if ($data['start_date'] == Carbon::now()->toDateString()){
            $bookings = $this->r_reservable->getReservableListOfMerchant($merchant_id, 'Reservation', Carbon::now()->toDateString());

            // Trigger web booking channel
            \Event::fire(new \App\Events\UpdateBookingList($bookings['data']));
        }

        // Log activity
        Helpers::logActivity($reservable['reservable_id'], 'Reservation');

        $notifications = $this->r_activitylog->recentNotifications($merchant_id);
        \Event::fire(new \App\Events\RefreshNotifications($this->addPagination($notifications['total'], ['data' => $notifications['data']])));

        if($data['customer_id']) {
            $customer = Customer::find($data['customer_id']);
            if($customer) {
                $send_email = $customer->user->email;
                $fullname = $customer->user->firstname. " " . $customer->user->lastname;
                /*
                Mail::send('consumer.email.register', $result, function($message) use ($fullname, $send_email)
                {
                   $message->to($send_email, $fullname)->subject('Welcome to FIVMOON');
                });
                */
                $mail_data = array('firstname'=>$fullname,'send_email'=>$send_email);
                Mail::send('email.reservetable',$mail_data , function($message) use ($fullname, $send_email) {
                   $message->to($send_email, $fullname)->subject('Reserve table successful!');
                });
            }
        }
        return $this->respond(['data' => $reservable]);
    }

    public function walkin()
    {
        $order_type = 'Walkin';

        $data = Input::all();

        $validator = $this->r_reservable->validateData($data, $order_type);
        if ($validator->fails()){
            return $this->respondPostValidationError($validator->messages());
        }

        $table_validity = $this->r_reservable->validateTable($data);

        if ($table_validity['has_errors']){
            return $this->respondPostError($table_validity['message']);
        }

        $reservable = $this->r_reservable->create($data, $order_type);
        return $this->respond(['data' => $reservable]);
    }
} 