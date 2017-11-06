<?php
namespace App\Http\Controllers\API\Admin;

use Illuminate\Support\Facades\Input as Input;
//use App\Http\Requests\Request;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\User;
use App\Role;
use Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\API\ApiController;

class AdminController extends ApiController
{
    function __construct(Request $request)
    {
        //$token = isset($_GET['token'])? $_GET['token'] : false;
    	$token =  ($request->get("token"))? $request->get("token") : false;

    	$permission = true;
        if($token) {
            $checkToken = User::where('token',$token);
        	if(!$checkToken->count())  $permission = false;
        	else  {
        		$user = $checkToken->first();
        		if($user->roles[0]->name != 'admin') {
        			$permission = false;
        		}
        	}
        }

    	if(!$token || !$permission) {
			echo json_encode([
				'result' => 0,
				'messages' => "You don't have permission to access this page"
			]);
	        exit();
    	}
    }

    public function logout(){
    	/*
    	if(Auth::logout()) {
    		$user = Auth::user();	
	    	$user->token = '';
	    	$user->save();
    		return response()->json([
            	'result' => 1,
        	],200);
    	} else {
    		return response()->json([
            	'result' => 0,
        	],200);
    	}
    	*/

    	$token = $_GET['token'];
    	$user = User::where('token',$token)->first();
    	$user->token = '';
    	$user->save();
		return response()->json([
        	'result' => 1,
    	],200);
    }
}