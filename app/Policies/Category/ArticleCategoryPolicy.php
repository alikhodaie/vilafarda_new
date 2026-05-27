<?php


namespace App\Policies\Category;


use App\Models\Category;
use App\Models\User;

trait ArticleCategoryPolicy
{
    public function indexArticle(User $user): bool
    {
        return $user->hasPermissionTo('article-categories:index');
    }

    public function createArticle(User $user): bool
    {
        return $user->hasPermissionTo('article-categories:create');
    }

    public function updateArticle(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('article-categories:update') && $category->section === Category::ARTICLE;
    }

    public function deleteArticle(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('article-categories:destroy') && $category->section === Category::ARTICLE;
    }
}
