<?php
namespace App\Http\Controllers\API\Merchant;

use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Input as Input;
use Validator;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use App\Merchant;
use App\MerchantTable;
use App\Restaurant;
use App\Customer;
use App\OrderSetting;
use App\Installation;
use App\Category;
use App\Dish;
use App\Menu;
use App\HappyHour;
use App\DishCategory;
use Illuminate\Http\Response;
use Hash;
use Auth;
use LucaDegasperi\OAuth2Server\Authorizer as Authorizer;
use DB;
use App\Repositories\Stripe\StripeRepository;

class MerchantController extends ApiController
{
    

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
                exit('a');
                return $this->respondPostValidationError($validator->messages());
            }else {

                if(!Hash::check(Input::get('password_old'), $user->password))
                {   
                    return response()->json([
                        'result' => 0,
                        'messages' => 'Please Key In The Right Old Password'
                    ],200);
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

        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $restaurant = Restaurant::where('merchant_id',$merchant->id)->first();

        $rules = array(
            'account_name' => 'required',
            'describe_restaurant' => 'required',
        );
        
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
                $lastname = "";
                if (isset($pieces[1])) {
                    # code...
                    $lastname = $pieces[1];
                }
                $user->firstname = $firstname;
                $user->lastname = $lastname;
                $user->save();
            }

            $uploaddir = config('app.uploadfolder');
            foreach($_FILES as $file)
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
                {
                    $files[] = $uploaddir .$file['name'];
                }
                else
                {
                    $error = true;
                }
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
            /*
            $employees = str_replace('%5B', '[', $employees);
            $employees = str_replace('%7B', '{', $employees);
            $employees = str_replace('%22', "\"", $employees);
            $employees = str_replace('%40', '@', $employees);
            $employees = str_replace('%3A', ':', $employees);
            $employees = str_replace('%7D', '}', $employees);
            $employees = str_replace('%5D', ']', $employees);
            */
            $employees = urldecode($employees);
            $yummy = json_decode($employees, true);
            foreach ($yummy as $value) {
                $emailS = $value['email'];
                if (isset($emailS) && filter_var($emailS, FILTER_VALIDATE_EMAIL)) {
                     $mail_data = array('email'=>$value['email'],'firstname' => $user->firstname,'lastname' => $user->lastname,'url'=>$url);
                    Mail::send('email.pagesetup',$mail_data , function($message) use ($emailS){
                        $message->to($emailS)->subject('Welcome to!');   
                    });
                }
            }
            
            define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
            if (isset($request['stripe_token'])) { // Redirect w/ code
                $code = $request['stripe_token'];
                $token_request_body = array(
                    'client_secret' => 'sk_test_kHbDgnDuIlsChjKBQ0aXzAMP',
                    'grant_type' => 'authorization_code',
                    'client_id' => 'ca_APVZPJSguUG5RIVTvep4NVO75t3tFFd9',
                    'code' => $code,
                );
                $req = curl_init(TOKEN_URI);
                curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($req, CURLOPT_POST, true );
                curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
                // TODO: Additional error handling
                $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
                $resp = json_decode(curl_exec($req), true);
                curl_close($req);
                // echo $resp['access_token'];

                $merchant->stripe_token = $resp['access_token'];
                $merchant->step = 3;
                $merchant->save();
            }

            return response()->json([
                'result' => 1,
            ],200);
        }

    }

    public function settingOrder(Request $request){
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();

        if(count($merchant) > 0 && $merchant->step == 3){
            try {
                $order_settings = new OrderSetting;
                $order_settings->merchant_id = $merchant->id;
                $order_settings->reservation_start_time = $request['start_time'];
                $order_settings->reservation_end_time = $request['end_time'];
                $order_settings->eating_hours = $request['hour_slot_per_table'];
                $order_settings->save();

                $merchant_table = $request->merchant_table;
                $yummy = json_decode($merchant_table, true);
                
                foreach ($yummy['records'] as $value) {
                    $merchant_tables = new MerchantTable;
                    $merchant_tables->merchant_id = $merchant->id;
                    $merchant_tables->table_no = $value['table_no'];
                    $merchant_tables->capacity = $value['capacity'];
                    $merchant_tables->is_reserved = $value['is_reserved'];
                    $merchant_tables->save();
                }

                $checkRepeat = $request->input('repeat');

                $happy_hour = new HappyHour;
                $happy_hour->merchant_id    = $merchant->id;
                $happy_hour->name           = $request['offer_name'];
                $happy_hour->start_time     = $request['from'];
                $happy_hour->end_time       = $request['to'];
                $happy_hour->discount_type  = $request['discount_type'];
                $happy_hour->total_discount = $request['total_discount'];
                $happy_hour->repeat = ($checkRepeat == "on")? 1: 0;
                $happy_hour->save();

                $dish_id_re = $request->dish_id;
                $dish_id_arr = json_decode($dish_id_re, true);
                foreach ($dish_id_arr['records'] as $value) {
                    $happy_hour_dishes = DB::table('happy_hour_dishes')->where([['dish_id', $value["id"]],['happy_hour_id', $happy_hour->id]])->first();
                    if (empty($happy_hour_dishes)) {
                        DB::table('happy_hour_dishes')->insert(
                            ['happy_hour_id' => $happy_hour->id, 'dish_id' => $value["id"]]
                        );
                    }   
                }
                $merchant->step = 4;
                $merchant->save();

                return response()->json([
                    'result' => 1,
                    'messages' => 'Settings Order success!'
                ],200);
            } catch(\Exception $e){
                return response()->json([
                    'result' => 0,
                    'messages' => $e->getMessage()

                ],200);
            }
        } else {
            return response()->json([
                'result' => 0,
                'messages' => 'Settings Order unsuccess! Something wrong with your data.'
            ],200);
        }
    }

    public function editSettingOrder(Request $request){
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        $order_setting = OrderSetting::where('merchant_id',$merchant->id)->first();
        $happy_hour = HappyHour::where('merchant_id',$merchant->id)->first();
        if(count($merchant) > 0 && $merchant->step == 4){
            try {
                if($order_setting){
                    $order_setting->reservation_start_time = $request['start_time'];
                    $order_setting->reservation_end_time = $request['end_time'];
                    $order_setting->eating_hours = $request['hour_slot_per_table'];
                    $order_setting->save();
                } else {
                    return response()->json([
                        'result' => 0,
                        'messages' => 'Order Settings not existing!'
                    ],200);
                }
                $merchant_table = $request->merchant_table;
                $yummy = json_decode($merchant_table, true);
                
                MerchantTable::where('merchant_id', $merchant->id)->delete();
                foreach ($yummy['records'] as $value) {
                    $merchant_tables = new MerchantTable;
                    $merchant_tables->merchant_id = $merchant->id;
                    $merchant_tables->table_no = $value['table_no'];
                    $merchant_tables->capacity = $value['capacity'];
                    $merchant_tables->is_reserved = $value['is_reserved'];
                    $merchant_tables->save();
                }

                $checkRepeat = $request->input('repeat');
                if($happy_hour){
                    $happy_hour->name = $request['offer_name'];
                    $happy_hour->start_time = $request['from'];
                    $happy_hour->end_time = $request['to'];
                    $happy_hour->discount_type = $request['discount_type'];
                    $happy_hour->total_discount = $request['total_discount'];
                    if($checkRepeat == "on"){
                        $happy_hour->repeat = 1;
                    }else{
                        $happy_hour->repeat = 0;
                    }

                    $happy_hour->save();
                }

                $dish_id_re = $request->dish_id;
                $dish_id_arr = json_decode($dish_id_re, true);

                DB::table('happy_hour_dishes')->where('happy_hour_id', '=', $happy_hour->id)->delete(); 
                foreach ($dish_id_arr['records'] as $value) {
                    $happy_hour_dishes = DB::table('happy_hour_dishes')->where([['dish_id', $value["id"]],['happy_hour_id', $happy_hour->id]])->first();
                    if (empty($happy_hour_dishes)) {
                        DB::table('happy_hour_dishes')->insert(
                            ['happy_hour_id' => $happy_hour->id, 'dish_id' => $value["id"]]
                        );
                    }   
                }
                
                return response()->json([
                    'result' => 1,
                    'messages' => 'Settings Order success!'
                ],200);

            } catch(\Exception $e){
                return response()->json([
                    'result' => 0,
                    'messages' => $e->getMessage()

                ],200);
            }
        }else{
            return response()->json([
                'result' => 0,
                'messages' => 'Settings Order unsuccess! Something wrong with your data.'
            ],200);
        }
    }

    public function getData(){
        
        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        
        if(count($merchant) > 0 && $merchant->step > 2) {
            $order_setting = OrderSetting::where('merchant_id',$merchant->id)->first();
            $merchant_table = MerchantTable::where('merchant_id',$merchant->id)->get();
            $happy_hour = DB::table('happy_hour')->where('merchant_id',$merchant->id)->first();

            $data_order = [];
            if ($order_setting) {
                $data_order = [
                    'start_time' => $order_setting->reservation_start_time,
                    'end_time' => $order_setting->reservation_end_time,
                    'hour_slot_per_table' => $order_setting->eating_hours,
                ];
            }else{
                $data_order = null;
            }

            $table_no = array();
            if ($merchant_table) {
                foreach ($merchant_table as $key => $value) {
                    $table_no[] = array(
                                    'table_no'      => $value['table_no'],
                                    'capacity'      => $value['capacity'], 
                                    'is_reserved'   => $value['is_reserved']
                                );
                }
            }else{
                $data_table = null;
            }
            $table = json_encode($table_no);

            $data_hour = [];
            if ($happy_hour) {
                $happy_hour_dishes = DB::table('happy_hour_dishes')->where('happy_hour_id',$happy_hour->id)->get();
                $dishes = [];
                foreach ($happy_hour_dishes as $key => $value) {
                    $dishes[] = $value->dish_id;
                }
                $data_hour = [
                    'offer_name' => $happy_hour->name,
                    'discount_type' => $happy_hour->discount_type,
                    'total_discount' => $happy_hour->total_discount,
                    'repeat' => $happy_hour->repeat,
                ];
            }else{
                $data_hour = null;
                $dishes = null;
            }
            return response()->json([
                'result' => 1,
                'data' => [
                    'data_order' => $data_order,
                    'table' => $table,
                    'special_offers' => $data_hour,
                    'dishes' => $dishes,
                ]
            ],200);
        } else {
            return response()->json([
                'result' => 0,
                'messages' => 'Settings Order unsuccess! Something wrong with your data.'
            ],200);
        }
    }

    public function listCategoryDishes(){

        $token =  $_GET['token'];
        $user = User::where('token',$token)->first();
        $merchant = Merchant::where('user_id',$user->id)->first();
        if(count($merchant) > 0){
            $menu = Menu::where('merchant_id',$merchant->id)->first();
            $category = Category::where('menu_id',$menu->id)->get();
            $data = [];
            foreach ($category as $cate) {
                # code...
                $dish_category = DishCategory::where('category_id',$cate->id)->get();
                // $dish_category = DishCategory::where('category_id',$cate->id)->get();
                $dishes = [];
                foreach ($dish_category as $di_ca) {
                    # code...
                    $dishes[] = Dish::select('id','name')->where('id',$di_ca->dish_id)->first();
                }

                $data[] = [
                    'category_id' => $cate->id,
                    'category_name' => $cate->name,
                    'dishes' => $dishes,
                ];           
            }



            return response()->json([
                'result' => 1,
                'data' => $data
            ],200);
        }
        return response()->json([
            'result' => 0,
        ],200);
            
    }
}