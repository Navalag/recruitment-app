<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Applicant;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Applicant::class, function (Faker $faker) {
    return [
        'vacancy_id' => function() {
            return factory('App\Vacancy')->create()->id;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'status' => 'created',
        'unique_key' => uniqid(),
    ];
});
