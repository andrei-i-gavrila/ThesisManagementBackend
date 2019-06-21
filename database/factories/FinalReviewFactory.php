<?php

/* @var $factory Factory */

use App\Model;
use App\Models\FinalReview;
use App\Models\Paper;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(FinalReview::class, function (Faker $faker) {
    return [
        'paper_id' => factory(Paper::class),
        'professor_id' => $faker->randomElement(User::professor()->pluck('id')->toArray()),
        'overall' => $faker->numberBetween(1, 4),
        'grade_recommendation' => $faker->numberBetween(1, 3),
        'structure' => $faker->numberBetween(1, 5),
        'originality' => $faker->numberBetween(1, 5),
        'literature_results' => $faker->numberBetween(1, 5),
        'references' => $faker->numberBetween(1, 5),
        'form' => $faker->numberBetween(1, 5),
        'result_analysis' => $faker->numberBetween(1, 5),
        'result_presentation' => $faker->numberBetween(1, 5),
        'app_complexity' => $faker->numberBetween(1, 5),
        'app_quality' => $faker->numberBetween(1, 5),
        'observations' => $faker->text,
    ];
});
