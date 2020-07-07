<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Blog\Enums\ActivityType;
use Blog\Models\Activity;
use Blog\Models\Post;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'activityable_type' => Post::class,
        'activityable_id' => factory(Post::class),
        'type' => ActivityType::getRandomValue(),
    ];
});
