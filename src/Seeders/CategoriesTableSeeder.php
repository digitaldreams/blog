<?php

namespace Blog\Seeders;

use Blog\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $generator = \Faker\Factory::create();

        Category::create([
            'title' => $generator->word,
            'slug' => $generator->slug,
        ]);
    }
}
