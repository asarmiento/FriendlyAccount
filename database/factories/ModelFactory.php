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

$factory->define(AccountHon\Entities\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});


$factory->define(AccountHon\Entities\Accounting\Supplier::class, function ($faker) {
    return [
        'code' => $faker->numberBetween(1,99999),
        'charter' => $faker->numberBetween(1,99999),
        'name' => $faker->name,
        'token' => $faker->md5,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'phoneContact' => $faker->phoneNumber,
        'contact' => $faker->name,
        'school_id' => 2,
    ];
});

$factory->define(AccountHon\Entities\Restaurant\RawMaterial::class, function ($faker) {
    return [
        'code' => $faker->numberBetween(1,99999),
        'description' => $faker->company,
        'units' => $faker->randomElement(['unidades','gr','cups','tbs','tps','ml','l','lb','kg','oz']),
        'brand_id' => $faker->numberBetween(1,2),
        'token' => $faker->md5,
        'school_id' => 2,
        'type' => $faker->randomElement(['gravado','exento']),
    ];
});

$factory->define(AccountHon\Entities\Restaurant\Brand::class, function ($faker) {
    return [
        'name' => $faker->name,
        'token' => $faker->md5,
        'school_id' => 2,
    ];
});