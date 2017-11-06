<?php 
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input as Input;
use App\Repositories\Reservable\ReservableRepository;
use App\Repositories\Merchant\MerchantRepository;
use App\Repositories\Order\OrderRepository;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use App\Merchant;
use App\Restaurant;
use App\Menu;
use App\RestaurantLocation;
use App\RestaurantType;
use Mail;
use Auth;
use DB;


class MerchantController extends ApiController{

    protected $statusCode = 200;
    protected $r_reservable;
    protected $r_order;
    protected $r_merchant;

    function __construct(ReservableRepository $resRepo, OrderRepository $ordRepo, MerchantRepository $merRepo)
    {
        $this->r_reservable = $resRepo;
        $this->r_order = $ordRepo;
        $this->r_merchant = $merRepo;
    }

    public function save_stripe_account_id()
    {
        $code = $access_token;

        $token_request_body = array(
            'grant_type' => 'authorization_code',
            'client_id' => 'ca_APVZPJSguUG5RIVTvep4NVO75t3tFFd9',
            'code' => $code,
            'client_secret' => 'sk_test_yzH3DYUYWKYc6XnHhxvjw7ci'
        );
        $req = curl_init("https://connect.stripe.com/oauth/token");
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

        // TODO: Additional error handling
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $resp = json_decode(curl_exec($req), true);
        curl_close($req);

        if (isset($resp['stripe_user_id'])) {
            $this->merchant->saveStripeAccountID($resp['stripe_user_id']);
        } else {
            // var_dump("Something went wrong.");
        }

        var_dump($resp);
    }

    public function orders($merchant_id)
    {
        $date = Input::get('date');
        $orders = $this->r_reservable->getReservableListOfMerchant($merchant_id, 'Ordered', $date);
        return $this->respondWithPagination($orders['total'], ['data' => $orders['data']]);
    }

    public function bookings($merchant_id)
    {
        $date = Input::get('date');
        $orders = $this->r_reservable->getReservableListOfMerchant($merchant_id, 'Reservation', $date);
        return $this->respondWithPagination($orders['total'], ['data' => $orders['data']]);
    }

    public function walkins($merchant_id)
    {
        $date = Input::get('date');
        $orders = $this->r_reservable->getReservableListOfMerchant($merchant_id, 'Walkin', $date);
        return $this->respondWithPagination($orders['total'], ['data' => $orders['data']]);
    }

    public function getReservableInfo($merchant_id, $reservable_id)
    {
        $reservable = $this->r_reservable->getDetails($reservable_id);
        $reservable_details = $this->r_reservable->formatReservableInfoMerchant($reservable);
        $order_details = $this->r_order->getOrderDetails($reservable_id);

        $result = array_merge($reservable_details, $order_details);
        return $this->respond(['data' => $result]);
    }

    public function history($merchant_id)
    {
        $type = Input::get('type');
        $date = Input::get('date');
        $result = $this->r_reservable->getHistory($merchant_id, $type, $date);
        return $this->respondWithPagination($result['total'], ['data' => $result['data']]);
    }

    public function reports($merchant_id)
    {
        exit('dfgh');
        // $start_date = Input::get('start_date');
        // $end_date = Input::get('end_date');
        // $result = $this->r_report->generate($merchant_id, $start_date, $end_date);

        $result = array(
                        "overview" => array(
                            "total_sales" => array(
                                "sales" => 5699,
                                "items_sold" => 567,
                            ),
                            "reservations" => array(
                                "sales" => 2699,
                                "items_sold" => 567,
                            ),
                            "takeaway" => array(
                                "sales" => 2392,
                                "items_sold" => 432,
                            ),
                            "walkin" => array(
                                "sales" => 1050,
                                "items_sold" => 124,
                            ),
                            "total_clients" => 1134,
                            "month" => "January"
                        ),
                        "sales_by_category" => array(
                            "0" => array(
                                "category_name" => "Breakfast",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 1234,
                                "items_sold" => 21
                            ),
                            "1" => array(
                                "category_name" => "Lunch",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 332,
                                "items_sold" => 11
                            ),
                            "2" => array(
                                "category_name" => "Dinner",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 213,
                                "items_sold" => 7
                            ),
                            "3" => array(
                                "category_name" => "Breakfast",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 4244,
                                "items_sold" => 88
                            ),
                            "4" => array(
                                "category_name" => "Lunch",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 1233,
                                "items_sold" => 16
                            ),
                            "5" => array(
                                "category_name" => "Dinner",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 5544,
                                "items_sold" => 37
                            ),
                        ),
                        "drinks_sales" => array(
                            "0" => array(
                                "category_name" => "Hot",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 223,
                                "items_sold" => 379
                            ),
                            "1" => array(
                                "category_name" => "Cold",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 3221,
                                "items_sold" => 495
                            )
                        ),
                        "rockstar" => array(
                            "0" => array(
                                "rank" => 1,
                                "dish_name" => "Fried Bun",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 9023,
                                "items_sold" => 961
                            ),
                            "1" => array(
                                "rank" => 1,
                                "dish_name" => "Ayam Penyet",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 8011,
                                "items_sold" => 944
                            ),
                            "2" => array(
                                "rank" => 1,
                                "dish_name" => "Pot Noodle",
                                "image" => "https://s3-ap-southeast-1.amazonaws.com/hillman-dev/hillman_images/featured_pic.png",
                                "sales" => 7521,
                                "items_sold" => 912
                            )
                        ),
        );

        return $this->respond(['data' => $result]);
    }
    public function generateToken($length) {
        $time = time();
        $valid_chars = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num_valid_chars = strlen($valid_chars);
        $random_string = "";
        // repeat the steps until we've created a string of the right length
        for ($i = 0; $i < $length; $i++)
        {
            // pick a random number from 1 up to the number of valid chars
            $random_pick = mt_rand(1, $num_valid_chars);

            // take the random character out of the string of valid chars
            // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
            $random_char = $valid_chars[$random_pick-1];

            // add the randomly-chosen char onto the end of our string so far
            $random_string .= $random_char;
        }
        return $random_string.$time;
    }

    public function merSignup(Request $request){

        // $token = Role::find(1)->users()->select('token')->get();
        $checkEmail = User::where('email',$request->input('email'))->count();

        
        if(!$checkEmail) {
            $user = new User;

            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $password = User::generateRandomPassword();
            $user->password = bcrypt($password);
            $user->token = $this->generateToken(10);
            $user->active = 1;
            $checkUser = $user->save();
            try {
                if($checkUser) {
                    
                    $data = [
                       'role_id' => 2,
                       'user_id' => $user->id,
                    ];
                    DB::table('role_user')->insert($data);

                    $merchant = new Merchant;
                    $merchant->user_id = $user->id;
                    $merchant->mobile_no = '0';
                    $merchant->connected_stripe_account_id = '';
                    $merchant->step = 1;
                    $merchant->save();

                    $resaurant = new Restaurant;
                    $resaurant->merchant_id = $merchant->id;
                    //$resaurant->name = $request->input('name');
                    $resaurant->name = $request->input('restaurant_name');
                    $resaurant->tel_no = $request->input('tel_no');
                    //$resaurant->tel_no = '';
                    $resaurant->restaurant_type_id = $request->restaurant_type;
                    $resaurant->operating_hour_start = "07:00:00";
                    $resaurant->operating_hour_end = "18:00:00";
                    $resaurant->gst = 0.00;
                    $resaurant->service_charge = 0.00;
                    $resaurant->is_featured = 0;
                    $resaurant->save();

                    $menu = new Menu;
                    $menu->merchant_id = $merchant->id;
                    $menu->save();

                    if($request->input('location') != null){
                        $resaurant_location = new RestaurantLocation;
                        $resaurant_location->restaurant_id = $resaurant->id;
                        $resaurant_location->location = $request->input('location');
                        $resaurant_location->save();
                    }
                    
                    if (isset($_SERVER['HTTP_REFERER'])) {
                        $url_get = $_SERVER['HTTP_REFERER'];
                        $pieces = explode("/", $url_get);
                        $url = ($pieces[0].'//'.$pieces[2]);
                    }else{
                        $url = 'http://localhost:3000';
                    }
                    $mail_data = array('firstname'=>Input::get('firstname'),'password'=>$password,'url'=> $url,'token'=>$user->token);
                    Mail::send('email.welcome',$mail_data , function($message){
                        $message->to(Input::get('email'), Input::get('firstname').' '.Input::get('lastname'))->subject('Welcome to!');
                    });
                    return response()->json([
                        'result' => 1,
                        'messages' => "Account has been created"
                    ],200);
                } else {
                    return response()->json([
                        'result' => 0,
                        'messages' => "Account has not been created"
                    ],200);
                }
            } catch(\Exception $e){
            //} catch(\QueryException $e){
                return response()->json([
                    'result' => 0,
                    'messages' => $e->getMessage()
                ],200);
            }
        } else {
            return response()->json([
                'result' => 0,
                'messages' => "This email has been used"
            ],200);
        }
    }

    public function merLogin(Request $request){

        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = array('email' => $email, 'password' => $password);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = $user->roles[0]->name;
            // return $this->respond(['dt'=>$user->roles[0]->name]);
            if($role == 'merchant'){
                $merchant = Merchant::where('user_id',$user->id)->first();
                //$resId = Restaurant::select('id')->where('merchant_id',$merchant->id)->first();
                $restaurant = Restaurant::where('merchant_id',$merchant->id)->first();
                $data = [
                    'id' => $user->id,
                    'firstname' => $user->firstname, 
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'token' => $user->token,
                    'role'=> $role,
                    'mobile_no' => $merchant->mobile_no,
                    'merchant_id' => $merchant->id,
                    'restaurant' => [
                        'id'    =>  $restaurant->id,
                        'name'  =>  $restaurant->name,
                    ]
                ];
                if($merchant->step == 1){
                    return response()->json([
                        'result' => 1,
                        'data' => $data
                    ],200);
                }
                if($merchant->step == 2){
                    return response()->json([
                        'result' => 2,
                        'data' => $data
                    ],200);
                }
                if($merchant->step == 3){
                    return response()->json([
                        'result' => 3,
                        'data' => $data
                    ],200);
                }
                if($merchant->step == 4){
                    return response()->json([
                        'result' => 4,
                        'data' => $data
                    ],200);
                }
            }
        }else{
            $messages = new MessageBag(['messages' => 'You do not have access to this page!']);

            return response()->json([
                'result' => 0,
                'messages' => $messages
            ],200);
        }

    }

    public function updateMerPassword(Request $request)
    {
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        if ($user && $user->roles[0]->name == 'merchant'){
            $rules = array(
                'password_old' => 'required',
                'password_new' => 'required|min:8',
                // 'password_confirmation' => 'required|min:8'
            );
            $allInput = Input::all();
            $validator = Validator::make($allInput, $rules);
            if ( $validator->fails() ) {
                return $this->respondPostValidationError($validator->messages());
            }else {
                if(!Hash::check(Input::get('password_old'), $user->password))
                {
                    return $this->respondPostError('Please Key In The Right Old Password');
                }
                unset($allInput['email']);
                $allInput['password'] = bcrypt(Input::get('password_new'));
                $user->update($allInput);

                // $user->merchant->step = 2;
                $merchant = Merchant::where('user_id',$user->id)->first();
                $merchant->step = 2;
                $merchant->save();

                return response()->json([
                    'result' => 1,
                ],200);
                
            }
        } else {
            return response()->json([
                'result' => 0,
            ],200);
        }
    }

    public function setupPage(Request $request){
        dd('dfghjk');
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $restaurant = Restaurant::where('merchant_id',$merchant->id)->first();

        $rules = array(
            'account_name' => 'required',
            'describe_restaurant' => 'required',
        );
        // $json = '
        // {
        //     "employees": [
        //         { "email": "xxx@gmail.com" },
        //         { "email": "xxxxx@gmail.com" },
        //         { "email": "xxxxxxxx@gmail.com" }
        //     ]
        // }';
        
        $validator = Validator::make($request->all(), $rules);
        if ( $validator->fails() ) {
            return $this->respondPostValidationError($validator->messages());
        }else {
            
            $account_name = $request->input('account_name');
            
            if(filter_var($account_name, FILTER_VALIDATE_EMAIL)) {
                $user->email = $account_name;
                $user->save();
            }
            else {
                $pieces = explode(" ", $account_name);
                $firstname = $pieces[0];
                $lastname = $pieces[1];
                $user->firstname = $firstname;
                $user->lastname = $lastname;
                $user->save();
            }

            $restaurant->describe = $request->input('describe_restaurant');
            $restaurant->save();
            
            if (isset($_SERVER['HTTP_REFERER'])) {
            $url_get = $_SERVER['HTTP_REFERER'];
            $pieces = explode("/", $url_get);
            $url = ($pieces[0].'//'.$pieces[2]);
            }else{
                $url = 'http://localhost:3000';
            }
            $employees = $request->employees;
            $yummy = json_decode($employees, true);

            foreach ($yummy as $value) {
                $emailS = $value['email'];
                $mail_data = array('email'=>$value['email'],'firstname' => $user->firstname,'lastname' => $user->lastname,'url'=>$url);
                Mail::send('email.pagesetup',$mail_data , function($message) use ($emailS){
                    $message->to($emailS)->subject('Welcome to!');   
                }); 
            }

            $merchant->stripe_token = $request->stripe_token;
            $merchant->step = 3;
            $merchant->save();

            return response()->json([
                'result' => 1,
            ],200);

        }

    }

} 