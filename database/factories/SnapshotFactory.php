<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Snapshot;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Snapshot::class, function (Faker $faker) {
    return [
    'filename' => $faker->word(),
    'timestamp' => Carbon::now(),
    ];
});