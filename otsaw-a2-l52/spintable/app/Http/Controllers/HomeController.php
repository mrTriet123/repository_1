<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
// use App\Repositories\Category\CategoryRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $asd = \Stripe\Customer::all(array("limit" => 3));

        $rq = \Stripe\Token::create(array(
          "card" => array(
            "number" => "4242424242424242",
            "exp_month" => 3,
            "exp_year" => 2018,
            "cvc" => "314"
          )
        ));
        $token = $rq->id;
        //var_dump($token);
        // $token = "tok_1A2B1dBRhRYty9X4Tl4CiJYV";

        // Create a Charge:
        // $charge = \Stripe\Charge::create(array(
        //   "amount" => 1700,
        //   "currency" => "sgd",
        //   "source" => $token,
        //   "application_fee" => 1100,
        // ), array("stripe_account" => "acct_1A2BJTFNNM0NTgkE"));
       

        // var_dump($charge);
    }

    public function createMerchant()
    {
        \Stripe\Account::create(array(
            "managed" => false,
            "country" => "US",
            "email" => "bob@example.com",
            "business_url" => "test@example.com",
            "email" => "kb150316@gmail.com",
            // "external_account" => "www",
            "product_description" => "food",
            // "support_phone" => 83712332
        ));
    }



    public function accessToken(){
        define('CLIENT_ID', 'YOUR_CLIENT_ID');
          define('API_KEY', 'YOUR_API_KEY');
          define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
          define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');
          if (isset($_GET['code'])) { // Redirect w/ code
            $code = $_GET['code'];
            $token_request_body = array(
              'client_secret' => API_KEY,
              'grant_type' => 'authorization_code',
              'client_id' => CLIENT_ID,
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
            echo $resp['access_token'];
          } else if (isset($_GET['error'])) { // Error
            echo $_GET['error_description'];
          } else { // Show OAuth link
            $authorize_request_body = array(
              'response_type' => 'code',
              'scope' => 'read_write',
              'client_id' => CLIENT_ID
            );
            $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
            echo "<a href='$url'>Connect with Stripe</a>";
          }
    }

}
