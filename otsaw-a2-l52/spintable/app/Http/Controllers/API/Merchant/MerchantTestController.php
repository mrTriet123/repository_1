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
use App\Restaurant;
use App\Customer;
use App\Installation;
use Illuminate\Http\Response;
use Hash;
use Auth;
use LucaDegasperi\OAuth2Server\Authorizer as Authorizer;
use DB;
use App\Repositories\Stripe\StripeRepository;

class MerchantTestController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        Mail::send(['text' => 'view'], ['data'], function ($m) {
            $m->from('hello@app.com', 'Your Application');

            $m->to("duongqhai@gmail.com", "Hai Duong")->subject('Your Reminder!');
        });
        */
        Mail::raw('Text to e-mail', function ($m) {
            //function ($m) use ($user) {
            $m->from('hello@app.com', 'Your Application');

            $m->to("duongqhai@gmail.com", "Hai Duong")->subject('Your Reminder!');
        });
        //
        $merchants = Role::find(2)->users()->select('id','firstname','lastname','email')->get();
        
        return response()->json([
            'result' => 1,
            'data' => $merchants
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
