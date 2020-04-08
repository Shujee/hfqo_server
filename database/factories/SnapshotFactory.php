<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Snapshot;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Snapshot::class, function (Faker $faker) {
    return [
    'filename' => "https://randomuser.me/api/portraits/" .
                    ($faker->boolean($chanceOfGettingTrue = 50)? "men" : "women") .
                    "/" . $faker->randomNumber($nbDigits = 2) . ".jpg",
    'thumb_filename' => "https://randomuser.me/api/portraits/" .
                            ($faker->boolean($chanceOfGettingTrue = 50)? "men" : "women") .
                            "/" . $faker->randomNumber($nbDigits = 2) . ".jpg",
    'timestamp' => Carbon::now(),
    ];
});