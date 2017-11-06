<?php 

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use App\Role;
use App\Permission;
use Illuminate\Http\Request;



class RoleController extends Controller 
{
	public function index()
	{
		$roles = Role::all();
		return view('role.roleList')->with('roles', $roles);
	}

	public function getView($id)
	{
		$role = Role::find($id);
		$roleData = array(
				'id' => $role->id,
				'name' => $role->name,
				'display_name' => $role->display_name,
				'description' => $role->description,
				'roles' => $this->getPermissionName($role->permissions),
			);
		return view('role.viewRole')->with('role', $roleData);
	}

	private function getPermissionName($roles)
	{
		$roleName = [];
		$count = 0;
		foreach ($roles as $role) {
			$roleName[$count]=$role->display_name;
			$count = $count + 1;
		}
		return $roleName;
	}

	public function getCreate()
	{
		$permissions = Permission::all();
		return view('role.createRole')->with('permissions', $permissions);
	}

	public function postCreate(Request $request)
	{
		$roles = $request->all();
		$rules = array(
			'name' => 'required|max:50',
            'display_name' => 'required|max:20',
            'description' => 'required',
            'permissions' => 'required'
			);
		// return $roles;
		$validator = Validator::make($roles, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {
        	$roleData = array(
        		'name' => $roles['name'],
        		'display_name' => $roles['display_name'],
        		'description' => $roles['description'],
        		'permission' => $roles['permissions']
        		);
        	$role = Role::create($roleData);
            $role->attachPermissions($roles['permissions']);
        	return redirect('role/role-list')->with('success', 'Role Successfully Created');
        }
	}

	public function getEdit($id)
	{
		$roles = Role::find($id);
		$roleData = array(
			'id' => $roles->id,
			'name' => $roles->name,
			'display_name' => $roles->display_name,
			'description' => $roles->description,
			'selected_permission' => $this->getPermission($roles->permissions),
			'permissions' => Permission::all()
			);
		return view('role.editRole')->with('role', $roleData);
		// return $roleData;
	}

	private function getPermission($roles)
	{
		$roleId = [];
		$count = 0;
		foreach ($roles as $role) {
			$roleId[$count] = $role->id;
			$count = $count + 1;
		}
		return $roleId;
	}

	public function postUpdate(Request $request)
	{
		$roles = $request->all();

		$exisRole = Role::find($roles['id']);

		$rules = array(
			'name' => 'required|max:50',
            'display_name' => 'required|max:20',
            'description' => 'required',
            'permissions' => 'required'
			);

		$validator = Validator::make($roles, $rules);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        else
        {
        	$exisRole->name = $roles['name'];
        	$exisRole->display_name = $roles['display_name'];
        	$exisRole->description = $roles['description'];
        	$exisRole->save();
        	$exisRole->perms()->detach($this->getPermission($exisRole->permissions));
        	$exisRole->attachPermissions($roles['permissions']);

        	return redirect('role/role-list')->with('success', 'Role Successfully Updated');
        }
	}

	public function doDelete($id)
	{
		$role = Role::find($id);
		$role->delete();

		return Redirect::back()->with('success', 'Role Successfully Deleted');
	}
}

