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
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(\App\UserGroup::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name . ' Group',
        'permissions' => json_decode('{"datasets":{"view":"true","raw":"true","create":"true","upload":"true","edit":"true","delete":"true","export":"true","rollback":"true"},"scores":{"view":"true","raw":"true","create":"true","refresh":"true","edit":"true","delete":"true","upload":"true"},"reports":{"view":"true","create":"true","save":"true","score":"true"},"usersAdmin":{"create":"true","delete":"true","assign":"true"},"properties":{"view":"true","show":"true","merge":"true","edit":"true","create":"true"},"export":{"view":"true","create":"true"}}', true)
    ];
});

