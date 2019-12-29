<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Exam;

use Faker\Generator as Faker;

$factory->define(Exam::class, function (Faker $faker) {
    $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
    $filenameXPS = $name .'.xps';
    $filenameXML = $name .'.xml';

    return [
        'name' => strtoupper($faker->randomLetter) . $faker->numberBetween($min = 100, $max = 999) . '_' . $faker->numberBetween($min = 100, $max = 999),
        'xps_file_name' => $filenameXPS,
        'xml_file_name' => $filenameXML,
        'qa_count' => $faker->numberBetween($min = 50, $max = 1000),
        'is_expired' => $faker->boolean(10)
    ];
});
