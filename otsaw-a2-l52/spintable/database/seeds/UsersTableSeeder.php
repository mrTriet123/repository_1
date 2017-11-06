<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// factory(App\User::class, 10)->create()->each(function ($u) {
	    //     $u->merchant()->save(factory(App\Merchant::class)->make());
	    // });

	    // factory(App\Restaurant::class, 1)->create()->each(function ($u) {
	        
	    // });

        // factory(App\Dish::class, 100)->create()->each(function ($u) {
            
        // });

        factory(App\DishSize::class, 100)->create()->each(function ($u) {
            
        });

    }
}
