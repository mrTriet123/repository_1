<?php

namespace App\Http\Controllers\API;
/**
 * Created by PhpStorm.
 * User: otsaw-dev
 * Date: 11/3/16
 * Time: 1:33 PM
 */

use Illuminate\Support\Facades\Input;
use Validator;
use App\Customer;
use Illuminate\Http\Response;
use Hash;
use App\User;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends ApiController
{
    protected $statusCode = 200;

    public function store()
    {
        $email = Input::get('email');

        //email
        $users = User::where('email', '=', $email)->get();

        if(isset($users[0]))
        {
            $currentUser = $users[0];
            $currentUser = User::find($currentUser->id);

            //Generate New Random Password
            $tmpPassword = $this->temp_pass();
            $hashedPassword = Hash::make($tmpPassword);

            $updateData = array(
                'password' => $hashedPassword,
                'temp_pass' => $tmpPassword
            );
            $currentUser->update($updateData);

            $fullName = $currentUser['firstname'].' '.$currentUser['lastname'];
            $userEmail = $currentUser['email'];
            $sendMessage = array(
                'firstname' => $currentUser['firstname'],
                'lastname' => $currentUser['lastname'],
                'password' => $tmpPassword
            );

            Mail::send('user.email', $sendMessage, function($message) use ($fullName, $userEmail)
            {
                $message->to($userEmail, $fullName)->subject('SpinTable Reset Password Request');
            });

            return $this->respond(['data' => ['message' => 'Password will send to '.$userEmail.' soon']]);

        }
        else
        {
            return $this->respondNotFound('User does not exist');
        }

    }

    private function temp_pass()
    {
        $alphabet = "abcdefghjkmnopqrstuwxyzABCDEFGHJKMNPQRSTUWXYZ123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $temporary_pass = implode($pass);
        return $temporary_pass;
    }
} 