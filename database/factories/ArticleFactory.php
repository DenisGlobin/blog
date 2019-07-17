<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'full_text' => $faker->text(1000),
        'short_text' => $faker->sentence,
        'is_active' => true,
        'user_id' => 2,
    ];
});
