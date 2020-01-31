<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Post::class, function (Faker $faker) {
    return [
      'title'   => $faker->sentence,
      'content' => $faker->sentence,
      'image'   => 'photo4.jpg',
      'date'    => '02/02/2020',
      'views'   => $faker->numberBetween(0, 5000),
      'category_id' => 1,
      'user_id' => 1,
      'status' => 1,
      'is_featured' => 0
    ];
});

$factory->define(Category::class, function (Faker $faker) {
  return [
    'title'   => $faker->word,
  ];
});

$factory->define(Tag::class, function (Faker $faker) {
  return [
    'title'   => $faker->word,
  ];
});
