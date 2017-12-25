<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 12/25/2017
 * Time: 11:36 PM
 */

namespace Blog\Database\Seeders;


use Blog\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $generator = \Faker\Factory::create();

        Category::create([
            'title' => $generator->word,
            'slug' => $generator->slug
        ]);
    }
}