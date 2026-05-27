<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $articles = Article::all();
            $users = User::all();
            $faker = Factory::create('fa_IR');

            foreach ($articles as $article) {
                for ($i = 0; $i < rand(1, 10); $i++) {
                    $article->addComment($faker->realText, $faker->email, "{$faker->firstName} {$faker->lastName}", null, 0, $users->random()->first()->id);
                }
            }
        }
    }
}
