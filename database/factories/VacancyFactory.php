<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Vacancy;
use Faker\Generator as Faker;

$factory->define(Vacancy::class, function (Faker $faker) {
    return [
        'test_task_url' => $faker->url,
        'job_title' => $faker->jobTitle,
    ];
});
