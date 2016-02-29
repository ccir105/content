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
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber
    ];
});

$factory->define(Modules\Management\Entities\Project::class, function(Faker\Generator $faker){
   return [
       'title' => $faker->sentence,
       'description' => $faker->paragraph,
       'user_id' => App\User::all()->random()->id
   ];
});

$factory->define(Modules\Project\Entities\Page::class, function(Faker\Generator $faker){
    $orders = range(1,5);
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'order' => $orders[array_rand($orders)],
        'project_id' => Modules\Management\Entities\Project::all()->random()->id
    ];
});

$factory->define(Modules\Project\Entities\FieldGroup::class, function(Faker\Generator $faker){
    $orders = range(1,5);
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'order' => $orders[array_rand($orders)],
        'page_id' => Modules\Project\Entities\Page::all()->random()->id
    ];
});

$factory->define(Modules\Project\Entities\FieldValue::class, function(Faker\Generator $faker){
    $orders = range(1,5);
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'order' => $orders[array_rand($orders)],
        'group_id' => Modules\Project\Entities\FieldGroup::all()->random()->id,
        'field_id' => Modules\Project\Entities\Field::all()->random()->id,
        'value' => $faker->word
    ];
});