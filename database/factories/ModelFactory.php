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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\UserGroup::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name . ' Group',
        'permissions' => json_decode('{"datasets":{"view":"true","raw":"true","create":"true","upload":"true","edit":"true","delete":"true","export":"true","rollback":"true"},"scores":{"view":"true","raw":"true","create":"true","refresh":"true","edit":"true","delete":"true","upload":"true"},"reports":{"view":"true","create":"true","save":"true","score":"true"},"usersAdmin":{"create":"true","delete":"true","assign":"true"},"properties":{"view":"true","show":"true","merge":"true","edit":"true","create":"true"},"export":{"view":"true","create":"true"}}', true)
    ];
});

$factory->define(\CityNexus\PropertyMgr\Comment::class, function (\Faker\Generator $faker){
   return [
        'title' => $faker->words(5),
        'comment' => $faker->paragraphs(2),
   ];
});

$factory->define(\CityNexus\PropertyMgr\Address::class, function(\Faker\Generator $faker){
    $address = [
        'house_num' => random_int(10, 9999),
        'name' => strtoupper($faker->lastName),
        'suftype' => 'street'
    ];
    $address['full_address'] = $address['house_num'] . ' ' . $address['name'] . ' ' . $address['suftype'];
    $property = \CityNexus\PropertyMgr\Property::create($address['full_address']);
    $address['property_id'] = $property->id;
    return $address;
});

$factory->define(\App\Client::class, function(\Faker\Generator $faker){
    $schema = $faker->word . '_' . $faker->randomNumber(4);
   return [
       'name' => $faker->name,
       'domain' => $schema . '.citynexus-io.app:8000',
       'schema' => $schema,
       'settings' => ['test' => true]
   ];
});