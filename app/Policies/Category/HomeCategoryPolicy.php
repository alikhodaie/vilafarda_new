<?php


namespace App\Policies\Category;


use App\Models\Category;
use App\Models\User;

trait HomeCategoryPolicy
{
    public function indexHome(User $user): bool
    {
        return $user->hasPermissionTo('home-categories:index');
    }

    public function createHome(User $user): bool
    {
        return $user->hasPermissionTo('home-categories:create');
    }

    public function updateHome(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('home-categories:update') && $category->section === Category::HOME;
    }

    public function deleteHome(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('home-categories:destroy') && $category->section === Category::HOME;
    }
}
