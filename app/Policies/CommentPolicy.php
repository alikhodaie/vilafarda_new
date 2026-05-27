<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('comments:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('comments:create');
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo('comments:update');
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasPermissionTo('comments:destroy');
    }
}
