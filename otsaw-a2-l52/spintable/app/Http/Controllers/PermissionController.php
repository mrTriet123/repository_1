<?php 

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use App\Role;
use App\Permission;
use Illuminate\Http\Request;



class PermissionController extends Controller 
{
	public function index()
	{
		$permissions = Permission::orderBy('created_at','desc')->paginate(10);
		return view('permission.permissionList')->with('permissions', $permissions);
	}

	public function getView($id)
	{
		$permission = Permission::find($id);
		$permissionData = array(
				'id' => $permission->id,
				'name' => $permission->name,
				'display_name' => $permission->display_name,
				'description' => $permission->description
			);
		return view('permission.view')->with('permission', $permissionData);
	}
	public function createPermission()
	{
		return view('permission.create');
	}

	public function postCreate(Request $request)
	{
		$permissions = $request->all();
		$rules = array(
			'name' => 'required|max:50',
            'display_name' => 'required|max:20',
            'description' => 'required'
			);
		$validator = Validator::make($permissions, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {
        	$permissionData = array(
        		'name' => $permissions['name'],
        		'display_name' => $permissions['display_name'],
        		'description' => $permissions['description']
        		);
        	$newPermission = Permission::create($permissionData);
        }
        return redirect('permissions/permissions-list')->with('success', 'Permission Successfully Created');
	}

	public function editPermission($id)
	{
		$permission = Permission::find($id);
		return view('permission.edit')->with('permission', $permission);
	}

	public function updatePermission(Request $request)
	{
		$permissions = $request->all();

		$exisPermission = Permission::find($permissions['id']);

		$rules = array(
			'name' => 'required|max:50',
            'display_name' => 'required|max:20',
            'description' => 'required'
			);

		$validator = Validator::make($permissions, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {
        	$exisPermission->name = $permissions['name'];
        	$exisPermission->display_name = $permissions['display_name'];
        	$exisPermission->description = $permissions['description'];
        	$exisPermission->save();

        	return redirect('permissions/permissions-list')->with('success', 'Permission Successfully Updated');
        }
	}

	public function doDelete($id)
	{
		$permission = Permission::find($id);
		$permission->delete();

		return Redirect::back()->with('success', 'Permission Successfully Deleted');
	}
}

