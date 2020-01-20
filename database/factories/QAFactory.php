<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\QA;
use Faker\Generator as Faker;

$factory->define(QA::class, function (Faker $faker) {
    return [
        'question' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true, $asText = true),
        'answer' => $faker->words($nb = 3, $asText = true),
        'choices' => $faker->sentences($nb = 3, $asText = true)
    ];
});
