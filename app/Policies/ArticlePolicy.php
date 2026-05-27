<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('articles:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('articles:create');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasPermissionTo('articles:update');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasPermissionTo('articles:destroy');
    }
}
