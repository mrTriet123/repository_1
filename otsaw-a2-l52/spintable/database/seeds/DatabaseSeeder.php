<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(LanguageSeeder::class);
        //$this->call(OAuthClientsSeeder::class);
        //$this->call(OAuthUsersSeeder::class);
        $this->call(UsersTableSeeder::class);
        //$this->call('PermissionTableSeeder');


        //CLIENT ID f3d259ddd3ed8ff3843839b
        //CLIENT SECRET 4c7f6f8fa93d59c45502c0ae8c4a95b
        //API Oauth
    }
}
