<?php

namespace App\View\Components\Blog;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categories = Category::query()->article()->parent()->withCount(['articles'])->get();
        $tags = Tag::query()->whereHas('articles')->inRandomOrder()->take(50)->get();
        $articles = Article::query()->latest()->take(5)->get();

        return view('components.blog.sidebar', compact(['categories', 'tags', 'articles']));
    }
}
