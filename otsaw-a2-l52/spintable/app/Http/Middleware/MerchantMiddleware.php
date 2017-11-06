<?php

namespace App\Http\Middleware;

use Closure;

use App\User;

class MerchantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token =  ($request->get("token"))? $request->get("token") : false;

        $permission = true;
        if($token) {
            $checkToken = User::where('token',$token);
            if(!$checkToken->count())  $permission = false;
            else  {
                $user = $checkToken->first();
                if($user->roles[0]->name != 'merchant') {
                    $permission = false;
                }
            }
        }
        if(!$token || !$permission) {
            echo json_encode([
                'result' => 0,
                'message' => "You don't have permission to access this page"
            ]);
            exit();
        }
        return $next($request);
    }
}
