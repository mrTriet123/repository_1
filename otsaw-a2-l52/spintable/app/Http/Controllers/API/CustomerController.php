<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Reservable\ReservableRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Stripe\StripeRepository;
use App\Http\Controllers\UploadFileController;

class CustomerController extends ApiController{

    protected $statusCode = 200;
    protected $r_order;
    protected $r_reservable;
    protected $r_customer;
    protected $stripe;

    function __construct(OrderRepository $ordRepo, ReservableRepository $resRepo, CustomerRepository $cusRepo, StripeRepository $strRepo)
    {
        $this->r_order = $ordRepo;
        $this->r_reservable = $resRepo;
        $this->r_customer = $cusRepo;
        $this->stripe = $strRepo;
    }

    public function orders($customer_id)
    {   
        $filter = Input::get('filter');
        $reservable_id = Input::get('reservable_id');

        if ($filter && in_array($filter, array('past', 'upcoming'))){
            $result = $this->r_reservable->getReservableListOfCustomer($customer_id, $filter);
            return $this->respondWithPagination($result['total'], [ 'data' => $result['data']]);
        }

        if ($reservable_id){
            $menu = $this->r_order->getOrderDetails($reservable_id);
            if (!$menu){
                return $this->respondPostError('Reservable ID does not exist.');
            }
            return $this->respond(['data' => $menu]);
        }

        return $this->respondNotFound();
    }

    public function addCard($customer_id)
    {
        $stripe_card_token = Input::get('stripe_card_token');
        $customer = $this->r_customer->getByID($customer_id);

        if (!$customer){
            return $this->respondPostError('Customer does not exist');
        }

        if (empty($customer->stripe_customer_id)) { // if customer doesnt have any stripe data yet, create for them
            $stripe_customer = $this->stripe->createCustomer($stripe_card_token, $customer->user->email);
            if (isset($stripe_customer['error'])){
                return $this->respondWithError('Something went wrong. [CC]', $stripe_customer['error']);
            }

            $customer->update(array('stripe_customer_id' => $stripe_customer->id));
        } else { // else, use their stripe_customer_id
            $card = $this->stripe->addCard($customer->stripe_customer_id, $stripe_card_token);
            if (isset($card['error'])){
                return $this->respondWithError('Something went wrong. [CA]', $card['error']);
            }
        }
        
        $customer->saved_cards = $this->stripe->getSavedCards($customer->stripe_customer_id);
        return $this->respondCreated('Success', $customer);
    }

    public function destroyCard($customer_id, $card_id)
    {
        $customer = $this->r_customer->getByID($customer_id);

        if (!$customer){
            return $this->respondPostError('Customer does not exist');
        }

        if (empty($customer->stripe_customer_id)) {
            return $this->respondPostError('Card does not exist');
        }

        $card = $this->stripe->deleteCard($customer->stripe_customer_id, $card_id);
        if (isset($card['error'])){
            return $this->respondWithError('Something went wrong. [CU]', $card['error']);
        }
        return $this->respondCreated('Success', $card);
    }
} 