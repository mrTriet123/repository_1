<?php
namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Input as Input;
use Validator;
//use App\Http\Requests\Request;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use App\Customer;
use App\Installation;
use Illuminate\Http\Response;
use Hash;
use Auth;
use LucaDegasperi\OAuth2Server\Authorizer as Authorizer;
use DB;
use App\Repositories\Stripe\StripeRepository;

class UserController extends ApiController
{
    protected $statusCode = 200;
    protected $stripe;

    function __construct(StripeRepository $strRepo)
    {
        $this->stripe = $strRepo;
    }
    
    public function index(Authorizer $authorizer)
    {
        $user_id = $authorizer->getResourceOwnerId();
        $user = User::find($user_id);
        $userDetail = $this->userDetails($user);
        return $this->respond(['data' => $userDetail]);
    }

    public function oauthLogin($username, $password) {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            $authUser = Auth::user()->id;
            $this->deletePreviousToken($authUser);
            return $authUser;
        }else{
            return false;
        }

    }

    private function deletePreviousToken($id)
    {
        $tokens = DB::table('oauth_sessions')->select('id')->where('owner_type', 'user')->where('owner_id', $id)->orderBy('created_at', 'desc')->get();
        foreach ($tokens as $token) {
            DB::table('oauth_sessions')->where('owner_type', 'user')->where('id', $token->id)->delete();
        }
    }

    public function userDetails($user)
    {
        $roles = $user->roles;
        $array = array(
            'user_id' => $user->id,
            'customer_id' => $user->customer->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'mobile_no' => $user->customer->mobile_no,
            'role_id' => $roles[0]->id,
            'role_name' => $roles[0]->name,
            'saved_cards' => $this->stripe->getSavedCards($user->customer->stripe_customer_id)
        );
        return $array;
    }

    public function signup()
    {
        $rules = array(
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|email|unique:users',
            'password' => 'required|min:8',
            'mobile_no'   => 'required|between:5,20|unique:customers'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ( $validator->fails() )
        {
           return $this->respondPostValidationError($validator->messages());
        }
        else
        {
            $stripe_customer_id = NULL;
            if (Input::get('stripe_card_token'))
            {
                $stripe_customer = $this->stripe->createCustomer(Input::get('stripe_card_token'), Input::get('email'));
                
                if (isset($stripe_customer['error'])){
                    return $this->respondWithError($stripe_customer['error']);
                } else {
                    $stripe_customer_id = $stripe_customer->id;
                }
            }

            $userData = array(
                'firstname' => Input::get('firstname'),
                'lastname' => Input::get('lastname'),
                'email' => Input::get('email'),
                'password' => bcrypt(Input::get('password'))
                );
            $user = User::create($userData);
            
            $role = Role::where('name', '=', 'customer')->first();
            $user->attachRole($role);
            $customerData = array(
                'user_id' => $user->id,
                'mobile_no' => Input::get('mobile_no'),
                'stripe_customer_id' => $stripe_customer_id
            );
            $newCustomer = Customer::create($customerData);

            //
            //send email to welcome consumer
            $fullname = $user->firstname. ' '. $user->lastname;
            $send_email = $user->email;
            Mail::send('consumer.email.register', $result, function($message) use ($fullname, $send_email)
            {
                $message->to($send_email, $fullname)->subject('Welcome to FIVMOON');
            });
            return $this->respondCreated('Register Successfully.',Input::all());
            // return $this->respondCreated('Register Successfully.',$result);
        }
    }

    public function updateUserInformation($id)
    {
        $user = User::find($id);
        if($user)
        {
            $rules = array(
                'firstname' => 'required',
                'lastname' => 'required',
                'mobile_no' => 'required',
                'email' => 'required|email'
            );
            $validator = Validator::make(Input::all(), $rules);

            if ( $validator->fails() ) {
                return $this->respondPostValidationError($validator->messages());
            }else{
                $data = Input::all();
                $user->update($data);

                if ($user->roles[0]->name == 'customer'){
                    $user->customer->update($data);
                    $user['mobile_no'] = $user->customer->mobile_no;
                    unset($user['roles']);
                    unset($user['customer']);
                }

                return $this->respondUpdated('Successfully Update', $user);
            }
        }
        else
        {
            return $this->respondNotFound('User does not exist');
        }

    }

    public function updateUserPassword($id)
    {
        $user = User::find($id);
        if ($user) {
            $rules = array(
                'old_password' => 'required',
                'password' => 'required|min:8',
                // 'password_confirmation' => 'required|min:8'
            );
            $allInput = Input::all();
            $validator = Validator::make($allInput, $rules);

            if ( $validator->fails() ) {
                return $this->respondPostValidationError($validator->messages());
            }else {
                if(!Hash::check(Input::get('old_password'), $user->password))
                {
                    return $this->respondPostError('Please Key In The Right Old Password');
                }
                unset($allInput['email']);
                $allInput['password'] = bcrypt(Input::get('password'));
                $user->update($allInput);

                if ($user->roles[0]->name == 'customer'){
                    $user['mobile_no'] = $user->customer->mobile_no;
                    unset($user['roles']);
                    unset($user['customer']);
                }
                return $this->respondUpdated('Successfully Change Password', $user);
            }
        } else {
            return $this->respondNotFound('User does not exist');
        }
    }

    public function logout(Authorizer $authorizer)
    {
        $user_id=$authorizer->getResourceOwnerId(); 
        $authorizer->getChecker()->getAccessToken()->expire();
        return $this->respond(['data' => ['message' => 'Successfully logout']]);
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

    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = array('email' => $email, 'password' => $password);
        if (Auth::attempt($credentials,true)) {
            $user = Auth::user();
            $role = $user->roles[0]->name;
            //$user->update(['token' => $token]);
            $user->token = $this->generateToken(10);
            $user->save();

            $responseData = [
                'result' => 1,
                'data' => [
                    'id' => $user->id,
                    'firstname' => $user->firstname, 
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'token' => $user->token,
                    'role'=> $role 
                ]
            ];
            return $this->respond($responseData);
        }else{
            $messages = new MessageBag(['messages' => 'Email or password not found!']);


            return response()->json([
                'result' => 0,
                'messages' => $messages
            ],200);
        }

    }

}