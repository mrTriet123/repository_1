<?php 

namespace App\Http\Controllers;

use Validator;
use Redirect;
use App\User;
use App\Role;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Zizaco\Entrust\Middleware\EntrustRole;
use DB;
use App\Driver;
use Helpers;
use Illuminate\Support\Facades\Input as Input;
use Hash;
use App\Document;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Collection;

class UserController extends Controller 
{
	public function index()
	{
		exit('aa');
		$limit = 10;
		$users = DB::table('users')
                ->select([
                     'users.id as id',
                     'users.firstname as firstname',
                     'users.lastname as lastname',
                     'users.email as email',
                     'roles.display_name as role'
                 ])
                 ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                 ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                 ->where('roles.name','!=', 'consumer')->where('roles.name','!=', 'driver')->paginate($limit);

		return view('user.userList')->with('users', $users);
	}

	private function getRole($roles)
	{
		foreach ($roles as $role) {
			$userRole = $role->display_name;
		}

		return $userRole;
	}

	public function getView($id)
	{
		$user = User::find($id);
		$userData = array(
				'id' => $user->id,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'email' => $user->email,
				'role' => $this->getRole($user->roles),
			);
		return view('user.viewUser')->with('user', $userData);
	}

	public function getCreate()
	{
		$roles = Role::all();
		return view('user.createUser')->with('roles', $roles);
	}

	public function postCreate(Request $request)
	{
		$users = $request->all();
        $rules = array(
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required'
            );

        $validator = Validator::make($users, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {  
        	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			    $pass = array(); //remember to declare $pass as an array
			    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			    for ($i = 0; $i < 8; $i++) {
			        $n = rand(0, $alphaLength);
			        $pass[] = $alphabet[$n];
			    }
			$random_pass = implode($pass);

        	$userData = array(
                'firstname' => $users['firstname'],
                'lastname' => $users['lastname'],
                'email' => $users['email'],
                'password' => bcrypt($random_pass)
                );

            $user = User::create($userData);
            $user->attachRole($users['role']);
            $data =array(
            	'firstname' => $user->firstname,
            	'lastname' => $user->lastname,
            	'email' => $user->email,
            	'password' => $random_pass,
            	'role' => $user->roles
            	);
            $full_name = $user->firstname.' '.$user->lastname;
            $user_email = $user->email;
            Mail::send('user.email', $data, function($message) use ($full_name, $user_email)
			{
				$message->to($user_email, $full_name)->subject('Temporary Password');
			});

            return redirect('user/user-list')->with('success', 'User Successfully Created');
        }
	}

	public function getEdit($id)
	{

		$user = User::find($id);
		$userData = array(
				'id' => $user->id,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'email' => $user->email,
				'userrole' => $this->getRoleid($user->roles),
				'roles' => Role::all()
			);
		return view('user.editUser')->with('user',$userData);
	}

	private function getRoleid($roles)
	{

		foreach ($roles as $role) {
			$userRole = $role->id;
		}

		return $userRole;
	}

	public function postUpdate(Request $request)
	{
		$users = $request->all();

		$exisUser = User::find($users['id']);

		if($users['email'] == $exisUser->email)
		{
			$rules = array(
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'role' => 'required'
            );
		}
		else
		{
			$rules = array(
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required'
            );
		}

        $validator = Validator::make($users, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {  
        	$exisUser->firstname = $users['firstname'];
        	$exisUser->lastname = $users['lastname'];
        	$exisUser->email = $users['email'];
        	$exisUser->save();
        	$exisUser->detachRoles($exisUser->roles);
        	$exisUser->attachRole($users['role']);

        	return redirect('user/user-list')->with('success', 'User Successfully Updated');
        }
	}

	public function doDelete($id)
	{
		$user = User::find($id);
		$user->delete();

		return Redirect::back()->with('success', 'User Successfully Deleted');
	}

	public function getProfile()
	{
		$id = Auth::user()->id;

        return Auth::user();
//
//		if(Auth::user()->hasRole('driver'))
//		{
//			$driverID = Driver::where('user_id', $id)->first()->id;
//			$driverDetails = Helpers::getDriver($driverID);
//			return view('driver.viewProfile')->with('users', $driverDetails);
//		}
//		else
//		{
//			$userDetail = User::find($id);
//			return view('user.viewProfile')->with('users', $userDetail);
//		}
	}

	public function updateProfile(Request $request)
	{
		$id = Auth::user()->id;
		$user = User::find($id);
		$updateData = $request->all();

		if(Input::get('oldpassword') != null || Input::get('newpassword') != null || Input::get('confirmnewpassword') != null)
		{
			$rules = array(
	            'firstname' => 'required|max:255',
	            'lastname' => 'required|max:255',
	            'oldpassword' => 'required|min:8',
	            'newpassword' => 'required|min:8',
	            'confirmnewpassword' => 'required|min:8',
	            'address' => 'required',
	            'dob' => 'required',
	            'contact_no' => 'required',
	            'gender' => 'required',
	            'profile_image' => 'image'
            );
		}
		else
		{
			$rules = array(
	            'firstname' => 'required|max:255',
	            'lastname' => 'required|max:255',
	            'address' => 'required',
	            'dob' => 'required',
	            'contact_no' => 'required',
	            'gender' => 'required',
	            'profile_image' => 'image'
            );
		}
		

        $validator = Validator::make($updateData, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        { 
        	unset($updateData['email']);
        	if (Input::get('newpassword')) {
        		$oldData = array(
        			'oldpassword' => Input::get('oldpassword'),
        			'newpassword' => Input::get('newpassword'),
        			'confirmnewpassword' => Input::get('confirmnewpassword')
        			);
                if(!Hash::check(Input::get('oldpassword'), $user->password))
                {
                    return Redirect::back()->with('error', 'Please Key In The Right Old Password');
                }
                else
                {
                    if(Input::get('newpassword') != Input::get('confirmnewpassword'))
                    {
                        return Redirect::back()->with('error', 'New password and confirm password not match!');
                    }
                    else
                    {
                        $updateData['newpassword'] = bcrypt(Input::get('newpassword')); 
                    }
                    
                }
            }
            $userData = array(
                'firstname' => $updateData['firstname'],
                'lastname' => $updateData['lastname']
                );
            if($updateData['newpassword'] != null)
        	{
        		$userData['password'] = $updateData['newpassword'];
        	}
            $user->update($userData);
            $driverData = array(
                'user_id' => $id,
                'gender' => $updateData['gender'],
                'contact_no' => $updateData['contact_no'],
                'address' => $updateData['address'],
                'dob' => $updateData['dob'],
                );
            Driver::where('user_id', $id)->update($driverData);
            if(isset($updateData['profile_image']))
            {
            	$profileImage = Input::file('profile_image');
            	$driver = Driver::where('user_id', $id)->first();
            	$oldImage = $driver->documents()->where('file_type', 'Profile')->get();
            	if(isset($oldImage->first()->name))
        		{
            		$delete_image = UploadFileController::deleteS3('profile_images' , $oldImage->first()->name);
            		Document::find($oldImage->first()->id)->delete();
            	}
            	$profile_img = UploadFileController::saveS3('profile_images' , $profileImage, 'Profile', $driver->id);
            	// return response()->json($output->first());
            }
        	return redirect('driver_profile')->with('success', 'Successfully Updated');
        }
	}

	public function updateUserProfile(Request $request)
	{
		$id = Auth::user()->id;
		$user = User::find($id);
		$updateData = $request->all();

		$rules = array(
	            'firstname' => 'required|max:255',
	            'lastname' => 'required|max:255'
            );

		$validator = Validator::make($updateData, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        { 
        	unset($updateData['email']);
        	if (Input::get('newpassword') != null) {
        		$oldData = array(
        			'oldpassword' => Input::get('oldpassword'),
        			'newpassword' => Input::get('newpassword'),
        			'confirmnewpassword' => Input::get('confirmnewpassword')
        			);
                if(!Hash::check(Input::get('oldpassword'), $user->password))
                {
                    return Redirect::back()->with('error', 'Please Key In The Right Old Password');
                }
                else
                {
                    if(Input::get('newpassword') != Input::get('confirmnewpassword'))
                    {
                        return Redirect::back()->with('error', 'New password and confirm password not match!');
                    }
                    else
                    {
                        $updateData['newpassword'] = bcrypt(Input::get('newpassword')); 
                    }
                    
                }
            }
        	$userData = array(
                'firstname' => $updateData['firstname'],
                'lastname' => $updateData['lastname']
                );
        	if($updateData['newpassword'] != null)
        	{
        		$userData['password'] = $updateData['newpassword'];
        	}
            $user->update($userData);
            return redirect('user_profile')->with('success', 'Successfully Updated');
        }
	}

}


