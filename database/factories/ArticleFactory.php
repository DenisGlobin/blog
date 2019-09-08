<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Article;
use App\User;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'full_text' => $faker->text(2000),
        'short_text' => $faker->paragraph(),
        'is_active' => true,
        'user_id' => rand(1, User::get()->count()),
    ];
});
