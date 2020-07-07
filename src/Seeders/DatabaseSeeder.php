<?php

namespace Blog\Seeders;

use Blog\Database\Seeders\TagsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
    }
}
