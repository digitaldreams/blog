<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 12/25/2017
 * Time: 11:36 PM
 */

namespace Blog\Database\Seeders;


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
        $this->call(PostsTableSeeder::class);
    }
}