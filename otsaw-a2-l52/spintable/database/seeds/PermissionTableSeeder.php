<?php
use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder {

	public function run()
	{
		//user
		Permission::create(['name' => 'create-user', 'display_name' => 'Create User', 'description' => 'Create Fiv User']);
		Permission::create(['name' => 'view-user', 'display_name' => 'View User', 'description' => 'View Fiv User']);
		Permission::create(['name' => 'edit-user', 'display_name' => 'Edit User', 'description' => 'Edit Fiv User']);
		Permission::create(['name' => 'delete-user', 'display_name' => 'Delete User', 'description' => 'Delete Fiv User']);
		//role
		Permission::create(['name' => 'create-role', 'display_name' => 'Create Role', 'description' => 'Create User Role']);
		Permission::create(['name' => 'view-role', 'display_name' => 'View Role', 'description' => 'View User Role']);
		Permission::create(['name' => 'edit-role', 'display_name' => 'Edit Role', 'description' => 'Edit User Role']);
		Permission::create(['name' => 'delete-role', 'display_name' => 'Delete Role', 'description' => 'Delete User Role']);

		//customer transaction
		Permission::create(['name' => 'customer-transaction', 'display_name' => 'Customer Transaction', 'description' => 'View All Transaction Information']);
		Permission::create(['name' => 'export-customer-transaction', 'display_name' => 'Export Customer Transaction', 'description' => 'Export Transaction Information']);
	}
}