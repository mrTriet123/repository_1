<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Merchant::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function(){
            return factory(App\User::class)->create()->id;
        },
        'mobile_no' => $faker->phoneNumber
    ];
});

$factory->define(App\Restaurant::class, function (Faker\Generator $faker) {
    $type = App\RestaurantType::lists('id')->all();
    return [
        'name' => $faker->company,
        'merchant_id' => function(){
            return factory(App\Merchant::class)->create()->id;  
        }, 
        'tel_no' => $faker->phoneNumber,
        'restaurant_type_id' => $faker->randomElement($type)
    ];
});

$factory->define(App\Restaurant::class, function (Faker\Generator $faker) {
    $type = App\RestaurantType::lists('id')->all();
    return [
        'name' => $faker->company,
        'merchant_id' => function(){
            return factory(App\Merchant::class)->create()->id;  
        }, 
        'tel_no' => $faker->phoneNumber,
        'restaurant_type_id' => $faker->randomElement($type)
    ];
});

$factory->define(App\Dish::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence($nbWords = 3, $variableNbWords = true)
    ];
});

$factory->define(App\DishSize::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence($nbWords = 3, $variableNbWords = true)
    ];
});