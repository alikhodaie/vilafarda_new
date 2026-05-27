<?php


namespace App\Policies\Category;


use App\Models\Category;
use App\Models\User;

trait FAQCategoryPolicy
{
    public function indexFAQ(User $user): bool
    {
        return $user->hasPermissionTo('faq-categories:index');
    }

    public function createFAQ(User $user): bool
    {
        return $user->hasPermissionTo('faq-categories:create');
    }

    public function updateFAQ(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('faq-categories:update') && $category->section === Category::FAQ;
    }

    public function deleteFAQ(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('faq-categories:destroy') && $category->section === Category::FAQ;
    }
}
