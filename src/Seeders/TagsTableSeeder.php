<?php

namespace Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('blog_tags')->insert([
            [
                'slug' => str_slug('Grammer'),
                'name' => 'Grammer',
            ],
            [
                'slug' => str_slug('Sentence'),
                'name' => 'Sentence',
            ],
            [
                'slug' => str_slug('Tense'),
                'name' => 'Tense',
            ],
            [
                'slug' => str_slug('Level One'),
                'name' => 'Level One',
            ],
            [
                'slug' => str_slug('Level Two'),
                'name' => 'Level Two',
            ],
            [
                'slug' => str_slug('Level Three'),
                'name' => 'Level Three',
            ],
            [
                'slug' => str_slug('Level Four'),
                'name' => 'Level Four',
            ],
            [
                'slug' => str_slug('Level Five'),
                'name' => 'Level Five',
            ],
            [
                'slug' => str_slug('Narration'),
                'name' => 'Narration',
            ],
            [
                'slug' => str_slug('Parts of Speech'),
                'name' => 'Parts of Speech',
            ],
            [
                'slug' => str_slug('Exam'),
                'name' => 'Exam',
            ],
            [
                'slug' => str_slug('Words'),
                'name' => 'Words',
            ],
            [
                'slug' => str_slug('Games'),
                'name' => 'Games',
            ],
            [
                'slug' => str_slug('Tips'),
                'name' => 'Tips',
            ],
        ]);
    }
}
