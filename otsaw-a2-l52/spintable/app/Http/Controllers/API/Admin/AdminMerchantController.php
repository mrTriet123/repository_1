<?php
namespace App\Http\Controllers\API\Admin;

use Illuminate\Support\Facades\Input as Input;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use App\Customer;
use App\Installation;
use Auth;
use DB;
use Mail;
use Illuminate\Http\Response;
use App\Http\Controllers\API\Admin\AdminController;
class AdminMerchantController extends AdminController
{
    protected $statusCode = 200;
    protected $stripe;

    public function listMerchant(){ 
        $merchants = Role::find(2)->users()->select('id','firstname','lastname','email')->get();
        
        return response()->json([
            'result' => 1,
            'data' => $merchants
        ],200);
    }
    
    public function detailMerchant($id){

        $user = User::find($id);

        if($user->roles[0]->name == 'merchant'){
            $responseData = [
                'result' => 1,
                'data' => [
                    'id' => $user->id,
                    'firstname' => $user->firstname, 
                    'lastname' => $user->lastname,
                    'email' => $user->email, 
                ]
            ];
            return $this->respond($responseData);
        }else{
            return response()->json([
                'result' => 0,
                'messages' => "You don't have permission to access this page"
            ],200);
        }
        
    }

    public function generatedPassword(Request $request){
        $email = $request->input('email');
        $user = User::where('email',$email)->first();

        if(!(empty((array)$user)) && $user->roles[0]->name == 'merchant'){
            //$password = $this->generateRandomPassword();
            $password = User::generateRandomPassword();
            $user->password = bcrypt($password);

            if($user->save()) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $url_get = $_SERVER['HTTP_REFERER'];
                    $pieces = explode("/", $url_get);
                    $url = ($pieces[0].'//'.$pieces[2]);
                }else{
                    $url = 'http://localhost:3000';
                }
                $mail_data = array('firstname'=>$user->firstname,'password'=>$password,'url'=>$url,'token'=>$user->token);
                Mail::send('email.resetpass',$mail_data , function($message) {
                    $message->to(Input::get('email'), Input::get('firstname').' '.Input::get('lastname'))->subject('Reset password!');
                });
                return response()->json([
                    'result' => 1,
                    'messages' => [
                        'user_id' => $user->id,
                        'password' => $password
                    ]
                ],200);
            } else {
                return response()->json([
                    'result' => 0,
                    'messages' => "Can not generated password"
                ],200);
            }
        } else {
            return response()->json([
                'result' => 0,
                'messages' => "Something wrong with input data. Can not updated this account!"
            ],200);
        }
        
    }

    public function createMerchant(Request $request){
        $checkEmail = User::where('email',$request->input('email'))->count();
        if(!$checkEmail) {
            $user = new User;

            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->token = '';
            $user->active = 1;

            try {
                if($user->save()) {
                    $data = [
                       'role_id' => 2,
                       'user_id' => $user->id,
                    ];

                    DB::table('role_user')->insert($data);

                    return response()->json([
                        'result' => 1,
                        'messages' => [
                            'user_id' => $user->id
                        ]
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

    public function updateMerchant(Request $request,$id){
        $user = User::find($id);

        if(!(empty((array)$user)) && $user->roles[0]->name == 'merchant'){
            $checkEmail = User::where('email',$request->input('email'))->count();
            if(!$checkEmail) {
                $user->firstname = $request->input('firstname');
                $user->lastname = $request->input('lastname');
                $user->email = $request->input('email');
                //$user->password = bcrypt($request->input('password'));

                try {
                    if($user->save()) {
                        return response()->json([
                            'result' => 1,
                            'messages' => [
                                'user_id' => $user->id
                            ]
                        ],200);
                    } else {
                        return response()->json([
                            'result' => 0,
                            'messages' => "Account has not been updated"
                        ],200);
                    }
                } catch(\Exception $e) {
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
        } else {
            return response()->json([
                'result' => 0,
                'messages' => "Something wrong with input data. Can not updated this account!"
            ],200);
        }
    }

    public function deleteMerchant($id){

        $user = User::find($id);
        if($user->roles[0]->name == 'merchant'){
            DB::table('role_user')->where('user_id',$id)->delete();
            $user->delete();
            return response()->json([
                'result' => 1,
                'messages' => "Account has been deleted"
            ],200);
        }else{
            return response()->json([
                'result' => 0,
                'messages' => "Account has not been deleted"
            ],200);
        }
        
    }

    public function generateRandomPassword($length=6) {
        $valid_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789~!@#$%^&*()_`';
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
        return $random_string;
    }

}