<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Reservable\ReservableRepository;
use App\Repositories\ActivityLog\ActivityLogRepository;
use App\Repositories\Stripe\StripeRepository;
use App\Http\Controllers\UploadFileController;
use Carbon\Carbon;
use Helpers;
use Mail;

class OrderController extends ApiController{

    protected $statusCode = 200;
    protected $r_order;
    protected $r_reservable;
    protected $r_activitylog;
    protected $stripe;

    function __construct(OrderRepository $ordRepo, ReservableRepository $resRepo, ActivityLogRepository $actRepo, StripeRepository $strRepo)
    {
        $this->r_order = $ordRepo;
        $this->r_reservable = $resRepo;
        $this->r_activitylog = $actRepo;
        $this->stripe = $strRepo;
    }

    public function checkout()
    {
        $data = Input::all();

        $validator = $this->r_order->validateData($data);
        if ($validator->fails()){
            return $this->respondPostValidationError($validator->messages());
        }

        $reservable = $this->r_reservable->getDetails($data['reservable_id']);

        $data['status'] = 'Upcoming';
        $data['type'] = $reservable->type;
        if ($reservable->type == 'Walkin'){
            $data['status'] = 'Serving';
        }

        $stripe_customer_id = $reservable->customer->stripe_customer_id;
        $connected_stripe_account_id = $reservable->merchant->connected_stripe_account_id;

        $charge = $this->stripe->createCharge($stripe_customer_id, $data['total_amount'], $connected_stripe_account_id);
        // var_dump($charge);
        // exit();
        /*
        if (isset($charge['error'])){
            return $this->respondWithError($charge['error'], $charge['dev_error']);
        }
        */
        $order = $this->r_order->checkout($data);

        // if type is reservation, trigger order and booking channel
        // if type is walkin, trigger walkin channel

        if ($data['type'] == 'Reservation') {
            if ($reservable['start_date'] == Carbon::now()->toDateString()){
                $orders = $this->r_reservable->getReservableListOfMerchant($reservable->merchant_id, 'Ordered', Carbon::now()->toDateString());

                // Trigger web booking channel
                \Event::fire(new \App\Events\UpdateOrderList($orders['data']));

                $bookings = $this->r_reservable->getReservableListOfMerchant($reservable->merchant_id, 'Reservation', Carbon::now()->toDateString());

                // Trigger web booking channel
                \Event::fire(new \App\Events\UpdateBookingList($bookings['data']));
            }

            // Log activity
            Helpers::logActivity($data['reservable_id'], 'Ordered');
        }

        if ($data['type'] == 'Walkin') {
            $walkins = $this->r_reservable->getReservableListOfMerchant($reservable->merchant_id, 'Walkin', Carbon::now()->toDateString());

            // Trigger web booking channel
            \Event::fire(new \App\Events\UpdateWalkinList($walkins['data']));

            // Log activity
            Helpers::logActivity($data['reservable_id'], 'Walkin');
        }
        $notifications = $this->r_activitylog->recentNotifications($reservable->merchant_id);
        \Event::fire(new \App\Events\RefreshNotifications($this->addPagination($notifications['total'], ['data' => $notifications['data']])));

        $send_email =   $reservable->customer->user->email;
        $fullname   =   $reservable->customer->user->firstname. " " . $reservable->customer->user->lastname;
        $mail_data  =   array('firstname'=>$fullname,'send_email'=>$send_email);
        Mail::send('email.order',$mail_data , function($message) use ($fullname, $send_email) {
           $message->to($send_email, $fullname)->subject('Order successful!');
        });      
       
        return $this->respond(['data' => $order]);
    }
} 