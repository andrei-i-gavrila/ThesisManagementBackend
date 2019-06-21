<?php

/* @var $factory Factory */

use App\Model;
use App\Models\ExamSession;
use App\Models\Paper;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Paper::class, function (Faker $faker) {
    return [
        'exam_session_id' => $faker->randomElement(ExamSession::all()->pluck('id')->toArray()),
        'name' => $faker->words(2, true),
        'student_id' => factory(User::class),
        'link' => $faker->url,
    ];
});
