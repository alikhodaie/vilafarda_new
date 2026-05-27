<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $faker = Factory::create('fa_IR');
            $admins = User::query()->admin()->get();
            $tags = Tag::all();
            $categories = Category::query()->where('section', Category::ARTICLE)->get();


            for ($i = 0; $i < rand(10, 20); $i++) {
                $admin = $admins->random()->first();
                $title = $faker->realText(100);

                $article = $admin->articles()->create([
                    'title' => $title,
                    'slug' => generateSlug($title),
                    'summary' => $faker->realText(),
                    'content' => $faker->realText(1000),
                    'meta' => explode(' ', $faker->realText()),
                ]);

//            $file = $faker->image();
//            $file = new UploadedFile($file, basename($file));
//            $article->updateImage($file);

                $article->tags()->attach($tags->random(rand(3, 10)));
                $article->categories()->attach($categories->random(1)->first());
            }
        }
    }
}
