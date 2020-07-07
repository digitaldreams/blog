<?php

namespace Blog\Seeders;

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

        for ($i = 0; $i < 10; $i++) {
            $image = $generator->image(storage_path('app/public/images'));
            $fileName = pathinfo($image, PATHINFO_BASENAME);
            Post::create([
                'user_id' => $user->id,
                'title' => $generator->realText(40),
                'slug' => $generator->slug,
                'body' => $generator->realText(),
                'status' => 'published',
                'category_id' => Category::all()->random(1)->first()->id,
                'image' => 'images/' . $fileName,
            ]);
        }

    }

}
