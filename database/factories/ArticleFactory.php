<?php

use Faker\Generator as Faker;
use App\Article;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->text(20),
        'body' => $faker->text(200),
    ];
});
