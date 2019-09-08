<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Article;
use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'message' => $faker->realText(),
        'user_id' => rand(1, User::get()->count()),
        'article_id' => rand(1, Article::get()->count()),
    ];
});
