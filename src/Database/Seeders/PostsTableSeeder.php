<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 12/25/2017
 * Time: 11:36 PM
 */

namespace Blog\Database\Seeders;


use Blog\Models\Category;
use Blog\Models\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

    public function run()
    {
        $generator = \Faker\Factory::create();
        $user = config('auth.providers.users.model');
        $user = new $user;
        $user = $user->first();
        Post::create([
            'user_id' => $user->id,
            'title' => $generator->realText(120),
            'slug' => $generator->slug,
            'body' => $generator->realText(),
            'status' => 'published',
            'category_id' => Category::all()->random(1)->first()->id,
            'image' => $generator->image(storage_path('app/public')),
        ]);
    }

}