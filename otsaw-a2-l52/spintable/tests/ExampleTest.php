<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
//    use DatabaseTransactions;
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

         $this->visit('/')
              ->see('Welcome to QUABII');
//
//        $this->click('Forgot password?')
//            ->see('Enter your email address')
//            ->seePageIs('password/reset');

//            ->seePageIs('/dashboard');
//        $this->json('POST', '/api/v1/consumers',
//            [
//                'firstname'=> 'hein thit1',
//                'lastname'=> 'thaw',
//                'password' => 'password',
//                'email'=> 'hein@ogmail.com',
//                'gender'=> 'male',
//                'contact_no'=> '98373952',
//                'address'=> '82, playfair road1',
//                'dob'=> '1990-04-01'
//            ])
//             ->seeJson([
//                 'message' => 'Register Successfully.',
//             ]);
    }
}
