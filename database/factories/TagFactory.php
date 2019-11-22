<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $tags = ['php', 'laravel', 'vuejs', 'html', 'css'];
    return [
        'question_id' => App\Question::pluck('id')->random(),
        'tag' => $tags[rand(0,4)]
    ];
});
